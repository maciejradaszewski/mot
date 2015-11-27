#!/bin/bash
#
# Recreate databse, repopulate it with test data (static and samples) and runs all updating scripts.
#
# Params:
# [user to execute as] - default root
# [password] - default root
# [host] - default localhost
# [database to create] - default mot (can be overridden by MOT_DATABASE env variable)
# [user to be granted db access] - default motdbuser
# [full sample data set] - default N. Y = Load full sample data set
# [create history tables and triggers] - default Y = create historical tables and triggers

set -o errexit

FORCE_RESET=0
THAW_RESULT=1
while getopts "f" opt; do
  case $opt in
    f)
      FORCE_RESET=1
      ;;
  esac
done

shift $((OPTIND-1))

MyUSER=${1-"root"}
MyPASS=${2-"password"}
MyHOST=${3-"localhost"}
MyDATABASE=${4-${MOT_DATABASE-"mot"}}
MyGRANTUSER=${5-"motdbuser"}
ImportFullVtsDataSet=${6-"N"}

echo "$(date) Reset database $MyDATABASE with test data"

if [ $FORCE_RESET -eq 0 ]; then
    set +o errexit
    ./db_thaw.sh $MyUSER $MyPASS $MyHOST $MyDATABASE
    THAW_RESULT=$?
    set -o errexit
fi

if [ ! $THAW_RESULT -eq 0 ] || [ $FORCE_RESET -eq 1 ]; then
    ./drop_db.sh $MyUSER $MyPASS $MyHOST $MyDATABASE $MyGRANTUSER $ImportFullVtsDataSet 2> >(grep -v 'Using a password on the command line interface can be insecure')
    ./create_db_with_test_data.sh $MyUSER $MyPASS $MyHOST $MyDATABASE $MyGRANTUSER $ImportFullVtsDataSet  2> >(grep -v 'Using a password on the command line interface can be insecure')
fi

echo "$(date) Done resetting DB"

# Do not run the reset users script on the dev Vagrant environment.
if [ `hostname` != "dev.dev.dvsa" ] ; then
  cd ../../authentication && ./reset_users.sh
  echo "$(date) Done resetting users"
fi
