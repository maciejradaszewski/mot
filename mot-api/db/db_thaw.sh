#!/bin/bash
#
# Truncate and repopulate only those tables that content have been modified.
# It speeds up fitnesse tests as we don't have to recreate the whole db every time. We only reset particular tables.
#
# Params:
# [user to execute as] - default root
# [password] - default root
# [host] - default localhost
# [database to create] - default mot (can be overridden by MOT_DATABASE env variable)
# [checksum] - no default

set -o errexit

MyUSER=${1-"root"}
MyPASS=${2-"password"}
MyHOST=${3-"localhost"}
MyDATABASE=${4-${MOT_DATABASE-"mot"}}
DB_CHECKSUM=$5

if [ "$5" = "" ]; then
    DB_CHECKSUM=$(./db_checksum.sh)
fi

DUMP_DIR=/tmp/dbdumps
ORIG_CKSUM_FILE="$DUMP_DIR/mysqldb.tabcksum-$DB_CHECKSUM"
DUMP_DATA_FILE="$DUMP_DIR/mysqldb.data-$DB_CHECKSUM"

if [ ! -e $ORIG_CKSUM_FILE ]; then
    echo "No DB dump file exists for DB in current config. Run db_freeze first"
    exit 1;
fi;

# Retrieves tables that have changed; outputs in the format: table1 table2 table3 ...
DIFF_TABLES=$(diff --unchanged-line-format= --old-line-format= --new-line-format='%L' <(mysql -u$MyUSER -p$MyPASS --database=$MyDATABASE -B -N -e "SHOW TABLES" | tr '\n' ','|  sed '$s/.$//' | awk '{print "CHECKSUM TABLE " $0 " EXTENDED;" }' | mysql -u$MyUSER -p$MyPASS --database=$MyDATABASE ) <(cat $ORIG_CKSUM_FILE) | awk '{print $1}')

SCHEMA_PREFIX_LENGTH=${#MyDATABASE}+1
TRUNCATE_SQL=""
GREP_INS_PATTERNS=""

# Creates parts of final sql script:
# 1) Truncate commands: TRUNCATE table1; TRUNCATE table2;...
# 2) Grep parameters to parse INSERT statements for the affected table: -e 'INSERT INTO `table1`' -e 'INSERT INTO `table2`'
for TABLE in $DIFF_TABLES;
do
   echo $TABLE" restored."
   TRUNCATE_SQL="$TRUNCATE_SQL TRUNCATE $TABLE;"
   GREP_INS_PATTERN="-e 'INSERT INTO \`"${TABLE:${SCHEMA_PREFIX_LENGTH}}"\`' "
   GREP_INS_PATTERNS="$GREP_INS_PATTERNS $GREP_INS_PATTERN"

done;

# if any tables to reset, form an SQL script:
# prologue, truncate statements, insert statements, epilogue
if [ -n "$GREP_INS_PATTERNS" ]; then

    # form and execute grep to parse INSERT statement for each table
    GREP_CMD="grep "$GREP_INS_PATTERNS" "$DUMP_DATA_FILE
    INSERT_STATEMENTS=$(eval $GREP_CMD)

    SQL_PROLOGUE="SET foreign_key_checks = 0, autocommit = 0, unique_checks = 0, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';"
    SQL_EPILOGUE="SET foreign_key_checks = 1, autocommit = 1, unique_checks = 1;"
    RESET_SQL=$SQL_PROLOGUE" "$TRUNCATE_SQL" "$INSERT_STATEMENTS" "$SQL_EPILOGUE
    # execute the script
    mysql -u$MyUSER -p$MyPASS -h$MyHOST $MyDATABASE <<< "$RESET_SQL"
else
    echo "Nothing to do."
fi
