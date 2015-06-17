#!/bin/bash
#
# Creates database, creates schema and grants privilages for given user
#
# Params:
# [user to execute as] - default root
# [password] - default root
# [host] - default localhost
# [database to create] - default mot (can be overridden by MOT_DATABASE env variable)
# [user to be granted db access] - default motdbuser

set -o errexit
MyUSER=${1-"root"}
MyPASS=${2-"root"}
MyHOST=${3-"localhost"}
MyDATABASE=${4-${MOT_DATABASE-"mot"}}
MyGRANTUSER=${5-"motdbuser"}

echo "$(date) Creating database $MyDATABASE on host $MyHOST for user $MyGRANTUSER"

mysql -u $MyUSER -p$MyPASS -h $MyHOST -Bse 'CREATE DATABASE `'$MyDATABASE'` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT COLLATE utf8_general_ci;'
mysql -u $MyUSER -p$MyPASS -h $MyHOST $MyDATABASE -Bse 'GRANT CREATE TEMPORARY TABLES, SELECT, UPDATE, INSERT, DELETE, EXECUTE ON `'$MyDATABASE'`.* TO `'$MyGRANTUSER'`'

echo "$(date) Loading schema"
mysql -u $MyUSER -p$MyPASS -h $MyHOST $MyDATABASE < ./dev/schema/create_dev_db_schema.sql
