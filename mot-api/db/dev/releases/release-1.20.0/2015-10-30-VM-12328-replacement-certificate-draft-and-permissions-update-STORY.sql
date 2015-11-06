-- update replacement_certificate_draft
ALTER TABLE `replacement_certificate_draft`
ADD COLUMN `include_in_mismatch_file` TINYINT DEFAULT 0 AFTER `is_vin_vrm_expiry_changed`,
ADD COLUMN `include_in_passes_file` TINYINT DEFAULT 0 AFTER `include_in_mismatch_file`;

-- update history table
ALTER TABLE `replacement_certificate_draft_hist`
ADD COLUMN `include_in_mismatch_file` TINYINT DEFAULT 0 AFTER `is_vin_vrm_expiry_changed`,
ADD COLUMN `include_in_passes_file` TINYINT DEFAULT 0 AFTER `include_in_mismatch_file`;

-- update triggers
DROP TRIGGER IF EXISTS `tr_replacement_certificate_draft_au`;

CREATE TRIGGER `tr_replacement_certificate_draft_au` AFTER UPDATE
ON `replacement_certificate_draft` FOR EACH ROW
  INSERT INTO  `replacement_certificate_draft_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
                                                     `mot_test_id`,
                                                     `mot_test_version`,
                                                     `odometer_reading_id`,
                                                     `vrm`,
                                                     `empty_vrm_reason_id`,
                                                     `vin`,
                                                     `empty_vin_reason_id`,
                                                     `vehicle_testing_station_id`,
                                                     `make_id`,
                                                     `make_name`,
                                                     `model_id`,
                                                     `model_name`,
                                                     `primary_colour_id`,
                                                     `secondary_colour_id`,
                                                     `country_of_registration_id`,
                                                     `expiry_date`,
                                                     `different_tester_reason_id`,
                                                     `replacement_reason`,
                                                     `is_vin_vrm_expiry_changed`,
                                                     `include_in_mismatch_file`,
                                                     `include_in_passes_file`,
                                                     `mot1_legacy_id`,
                                                     `created_by`,
                                                     `created_on`,
                                                     `last_updated_by`,
                                                     `last_updated_on`,
                                                     `version`,
                                                     `batch_number`,
                                                     `is_deleted`)
  VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
          OLD.`mot_test_id`,
          OLD.`mot_test_version`,
          OLD.`odometer_reading_id`,
          OLD.`vrm`,
          OLD.`empty_vrm_reason_id`,
          OLD.`vin`,
          OLD.`empty_vin_reason_id`,
          OLD.`vehicle_testing_station_id`,
          OLD.`make_id`,
          OLD.`make_name`,
          OLD.`model_id`,
          OLD.`model_name`,
          OLD.`primary_colour_id`,
          OLD.`secondary_colour_id`,
          OLD.`country_of_registration_id`,
          OLD.`expiry_date`,
          OLD.`different_tester_reason_id`,
          OLD.`replacement_reason`,
          OLD.`is_vin_vrm_expiry_changed`,
          OLD.`include_in_mismatch_file`,
          OLD.`include_in_passes_file`,
          OLD.`mot1_legacy_id`,
          OLD.`created_by`,
          OLD.`created_on`,
          OLD.`last_updated_by`,
          OLD.`last_updated_on`,
          OLD.`version`,
          OLD.`batch_number`,
          OLD.`is_deleted`);

DROP TRIGGER IF EXISTS `tr_replacement_certificate_draft_ad`;

CREATE TRIGGER `tr_replacement_certificate_draft_ad` AFTER DELETE
ON `replacement_certificate_draft` FOR EACH ROW
  INSERT INTO  `replacement_certificate_draft_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
                                                     `mot_test_id`,
                                                     `mot_test_version`,
                                                     `odometer_reading_id`,
                                                     `vrm`,
                                                     `empty_vrm_reason_id`,
                                                     `vin`,
                                                     `empty_vin_reason_id`,
                                                     `vehicle_testing_station_id`,
                                                     `make_id`,
                                                     `make_name`,
                                                     `model_id`,
                                                     `model_name`,
                                                     `primary_colour_id`,
                                                     `secondary_colour_id`,
                                                     `country_of_registration_id`,
                                                     `expiry_date`,
                                                     `different_tester_reason_id`,
                                                     `replacement_reason`,
                                                     `is_vin_vrm_expiry_changed`,
                                                     `include_in_mismatch_file`,
                                                     `include_in_passes_file`,
                                                     `mot1_legacy_id`,
                                                     `created_by`,
                                                     `created_on`,
                                                     `last_updated_by`,
                                                     `last_updated_on`,
                                                     `version`,
                                                     `batch_number`,
                                                     `is_deleted`)
  VALUES ('D',  COALESCE(@BATCH_NUMBER,0), OLD.`id`,
          OLD.`mot_test_id`,
          OLD.`mot_test_version`,
          OLD.`odometer_reading_id`,
          OLD.`vrm`,
          OLD.`empty_vrm_reason_id`,
          OLD.`vin`,
          OLD.`empty_vin_reason_id`,
          OLD.`vehicle_testing_station_id`,
          OLD.`make_id`,
          OLD.`make_name`,
          OLD.`model_id`,
          OLD.`model_name`,
          OLD.`primary_colour_id`,
          OLD.`secondary_colour_id`,
          OLD.`country_of_registration_id`,
          OLD.`expiry_date`,
          OLD.`different_tester_reason_id`,
          OLD.`replacement_reason`,
          OLD.`is_vin_vrm_expiry_changed`,
          OLD.`include_in_mismatch_file`,
          OLD.`include_in_passes_file`,
          OLD.`mot1_legacy_id`,
          OLD.`created_by`,
          OLD.`created_on`,
          OLD.`last_updated_by`,
          OLD.`last_updated_on`,
          OLD.`version`,
          OLD.`batch_number`,
          OLD.`is_deleted`);



SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

-- change CERTIFICATE-CERTIFICATE-NO-MISMATCH-VIN-VRM-CHANGE to CERTIFICATE-REPLACEMENT-DVLA-CHANGE
UPDATE `permission`
SET `name` = 'Do not create a mismatch for DVLA user changes',
  `code` = 'CERTIFICATE-REPLACEMENT-DVLA-CHANGE',
  `last_updated_by` = @app_user_id,
  `last_updated_on` = CURRENT_TIMESTAMP(6)
WHERE `code` = 'CERTIFICATE-REPLACEMENT-NO-MISMATCH-VIN-VRN-CHANGE';


-- update DVLA users with new permission
SET @dvla_change_permission = (SELECT `id` FROM `permission` WHERE `code` = 'CERTIFICATE-REPLACEMENT-DVLA-CHANGE');

INSERT INTO `role_permission_map`(`role_id`, `permission_id`, `created_by`, `created_on`) VALUES
  (
    (SELECT `id` FROM `role` WHERE `name` = 'DVLA Import'),
    @dvla_change_permission,
    @app_user_id,
    CURRENT_TIMESTAMP(6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `name` = 'DVLA Manager'),
    @dvla_change_permission,
    @app_user_id,
    CURRENT_TIMESTAMP(6)
  );
