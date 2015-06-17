#!/bin/bash
set -o errexit
MyUSER=${1-"root"}
MyPASS=${2-"root"}
MyHOST=${3-"localhost"}

./create_db.sh $MyUSER $MyPASS $MyHOST

# Add the constant type of data
#mysql -u $MyUSER -p$MyPASS -h $MyHOST MOT1 < ./test_data/test_data_static.sql
