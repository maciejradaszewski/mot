MyUSER=${1-"root"}
MyPASS=${2-"root"}
./create_test_db.sh $MyUSER $MyPASS

# Add the constant type of data
echo "LOAD DATA LOCAL INFILE './anon_data/failure_location.txt' INTO TABLE FAILURE_LOCATION FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';"|mysql -u $MyUSER -p$MyPASS mot_test
echo "LOAD DATA LOCAL INFILE './anon_data/item_detail.txt' INTO TABLE TESTITEM_DETAIL FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';"|mysql -u $MyUSER -p$MyPASS  mot_test
echo "LOAD DATA LOCAL INFILE './anon_data/item_group.txt' INTO TABLE TESTITEM_GROUP FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';"|mysql -u $MyUSER -p$MyPASS mot_test 
echo "LOAD DATA LOCAL INFILE './anon_data/mdr_fuel_types.txt' INTO TABLE FUEL_TYPE FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';"|mysql -u $MyUSER -p$MyPASS mot_test 
#echo "LOAD DATA LOCAL INFILE './anon_data/mdr_test_item_detail.txt' INTO TABLE  FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';"|mysql -u $MyUSER -p$MyPASS mot_test
#echo "LOAD DATA LOCAL INFILE './anon_data/mdr_test_item_group.txt' INTO TABLE  FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';"|mysql -u $MyUSER -p$MyPASS mot_test
echo "LOAD DATA LOCAL INFILE './anon_data/mdr_test_item_marker.txt' INTO TABLE TESTITEM_MARKER FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';"|mysql -u $MyUSER -p$MyPASS mot_test
echo "LOAD DATA LOCAL INFILE './anon_data/mdr_test_outcome.txt' INTO TABLE TEST_OUTCOME FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';"|mysql -u $MyUSER -p$MyPASS mot_test
echo "LOAD DATA LOCAL INFILE './anon_data/mdr_test_types.txt' INTO TABLE TEST_TYPE FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';"|mysql -u $MyUSER -p$MyPASS mot_test

# MOT site list (VTS sites)
echo "LOAD DATA LOCAL INFILE './anon_data/MOTsitelist.csv' INTO TABLE MOT_VTS_SITE FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 LINES;"|mysql -u $MyUSER -p$MyPASS mot_test

# RANDOM USERNAMES
echo "LOAD DATA LOCAL INFILE './anon_data/unique-names-numbered.csv' INTO TABLE USERS FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n';"|mysql -u $MyUSER -p$MyPASS mot_test
