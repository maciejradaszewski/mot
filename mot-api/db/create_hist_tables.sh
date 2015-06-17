#!/bin/bash
#
# Creates tables for storing history of tables (each table has *_hist) table. Adds triggers on_insert and on_update that populate *_hist tables
#
# Params:
# [user to execute as] - default root
# [password] - default root
# [host] - default localhost
# [database to create] - default mot (can be overridden by MOT_DATABASE env variable)
# [user to be granted db access] - default motdbuser

set -o errexit

MyUSER=${1-"root"}
MyPASS=${2-"password"}
MyHOST=${3-"localhost"}
MyDATABASE=${4-${MOT_DATABASE-"mot"}}
MyGRANTUSER=${5-"motdbuser"}

temp_sql_script="./historical_tables_script.sql_temp"

echo "$(date) Create historical tables and adding triggers"

rm -rf $temp_sql_script

sed "s/#SCHEMA#/$MyDATABASE/g" ./dev/schema/create_hist_tables.sql \
    | mysql -u $MyUSER -p$MyPASS -h $MyHOST $MyDATABASE \
    | sed -e 's/^cmd$/-- Generated script/' -e 's/\\n/\n/g' > $temp_sql_script

if [ "$?" -eq "0" ]; then
    mysql -u $MyUSER -p$MyPASS -h $MyHOST $MyDATABASE < $temp_sql_script
    echo "$(date) Hist tables and triggers added"
else
    echo "$(date) Hist tables and triggers FAILED!!!"
fi
