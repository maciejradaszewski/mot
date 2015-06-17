#!/bin/bash
# Convenience script to dump all the existing test data into a giant file for comparison purposes.
# All timestamps are set to 00:00:00 to ease comparison.
# Needs to be run on the vagrant VM.
set -o errexit
set -o nounset
BRANCH=$(git branch | awk '/\*/ { print $2; }' | sed 's+/+_+g')
DUMP_FILENAME=/workspace/data_$BRANCH.sql
./reset_db_with_test_data.sh root password
mysqldump --user root -ppassword --complete-insert mot  | sed -e 's/),(/),\
('/g | perl -p -i -e 's/\d\d:\d\d:\d\d(\.\d{6})*/00:00:00/g' >  $DUMP_FILENAME;
echo DB dumped to $DUMP_FILENAME
