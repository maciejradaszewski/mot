#!/bin/bash

MyUSER=${1-"root"}
MyPASS=${2-"root"}

import_data () {
  MyFilename="$1"
  MyTABLE="$2"
  MyDatetime="$(date)"
  echo "$MyDatetime: Starting Import $MyFilename" >>./anon_data/import_data.log
  if [ -a "./anon_data/$MyFilename.gz" ]
   then
    if [ ! -a "./anon_data/$MyFilename.parts.aa" ]
     then
      zcat "./anon_data/$MyFilename.gz" |split -l 1000000 - "./anon_data/$MyFilename.parts."
    fi  
    if [ ! -a "./anon_data/$MyFilename.imp" ]
     then
		ls ./anon_data/$MyFilename.parts.*|xargs -I{} echo "LOAD DATA INFILE '$PWD/{}' IGNORE INTO TABLE $MyTABLE FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';" >> "./anon_data/$MyFilename.sql"
		mysql -u $MyUSER -p$MyPASS mot_test < "./anon_data/$MyFilename.sql"
      touch "./anon_data/$MyFilename.imp"
    fi
    if [ -a ./anon_data/cleanup ]
     then
      rm "./anon_data/$MyFilename"
    fi
    MyDatetime="$(date)"
    echo "$MyDatetime: Imported $MyFilename" >>./anon_data/import_data.log
  fi
  
}

# Add the bulk test of data
MyDatetime="$(date)"
echo "$MyDatetime: Started import of data" >>./anon_data/import_data.log
import_data test_result_2013.txt TESTRESULT
import_data test_item_2013.txt TESTITEM
import_data test_result_2012.txt TESTRESULT
import_data test_item_2012.txt TESTITEM
import_data test_result_2011.txt TESTRESULT
import_data test_item_2011.txt TESTITEM
import_data test_result_2010.txt TESTRESULT
import_data test_item_2010.txt TESTITEM
import_data test_result_2009.txt TESTRESULT
import_data test_item_2009.txt TESTITEM
import_data test_result_2008.txt TESTRESULT
import_data test_item_2008.txt TESTITEM
import_data test_result_2007.txt TESTRESULT
import_data test_item_2007.txt TESTITEM
import_data test_result_2006.txt TESTRESULT
import_data test_item_2006.txt TESTITEM
import_data test_result_2005.txt TESTRESULT
import_data test_item_2005.txt TESTITEM
MyDatetime="$(date)"
echo "$MyDatetime: Completed import of data" >>./anon_data/import_data.log

# Add the constant type of data
echo "$MyDatetime: Start import of static data" >>./anon_data/import_data.log
echo "$MyDatetime: Importing FAILURE_LOCATION" >>./anon_data/import_data.log
echo "LOAD DATA LOCAL INFILE '$PWD/anon_data/failure_location.txt' INTO TABLE FAILURE_LOCATION FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';"|mysql -u $MyUSER -p$MyPASS mot_test
MyDatetime="$(date)"
echo "$MyDatetime: Importing TESTITEM_DETAIL" >>./anon_data/import_data.log
echo "LOAD DATA LOCAL INFILE '$PWD/anon_data/item_detail.txt' INTO TABLE TESTITEM_DETAIL FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';"|mysql -u $MyUSER -p$MyPASS  mot_test
MyDatetime="$(date)"
echo "$MyDatetime: Importing TESTITEM_GROUP" >>./anon_data/import_data.log
echo "LOAD DATA LOCAL INFILE '$PWD/anon_data/item_group.txt' INTO TABLE TESTITEM_GROUP FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';"|mysql -u $MyUSER -p$MyPASS mot_test 

if [ -a "./anon_data/mdr_test_lookup_tables.zip" ]
 then
  if [ -a ./anon_data/mdr_fuel_types.txt ]
   then
    MyDatetime="$(date)"
    echo "$MyDatetime: Decompressing mdr_test_lookup_tables.zip" >>./anon_data/import_data.log
    cd ./anon_data/
    unzip mdr_test_lookup_tables.zip
    cd ..
    MyDatetime="$(date)"
    echo "$MyDatetime: Decompressed mdr_test_lookup_tables.zip" >>./anon_data/import_data.log
  fi

  MyDatetime="$(date)"
  echo "$MyDatetime: Importing FUEL_TYPE" >>./anon_data/import_data.log
  echo "LOAD DATA LOCAL INFILE '$PWD/anon_data/mdr_fuel_types.txt' INTO TABLE FUEL_TYPE FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';"|mysql -u $MyUSER -p$MyPASS mot_test 
  MyDatetime="$(date)"
  echo "$MyDatetime: Importing TESTITEM_MARKER" >>./anon_data/import_data.log
  echo "LOAD DATA LOCAL INFILE '$PWD/anon_data/mdr_test_item_marker.txt' INTO TABLE TESTITEM_MARKER FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';"|mysql -u $MyUSER -p$MyPASS mot_test
  MyDatetime="$(date)"
  echo "$MyDatetime: Importing TEST_OUTCOME" >>./anon_data/import_data.log
  echo "LOAD DATA LOCAL INFILE '$PWD/anon_data/mdr_test_outcome.txt' INTO TABLE TEST_OUTCOME FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';"|mysql -u $MyUSER -p$MyPASS mot_test
  MyDatetime="$(date)"
  echo "$MyDatetime: Importing TEST_TYPE" >>./anon_data/import_data.log
  echo "LOAD DATA LOCAL INFILE '$PWD/anon_data/mdr_test_types.txt' INTO TABLE TEST_TYPE FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n';"|mysql -u $MyUSER -p$MyPASS mot_test
fi

# MOT site list (VTS sites)
MyDatetime="$(date)"
echo "$MyDatetime: Importing MOT_VTS_SITE" >>./anon_data/import_data.log
echo "LOAD DATA LOCAL INFILE '$PWD/anon_data/MOTsitelist.csv' INTO TABLE MOT_VTS_SITE FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 LINES;"|mysql -u $MyUSER -p$MyPASS mot_test

# RANDOM USERNAMES
MyDatetime="$(date)"
echo "$MyDatetime: Importing USERS" >>./anon_data/import_data.log
echo "LOAD DATA LOCAL INFILE '$PWD/anon_data/unique-names-numbered.csv' INTO TABLE USERS FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n';"|mysql -u $MyUSER -p$MyPASS mot_test
MyDatetime="$(date)"
echo "$MyDatetime: End import of static data" >>./anon_data/import_data.log





# Extract Make Model data from Test Details
echo "$MyDatetime: Started psudo-random data generation" >>./anon_data/import_data.log
mysql -u $MyUSER -p$MyPASS mot_test < ./anon_data/extract_make_model.sql
MyDatetime="$(date)"
echo "$MyDatetime: Completed psudo-random data generation" >>./anon_data/import_data.log

