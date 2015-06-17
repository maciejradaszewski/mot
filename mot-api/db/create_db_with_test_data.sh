#!/bin/bash
#
# Creates database (schema and tables structure), populates tables with static and test data. Runs all update scripts
#
# Params:
# [user to execute as] - default root
# [password] - default root
# [host] - default localhost
# [database to create] - default mot (can be overridden by MOT_DATABASE env variable)
# [user to be granted db access] - default motdbuser
# [full sample data set] - default N. Y = Load full sample data set

set -o errexit
MyUSER=${1-"root"}
MyPASS=${2-"password"}
MyHOST=${3-"localhost"}
MyDATABASE=${4-${MOT_DATABASE-"mot"}}
MyGRANTUSER=${5-"motdbuser"}
ImportFullVtsDataSet=${6-"Y"}

echo "$(date) Create database $MyDATABASE with test data"

./create_db.sh $MyUSER $MyPASS $MyHOST $MyDATABASE $MyGRANTUSER

cat ./dev/bin/template/sql-header.tsql ./dev/populate/static-data/*.sql ./dev/populate/static-data-mot2/*.sql ./dev/populate/test-data/*.sql ./dev/bin/template/sql-footer.tsql > ./populate_db.sql_temp

echo $(date) Loading ./populate_db.sql_temp
mysql -u $MyUSER -p$MyPASS -h $MyHOST $MyDATABASE < ./populate_db.sql_temp


# Run the update scripts
for UPDATE_DIR in ./dev/update/*; do
    for UPDATE in $UPDATE_DIR/*.sql; do
        echo "$(date) Loading schema or data update $UPDATE"
        mysql -u $MyUSER -p$MyPASS -h $MyHOST $MyDATABASE < $UPDATE
    done
done

if [ "$ImportFullVtsDataSet" = "Y" ]; then

    echo "$(date) Loading extra VTS dataset"
    # Add test data for vts details
    mysql -u $MyUSER -p$MyPASS -h $MyHOST $MyDATABASE  < ./dev/populate/sample-data/test_data_vts_details.sql

fi