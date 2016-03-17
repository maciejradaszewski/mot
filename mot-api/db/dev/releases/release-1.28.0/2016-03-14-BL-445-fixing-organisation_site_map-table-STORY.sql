##################################################################################################
# BL-445
#
# The application did not populate `start_date`, `end_date` columns in  `organisation_site_map`.
# This script will fix those values the application was supposed to update.
#
##################################################################################################

SET @`app_user_id` =
(
  SELECT `id`
  FROM `person`
  WHERE `username` = 'static data' OR `user_reference` = 'Static Data'
);

SET @`etl_user_id` =
(
  SELECT `id`
  FROM `person`
  WHERE `username` = 'etl user' OR `user_reference` = 'ETL Process'
);


##################################################################################################
# Some end_dates in the application are set to '9999-12-31'
# Nothing lasts forever - we'll change to NULL, which means the link has not yet been closed
##################################################################################################

UPDATE `organisation_site_map`
SET
  `end_date`        = NULL,
  `last_updated_by` = @`app_user_id`
WHERE `end_date` = '9999-12-31';

##################################################################################################
# Now we can fix the start dates.
# We filter updated rows and skip rows created by migration (etl user), that way we will deal
# with data only produced by the application.
# The start_date is easy to figure out, it's the same date the row was created.
##################################################################################################

UPDATE `organisation_site_map`
SET `start_date`    = `created_on`,
  `last_updated_by` = @`app_user_id`
WHERE `created_by` != @`etl_user_id`;


##################################################################################################
# Now we can move on to end dates.
# When you unlink a site the application goes to the current ACTIVE link
# and changes it to SURRENDERED/WITHDRAWN status (whichever user selected in the app).
# At the same time it should populate end_date.
# One thing to note is that the link that is being 'closed' can be a row created by MOT2 app or
# or migrated from MOT1.
# We also need to remember migrated data contains statuses like applied(AP) and unknown(UNKN) -
# both of them mean that the site was not able to perform the tests at that time.
# Also links we do not want to touch are 'active'(AC) ones -
# the link is still valid so there should be no end date present.
#
# MOT2 application, after deactivating a link, does not interact with the row anymore.
# Thus the last_updated_on column tells when the un-linking happened,
# thus we can use it and copy it's contents into `end_date`.
#
# Quite a lot of science was done upon ACPT database to check if this is safe.
# Most notably going through migrated records searching for inactive links that have end_date missing.
# The `end_date` column of such rows was not updated by the app.
# There was no more than one such row per site. Each site that had a row like that had also
# a row via the application.
# This makes it clear that the no `end_date` rows for inactive links were left by the application
# and were not a result of migration.
##################################################################################################

UPDATE `organisation_site_map` `map`
  JOIN `organisation_site_status` `status` ON `status`.`id` = `map`.`status_id`
SET `map`.`end_date`        = `map`.`last_updated_on`,
  `map`.`status_changed_on` = `map`.`last_updated_on`,
  `map`.`last_updated_by`   = @`app_user_id`
WHERE `map`.`end_date` IS NULL
      AND `status`.`code` NOT IN ('AC', 'AP', 'UNKN');


##################################################################################################
# Still `organisation_site_map` contains some problems caused by data from MOT1:
# - Rows with inverted `start_date` and `end_date`.
# - Some links overlap in time - which makes it seem that the VTS was linked to two AEs at the same time.
#   This causes the application to view an MOT test in reports for two different AEs.
##################################################################################################
