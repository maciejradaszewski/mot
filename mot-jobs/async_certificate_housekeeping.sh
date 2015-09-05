#!/bin/bash
#
# Carries out housekeeping of mot_test_recent_certificate. Removing rows which predate a certain time
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
MyMAXDAY=${5-5}
MyCHUNKSIZE=${6-1000}

echo "$(date) Removing records from  mot_test_recent_certificate on $MyDATABASE on host $MyHOST for user $MyGRANTUSER " \
     "which are over $MyMAXDAY days old in chunks of $MyCHUNKSIZE"

mysql -u $MyUSER -p$MyPASS -h $MyHOST $MyDATABASE -e "CALL sp_housekeeping_mot_test_recent_certificate($MyMAXDAY, $MyCHUNKSIZE);"
