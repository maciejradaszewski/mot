upgradefiles=()
mysqladmin_password=PASSWORDGOESHERE
version=1.26.0
db_name=mot_v195_rel4

# add files here in intended run order for release

upgradefiles+=('2016-02-08-BL-21-create-new-table-STORY.sql');
upgradefiles+=('2016-02-09-BL-21-assign-permissions-STORY.sql');
upgradefiles+=('2016-02-09-BL-21-new-event-type-STORY.sql');
upgradefiles+=('2016-02-09-BL-21-copy-principals-to-new-table-STORY.sql');
upgradefiles+=('2016-02-02-BL-931-add-edit-telephone-permission-STORY.sql');
upgradefiles+=('2016-02-03-BL-1138-add-vts-status-change-event-type-STORY.sql');
upgradefiles+=('2016-02-03-BL-1140-add-link-status-change-event-type-STORY.sql');
upgradefiles+=('2016-02-03-BL-956-add-role-status-change-event-type-STORY.sql');
upgradefiles+=('2016-02-08-BL-1335-add-tester-active-event-outcome-STORY.sql');
upgradefiles+=('2016-02-01-BL-929-person-profile-edit-address-STORY.sql');

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
