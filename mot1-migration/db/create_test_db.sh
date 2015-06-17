MyUSER=${1-"root"}
MyPASS=${2-"root"}
mysql -u $MyUSER -p$MyPASS < ./schema/create_test_db.sql