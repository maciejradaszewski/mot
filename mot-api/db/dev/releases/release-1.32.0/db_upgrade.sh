#!/usr/bin/env bash
upgrade_files=()
readonly DB_VERSION=1.32.0
readonly MYSQL_DB_NAME=mot_v195_rel4
readonly MYSQL_HOST=mysql
readonly MYSQL_USER=motdbuser
readonly MYSQL_ADMIN_PASSWORD=password

# Add files here in intended run order for release.
upgrade_files+=('2016-04-26-BL-1692-update-from-dvla-import-job-to-use-java-endpoint-STORY.sql');
upgrade_files+=('2016-04-29-BL-1591-drop-mot-test-refusal-and-corresponding-tables-STORY.sql');
upgrade_files+=('2016-05-06-BL-1592-update-production-with-the-latest-RFR-dataset-STORY.sql');
upgrade_files+=('2016-05-06-BL-2009-fix-passed-mot-tests-with-null-expiry-date-STORY.sql');
upgrade_files+=('2016-05-02-BL-52-amend-list-of-non-component-advisories-and-make-available-in-production-STORY.sql');

# Necessary for first deployment.
$(mysql -h ${MYSQL_HOST} -u ${MYSQL_USER} -p${MYSQL_ADMIN_PASSWORD} ${MYSQL_DB_NAME} -e "CREATE TABLE IF NOT EXISTS db_upgrade (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  script_name VARCHAR(255),
  parent_db_version VARCHAR(255),
  applied_on DATETIME,
  PRIMARY KEY (id)
);")

if [ ${#upgrade_files[@]} -eq 0 ]; then
  echo "No upgrade scripts present, exiting..."
  exit 0
fi

# For each upgrade script, run the script, log any errors
for upgrade_file in ${upgrade_files[@]}; do
  # Get the list of previously applied upgrade scripts.
  previously_run_files=$(mysql -h ${MYSQL_HOST} -u ${MYSQL_USER} -p${MYSQL_ADMIN_PASSWORD} ${MYSQL_DB_NAME} -N -e "SELECT script_name FROM db_upgrade;" \
    | awk '{print $0}')

  # Check if the upgrade script has already been applied.
  file_already_run=0
  for previously_run_file in ${previously_run_files[@]}; do
    if [[ ${previously_run_file} = ${upgrade_file} ]]; then
      file_already_run=1
    fi
  done

  # If script has not already been successfully applied, apply it and log any errors.
  if [[ ${file_already_run} -eq 1 ]]; then
    echo "${upgrade_file} has already been applied, continuing..."
  else
    # Run sql script via mysql client, time execution and capture error code.
    error_code=$(/usr/bin/time -f "${upgrade_file} took %E (%x)" mysql -t -vvv -h ${MYSQL_HOST} -u ${MYSQL_USER} -p${MYSQL_ADMIN_PASSWORD} ${MYSQL_DB_NAME} < ${upgrade_file} 2>&1 \
      | tee -a /tmp/dbupgrade.log \
      | tail -1 \
      | awk -F '[()]' '{print $2}')
  fi

  # If the upgrade script errors out, exit and write to the log. If it has been
  # applied successfully, write its name to the db_upgrade table.
  if [[ ${error_code} -ne 0 ]]; then
    echo "${upgrade_file} caused upgrade failure, exiting. See /tmp/dbupgrade.log for details." >&2 \
      | tee -a /tmp/dbupgrade.log
    exit 1
  elif [[ ${file_already_run} -ne 1 ]]; then
    echo "Successfully applied ${upgrade_file}."
    mysql -h ${MYSQL_HOST} -u ${MYSQL_USER} -p${MYSQL_ADMIN_PASSWORD} ${MYSQL_DB_NAME} -e "INSERT INTO db_upgrade (script_name, parent_db_version, applied_on) VALUES ('${upgrade_file}', '${DB_VERSION}', current_timestamp());"
  fi
done

# Get the current db_version from the database_version table.
current_db_version=$(mysql -h ${MYSQL_HOST} -u ${MYSQL_USER} -p${MYSQL_ADMIN_PASSWORD} ${MYSQL_DB_NAME} -N -e "SELECT version_name FROM database_version ORDER BY id DESC LIMIT 1;" \
  | awk '{print $0}')

# If the version in the database_version table has already been updated, don't
# add a new row. If this is the first time this script has been successfully
# run for this version, insert a new row into the database_version table.
if [[ ${current_db_version} != ${DB_VERSION} ]]; then
  mysql -h ${MYSQL_HOST} -u ${MYSQL_USER} -p${MYSQL_ADMIN_PASSWORD} ${MYSQL_DB_NAME} -e "INSERT INTO database_version (version_name, applied_on) VALUES ('${DB_VERSION}', current_timestamp())"
  echo "DB upgrades applied successfully. DB version table updated with ${DB_VERSION}"
else
  echo "DB upgrades applied successfully. Not updating DB version table."
fi
