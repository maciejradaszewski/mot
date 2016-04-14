upgradefiles=()
mysqladmin_password=PASSWORDGOESHERE
version=1.30.0
db_name=mot_v195_rel4

# add files here in intended run order for release

upgradefiles+=('2016-03-10-BL-1568-view-qualification-details-page-permissions-STORY.sql');
upgradefiles+=('2016-03-21-BL-1236-create-new-table-STORY.sql');
upgradefiles+=('2016-03-24-BL-1236-create-new-permissions-for-create-and-update-cert-STORY.sql');
upgradefiles+=('2016-04-05-BL-1708-admin-view-of-users-in-demo-test-needed-STORY.sql');
upgradefiles+=('2016-04-06-BL-1716-create-new-event-type-STORY.sql');
upgradefiles+=('2016-04-06-BL-1716-create-new-notification-template-type-STORY.sql');
upgradefiles+=('2016-04-06-BL-1716-create-new-permissions-for-removing-cert-STORY.sql');

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
