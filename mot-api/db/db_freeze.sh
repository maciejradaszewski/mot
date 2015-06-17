#!/bin/bash
# Freezes the database by saving a mysql data dump.
# If a manual freeze file name is not supplied in $5, it will calculate one from the existing
# state of the DB schema/data setup scripts.
# This script must only be run on the vagrant VM, it won't work on OSX
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

echo "$(date) Dumping database $MyDATABASE to $ORIG_CKSUM_FILE"
mkdir -p $DUMP_DIR

# Chmod'ing is required so that apache user can read the files when doing a restore
chmod go+rx $DUMP_DIR
mysql -u$MyUSER -p$MyPASS -h $MyHOST --database=$MyDATABASE -B -N -e "SHOW TABLES" \
    | tr '\n' ',' \
    |  sed '$s/.$//' \
    | awk '{print "CHECKSUM TABLE " $0 " EXTENDED;" }' \
    | mysql -u$MyUSER -p$MyPASS -h $MyHOST --database=$MyDATABASE \
    | cat > $ORIG_CKSUM_FILE

chmod go+r $ORIG_CKSUM_FILE
mysqldump -u$MyUSER -p$MyPASS -h $MyHOST $MyDATABASE  --no-create-db --no-create-info --skip-triggers --quick > $DUMP_DATA_FILE
chmod go+r $DUMP_DATA_FILE

echo "$(date) Done dumping DB"
