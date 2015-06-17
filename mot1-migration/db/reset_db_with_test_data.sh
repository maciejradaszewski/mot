#!/bin/bash
set -o errexit
MyUSER=${1-"root"}
MyPASS=${2-"root"}
MyHOST=${3-"localhost"}

if [[ -e `which mysql` ]]; then
    ./drop_db.sh $MyUSER $MyPASS $MyHOST
    ./create_db_with_test_data.sh $MyUSER $MyPASS $MyHOST
fi

# For fitnesse tests
printf 'true'
