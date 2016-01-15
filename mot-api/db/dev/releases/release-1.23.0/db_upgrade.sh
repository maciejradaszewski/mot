upgradefiles=()
mysqladmin_password=PASSWORDGOESHERE
version=1.23.0
db_name=mot_v195_rel4

# add files here in intended run order for release
upgradefiles+=('2015-12-07-BL-416-content-details-site-related-permissions-STORY.sql');
upgradefiles+=('2015-12-07-BL-414-site-permissions-related-STORY.sql');
upgradefiles+=('2015-12-07-BL-415-site-details-granular-permissions-STORY.sql');
upgradefiles+=('2015-12-07-BL-418-permission-for-VTS-overview-permissions-STORY.sql');
upgradefiles+=('2015-12-10-BL-304-non-financial-manual-adjustments-payment-type-STORY.sql');
upgradefiles+=('2015-12-10-BL-304-non-financial-manual-adjustments-STORY.sql');
upgradefiles+=('2015-12-29-BL-236-vts-and-dvsa-roles-unable-to-acknowledge-special-notices-STORY.sql');
upgradefiles+=('2016-01-04-BL-448-new-person-profile-permissions-STORY.sql');
upgradefiles+=('2016-01-14-BL-795-dvla-vehicle-triggers-STORY.sql');

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
