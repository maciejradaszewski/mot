#!/bin/bash
#
# Drops database
#
# Params:
# [user to execute as] - default root
# [password] - default root
# [host] - default localhost
# [database to create] - default mot (can be overridden by MOT_DATABASE env variable)

set -o errexit
MyUSER=${1-"root"}
MyPASS=${2-"root"}
MyHOST=${3-"localhost"}
MyDATABASE=${4-${MOT_DATABASE-"mot"}}

echo "$(date) Dropping database $MyDATABASE"
mysql -u $MyUSER -p$MyPASS -h $MyHOST -Bse 'DROP DATABASE IF EXISTS `'$MyDATABASE'`;'
