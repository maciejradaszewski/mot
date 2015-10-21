upgradefiles=()

# add files here in intended run order for release
upgradefiles+=('2015-10-02-VM-12185-add-expiry-password-notifications-STORY.sql')
upgradefiles+=('2015-10-08-VM-2880-create-new-table-STORY.sql')
upgradefiles+=('2015-09-12-11841-demo-test-perform-missing-rfr-list-permission-STORY.sql')
upgradefiles+=('2015-10-12-VM-11903-add-DVLA-manager-user-STORY.sql')
upgradefiles+=('2015-10-16-VM-12209-buy-slots-when-negative-balance-STORY.sql')

for sqlscript in ${upgradefiles[@]}
do
  # run sql script via mysql client, time execution and capture error code
  errorCode=`/usr/bin/time -f "${sqlscript} took %E (%x)" mysql -t -vvv -h mysql -u mysql_admin -pPASSWORDGOESHERE mot_v195_rel4 < ${sqlscript} 2>&1 | tee -a /tmp/dbupgrade.log | tail -1 | awk -F '[()]' '{print $2}'`
  
  # if not successful, halt
  if [[ $errorCode -ne 0 ]]
  then
    echo "${sqlscript} caused upgrade failure, exiting" >> dbupgrade.log
    exit 1
  fi

done
