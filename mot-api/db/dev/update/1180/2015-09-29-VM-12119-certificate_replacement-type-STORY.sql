SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

ALTER TABLE `certificate_replacement`
  DROP FOREIGN KEY `fk_certificate_replacement_certificate_status_id`,
  DROP KEY `fk_certificate_replacement_certificate_status_id`,
  DROP COLUMN `certificate_status_id`;

CREATE TABLE `certificate_type` (
  `id` BIGINT unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `display_order` smallint(5) unsigned DEFAULT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_modified_by` int(10) unsigned DEFAULT NULL,
  `last_modified_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_certificate_type_mot1_legacy_id` (`mot1_legacy_id`),
  UNIQUE KEY `uk_certificate_type_code` (`code`),
  KEY `ix_certificate_type_created_by_person_id` (`created_by`),
  KEY `ix_certificate_type_last_updated_by_person_id` (`last_modified_by`),
  CONSTRAINT `ix_certificate_type_created_by_person_id` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `ix_certificate_type_last_updated_by_person_id` FOREIGN KEY (`last_modified_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Normalisation of the MOT test type';

-- Create history table for mot.certificate_type
CREATE TABLE `certificate_type_hist` (
  `hist_id` bigint unsigned not null auto_increment,
  `id` bigint(20) unsigned,
  `name` varchar(50),
  `display_order` smallint(5) unsigned,
  `code` varchar(5),
  `mot1_legacy_id` varchar(80),
  `batch_number` int(10) unsigned,
  `created_by` int(10) unsigned not null,
  `created_on` timestamp(6) null default null,
  `last_modified_by` int(10) unsigned not null,
  `last_modified_on` timestamp(6) null default null,
  `version` int(10) unsigned,
  `expired_by` int unsigned,
  `expired_on` timestamp(6) null default null,
  PRIMARY KEY (`hist_id`),
  UNIQUE INDEX uk_certificate_type_hist_id_version (`id`,`version`)) ENGINE=InnoDB;

-- Create before triggers for certificate_type
DROP TRIGGER IF EXISTS `tr_certificate_type_bi`;

DELIMITER $$
CREATE TRIGGER `tr_certificate_type_bi` BEFORE INSERT
ON `certificate_type` FOR EACH ROW
  BEGIN
    SET NEW.`version` = 1, NEW.`created_by` = @app_user_id, NEW.`last_modified_by` = @app_user_id;
  END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_certificate_type_bu`;

DELIMITER $$
CREATE TRIGGER `tr_certificate_type_bu` BEFORE UPDATE
ON `certificate_type` FOR EACH ROW
  BEGIN
    SET NEW.`version` = OLD.`version` + 1, NEW.`last_modified_by` = @app_user_id;
  END;
$$
DELIMITER ;

-- Create after triggers for certificate_type
DROP TRIGGER IF EXISTS `tr_certificate_type_ai`;

DELIMITER $$
CREATE TRIGGER `tr_certificate_type_ai` AFTER INSERT
ON `certificate_type` FOR EACH ROW

  BEGIN
    INSERT INTO `certificate_type_hist`
    (`id`,
     `name`,
     `display_order`,
     `code`,
     `mot1_legacy_id`,
     `created_by`,
     `created_on`,
     `last_modified_by`,
     `last_modified_on`,
     `version`,
     `batch_number`)
    VALUES (NEW.`id`,
            NEW.`name`,
            NEW.`display_order`,
            NEW.`code`,
            NEW.`mot1_legacy_id`,
            NEW.`created_by`,
            NEW.`created_on`,
            NEW.`last_modified_by`,
            NEW.`last_modified_on`,
            NEW.`version`,
            NEW.`batch_number`);
  END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_certificate_type_au`;

DELIMITER $$
CREATE TRIGGER `tr_certificate_type_au` AFTER UPDATE
ON `certificate_type` FOR EACH ROW

  BEGIN
    UPDATE `certificate_type_hist`
    SET `expired_by` = @app_user_id,
      `expired_on` = current_timestamp(6)
    WHERE `id` = OLD.`id` and `expired_on` is null;

    INSERT INTO `certificate_type_hist`
    (`id`,
     `name`,
     `display_order`,
     `code`,
     `mot1_legacy_id`,
     `created_by`,
     `created_on`,
     `last_modified_by`,
     `last_modified_on`,
     `version`,
     `batch_number`)
    VALUES (NEW.`id`,
            NEW.`name`,
            NEW.`display_order`,
            NEW.`code`,
            NEW.`mot1_legacy_id`,
            NEW.`created_by`,
            NEW.`created_on`,
            NEW.`last_modified_by`,
            NEW.`last_modified_on`,
            NEW.`version`,
            NEW.`batch_number`);
  END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_certificate_type_ad`;

DELIMITER $$
CREATE TRIGGER `tr_certificate_type_ad` AFTER DELETE
ON `certificate_type` FOR EACH ROW

  BEGIN
    UPDATE `certificate_type_hist`
    SET `expired_by` = @app_user_id,
      `expired_on` = current_timestamp(6)
    WHERE `id` = OLD.`id` and `expired_on` is null;
  END;
$$
DELIMITER ;

-- Update certificate_replacement
ALTER TABLE `certificate_replacement`
  ADD COLUMN `certificate_type_id` BIGINT unsigned DEFAULT NULL AFTER `document_id`,
  # Identifier name 'ix_certificate_replacement_certificate_type_id_certificate_type_id' was too long
  ADD CONSTRAINT `ix_certificate_replacement_certificate_type_id` FOREIGN KEY (`certificate_type_id`) REFERENCES `certificate_type` (`id`),
  CHANGE COLUMN `is_vin_registration_changed` `is_vin_vrm_expiry_changed` TINYINT UNSIGNED NOT NULL DEFAULT '0';

-- Update history table for mot.certificate_replacement
ALTER TABLE `certificate_replacement_hist`
  ADD COLUMN `certificate_type_id` bigint(20) unsigned AFTER `document_id`,
  CHANGE COLUMN `is_vin_registration_changed` `is_vin_vrm_expiry_changed` tinyint(3) unsigned AFTER `reason`;

-- Create after triggers for certificate_replacement
DROP TRIGGER IF EXISTS `tr_certificate_replacement_ai`;

DELIMITER $$
CREATE TRIGGER `tr_certificate_replacement_ai` AFTER INSERT
ON `certificate_replacement` FOR EACH ROW
  BEGIN
    INSERT INTO  `certificate_replacement_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
    VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

  END;
$$
DELIMITER ;

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

ALTER TABLE `replacement_certificate_draft`
  CHANGE COLUMN `is_vin_registration_changed` `is_vin_vrm_expiry_changed` TINYINT UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `replacement_certificate_draft_hist`
  CHANGE COLUMN `is_vin_registration_changed` `is_vin_vrm_expiry_changed` TINYINT UNSIGNED ;

-- Create triggers for mot.replacement_certificate_draft
DROP TRIGGER IF EXISTS `tr_replacement_certificate_draft_ai`;

CREATE TRIGGER `tr_replacement_certificate_draft_ai` AFTER INSERT
ON `replacement_certificate_draft` FOR EACH ROW
INSERT INTO  `replacement_certificate_draft_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

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
          OLD.`mot1_legacy_id`,
          OLD.`created_by`,
          OLD.`created_on`,
          OLD.`last_updated_by`,
          OLD.`last_updated_on`,
          OLD.`version`,
          OLD.`batch_number`,
          OLD.`is_deleted`);

START TRANSACTION;

INSERT INTO `certificate_type` (`id`, `name`, `display_order`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_modified_by`, `last_modified_on`, `version`, `batch_number`) VALUES (1, 'Test', 1, 'T', 'T', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, NULL, 1, 1);
INSERT INTO `certificate_type` (`id`, `name`, `display_order`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_modified_by`, `last_modified_on`, `version`, `batch_number`) VALUES (2, 'Replace', 2, 'R', 'R', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, NULL, 1, 1);
INSERT INTO `certificate_type` (`id`, `name`, `display_order`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_modified_by`, `last_modified_on`, `version`, `batch_number`) VALUES (3, 'Duplicate', 3, 'D', 'D', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, NULL, 1, 1);
INSERT INTO `certificate_type` (`id`, `name`, `display_order`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_modified_by`, `last_modified_on`, `version`, `batch_number`) VALUES (4, 'Exchange', 4, 'E', 'E', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, NULL, 1, 1);
INSERT INTO `certificate_type` (`id`, `name`, `display_order`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_modified_by`, `last_modified_on`, `version`, `batch_number`) VALUES (5, 'Transfer', 5, 'F', 'F', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, NULL, 1, 1);
INSERT INTO `certificate_type` (`id`, `name`, `display_order`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_modified_by`, `last_modified_on`, `version`, `batch_number`) VALUES (6, 'Verify', 6, 'V', 'V', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, NULL, 1, 1);

COMMIT;

-- Create history table for mot.certificate_type
# update certificate type for all certificates created by MOT2
# @TODO: What types of certificate can we add in MOT2 Application?
SET @replace_certificate_type = (SELECT `id` FROM `certificate_type` WHERE `code`='R');
SET @transfer_certificate_type = (SELECT `id` FROM `certificate_type` WHERE `code`='F');
UPDATE `certificate_replacement`
SET `certificate_type_id` = IF(reason = 'DVLA Cherished Transfer', @transfer_certificate_type, @replace_certificate_type),
  `last_updated_by` = @app_user_id
WHERE `mot1_legacy_id` IS NULL;

