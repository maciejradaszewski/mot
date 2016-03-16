upgradefiles=()
mysqladmin_password=PASSWORDGOESHERE
version=1.28.0
db_name=mot_v195_rel4

# add files here in intended run order for release

upgradefiles+=('2016-03-04-BL-1942-demo-test-permissions-STORY.sql');
upgradefiles+=('2016-03-07-BL-1529-survey-page-for-mot-service.sql');
upgradefiles+=('2016-03-01-BL-1464-users-do-not-receive-notification-when-personal-details-updated.sql');
upgradefiles+=('2016-03-09-BL-772-add-users-with-aedm-and-aed-roles-to-the-list-of-special-notice-recipients-STORY.sql');
upgradefiles+=('2016-02-25-BL-1395-DVLA-Import-Date-Handling-STORY.sql');

# add files here in intended run order for release

for sqlscript in ${upgradefiles[@]}
do
  # run sql script via mysql client, time execution and capture error code
  errorCode=`/usr/bin/time -f "${sqlscript} took %E (%x)" mysql -t -vvv -h mysql -u mysql_admin -p${mysqladmin_password} ${db_name} < ${sqlscript} 2>&1 | tee -a /tmp/dbupgrade.log | tail -1 | awk -F '[()]' '{print $2}'`

  # if not successful, halt
  if [[ $errorCode -ne 0 ]]
  then
    echo "${sqlscript} caused upgrade failure, exiting. See /tmp/dbupgrade.log for details." | tee -a /tmp/dbupgrade.log
    exit 1
  fi

done

# if successful update DB version
mysql -h mysql -u mysql_admin -p${mysqladmin_password} ${db_name} -e "insert into database_version (version_name, applied_on) values ('${version}', current_timestamp());"

echo "DB upgrades applied successfully. DB version table updated with ${version}"
