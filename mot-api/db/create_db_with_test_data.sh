#!/bin/bash
#
# Creates database (schema and tables structure), populates tables with static and test data. Runs all update scripts
#
# Params:
# [user to execute as] - default root
# [password] - default root
# [host] - default localhost
# [user to be granted db access] - default motdbuser

set -o errexit
MyUSER=${1-"root"}
MyPASS=${2-"password"}
MyHOST=${3-"localhost"}
MyGRANTUSER=${4-"motdbuser"}

echo "$(date) Create database mot2 with test data"

./create_db.sh $MyUSER $MyPASS $MyHOST $MyGRANTUSER

# generate and load synthetic test data
cat ./dev/populate/template/sql-header.tsql ./dev/populate/test-data/*.sql ./dev/populate/template/sql-footer.tsql > ./populate_db.sql_temp
echo $(date) Loading test data into mot2 on $MyHOST as $MyUSER
mysql -u $MyUSER -p$MyPASS -h $MyHOST -D mot2 < ./populate_db.sql_temp
rm ./populate_db.sql_temp

echo $(date) Loading static data
mysql -u $MyUSER -p$MyPASS -h $MyHOST -D mot2 -f < ./dev/populate/mot2_static_data.sql
echo $(date) Loading anonymised data set
mysql -u $MyUSER -p$MyPASS -h $MyHOST -D mot2 -f < ./dev/populate/mot2_data_10k.sql

# load subsequent DB updates from the releases folder
cd dev/releases/
./apply_updates.sh $MyUSER $MyPASS $MyHOST
