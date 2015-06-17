#!/bin/bash
#
# This script dumps current database to a number of files. Please see dump_db.php for more details about tables that will be dumped.
# Does not run any test - please run all of them before merge. Does not create a new branch.
#
# Params:
# [user to execute as] -default root
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

cd $dev_workspace

grunt db:reset-no-hist
grunt db:dump

cd $dev_workspace/mot-api/db/dev/update/9999-current
rm *
echo "-- placeholder file to ensure git remembers this directory" > empty.sql

grunt db:reset