-- VM-9765
-- For one time password generation the MOT2 schema requires the following alignment with SDM.
-- These columns are populated by the data migration based on the rules defined in MDM-207
-- and are used to as part of the criteria determine if a person will receive a user account, password on MOT2.

ALTER TABLE `person`
  ADD COLUMN `is_deceased` TINYINT(1) unsigned NOT NULL DEFAULT '0' AFTER `first_live_test_done_on`,
  ADD COLUMN `deceased_on` datetime(6) DEFAULT NULL AFTER `is_deceased`;