upgradefiles=()
upgradefiles+=('2015-10-21-VM-12251-fix-vrms-with-mot-test-in-registration-number-STORY.sql')
# add files here in intended run order for release

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
