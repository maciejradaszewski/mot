-- update certificate_replacement
ALTER TABLE `certificate_replacement`
ADD COLUMN `include_in_mismatch_file` TINYINT DEFAULT 0 AFTER `is_vin_vrm_expiry_changed`,
ADD COLUMN `include_in_passes_file` TINYINT DEFAULT 0 AFTER `include_in_mismatch_file`;

-- update history table
ALTER TABLE `certificate_replacement_hist`
ADD COLUMN `include_in_mismatch_file` TINYINT DEFAULT 0 AFTER `is_vin_vrm_expiry_changed`,
ADD COLUMN `include_in_passes_file` TINYINT DEFAULT 0 AFTER `include_in_mismatch_file`;

-- update triggers for certificate_replacement
DROP TRIGGER IF EXISTS `tr_certificate_replacement_au`;

DELIMITER $$
CREATE TRIGGER `tr_certificate_replacement_au` AFTER UPDATE
ON `certificate_replacement` FOR EACH ROW
  BEGIN
INSERT INTO  `certificate_replacement_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
     `mot_test_id`,
     `mot_test_version`,
     `different_tester_reason_id`,
     `document_id`,
     `certificate_type_id`,
     `tester_person_id`,
     `reason`,
     `is_vin_vrm_expiry_changed`,
     `include_in_mismatch_file`,
     `include_in_passes_file`,
     `mot1_legacy_id`,
     `created_by`,
     `created_on`,
     `last_updated_by`,
     `last_updated_on`,
     `version`,
     `batch_number`)
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
            OLD.`mot_test_id`,
            OLD.`mot_test_version`,
            OLD.`different_tester_reason_id`,
            OLD.`document_id`,
            OLD.`certificate_type_id`,
            OLD.`tester_person_id`,
            OLD.`reason`,
            OLD.`is_vin_vrm_expiry_changed`,
            OLD.`include_in_mismatch_file`,
            OLD.`include_in_passes_file`,
            OLD.`mot1_legacy_id`,
            OLD.`created_by`,
            OLD.`created_on`,
            OLD.`last_updated_by`,
            OLD.`last_updated_on`,
            OLD.`version`,
            OLD.`batch_number`);
  END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_certificate_replacement_ad`;

DELIMITER $$
CREATE TRIGGER `tr_certificate_replacement_ad` AFTER DELETE
ON `certificate_replacement` FOR EACH ROW
  BEGIN
INSERT INTO  `certificate_replacement_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
     `mot_test_id`,
     `mot_test_version`,
     `different_tester_reason_id`,
     `document_id`,
     `certificate_type_id`,
     `tester_person_id`,
     `reason`,
     `is_vin_vrm_expiry_changed`,
     `include_in_mismatch_file`,
     `include_in_passes_file`,
     `mot1_legacy_id`,
     `created_by`,
     `created_on`,
     `last_updated_by`,
     `last_updated_on`,
     `version`,
     `batch_number`)
VALUES ('D',  COALESCE(@BATCH_NUMBER,0), OLD.`id`,
            OLD.`mot_test_id`,
            OLD.`mot_test_version`,
            OLD.`different_tester_reason_id`,
            OLD.`document_id`,
            OLD.`certificate_type_id`,
            OLD.`tester_person_id`,
            OLD.`reason`,
            OLD.`is_vin_vrm_expiry_changed`,
            OLD.`include_in_mismatch_file`,
            OLD.`include_in_passes_file`,
            OLD.`mot1_legacy_id`,
            OLD.`created_by`,
            OLD.`created_on`,
            OLD.`last_updated_by`,
            OLD.`last_updated_on`,
            OLD.`version`,
            OLD.`batch_number`);
  END;
$$
DELIMITER ;
