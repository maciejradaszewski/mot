SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' || `user_reference` = 'Static Data');

-- Create table mot.person_2fa_lookup
CREATE TABLE `person_auth_type_lookup` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `code` VARCHAR(5) NOT NULL,
  `created_by` INT UNSIGNED NULL DEFAULT NULL,
  `created_on` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_modified_by` INT UNSIGNED NULL DEFAULT NULL,
  `last_modified_on` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  `version` INT UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_person_auth_type_lookup_code` (`code`),
  KEY `ix_person_auth_type_lookup_created_by` (`created_by`),
  KEY `ix_person_auth_type_lookup_last_modified_by` (`last_modified_by`),
  CONSTRAINT `fk_person_auth_type_lookup_created_by_person_id` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_person_auth_type_lookup_last_modified_by_person_id` FOREIGN KEY (`last_modified_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB COMMENT='Lookup table for the 2FA method for a person';

-- Create history table for mot.person_auth_type_lookup
CREATE TABLE `person_auth_type_lookup_hist` (
`hist_id` bigint unsigned not null auto_increment,
`id` bigint(20) unsigned,
`name` varchar(50),
`code` varchar(5),
`created_by` int(10) unsigned not null,
`created_on` timestamp(6) null default null,
`last_modified_by` int(10) unsigned not null,
`last_modified_on` timestamp(6) null default null,
`version` int(10) unsigned,
`expired_by` int unsigned,
`expired_on` timestamp(6) null default null,
  PRIMARY KEY (`hist_id`),
  UNIQUE INDEX uk_person_auth_type_lookup_hist_id_version (`id`,`version`)) ENGINE=InnoDB;

-- Create before triggers for person_auth_type_lookup
DROP TRIGGER IF EXISTS `tr_person_auth_type_lookup_bi`;

DELIMITER $$
CREATE TRIGGER `tr_person_auth_type_lookup_bi` BEFORE INSERT
ON `person_auth_type_lookup` FOR EACH ROW
BEGIN
  SET NEW.`version` = 1, NEW.`created_by` = @app_user_id, NEW.`last_modified_by` = @app_user_id;
END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_person_auth_type_lookup_bu`;

DELIMITER $$
CREATE TRIGGER `tr_person_auth_type_lookup_bu` BEFORE UPDATE
ON `person_auth_type_lookup` FOR EACH ROW
BEGIN
  SET NEW.`version` = OLD.`version` + 1, NEW.`last_modified_by` = @app_user_id;
END;
$$
DELIMITER ;

-- Create after triggers for person_auth_type_lookup
DROP TRIGGER IF EXISTS `tr_person_auth_type_lookup_ai`;

DELIMITER $$
CREATE TRIGGER `tr_person_auth_type_lookup_ai` AFTER INSERT
ON `person_auth_type_lookup` FOR EACH ROW

BEGIN
INSERT INTO `person_auth_type_lookup_hist`
(`id`,
`name`,
`code`,
`created_by`,
`created_on`,
`last_modified_by`,
`last_modified_on`,
`version`)
VALUES (NEW.`id`,
NEW.`name`,
NEW.`code`,
NEW.`created_by`,
NEW.`created_on`,
NEW.`last_modified_by`,
NEW.`last_modified_on`,
NEW.`version`);
END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_person_auth_type_lookup_au`;

DELIMITER $$
CREATE TRIGGER `tr_person_auth_type_lookup_au` AFTER UPDATE
ON `person_auth_type_lookup` FOR EACH ROW

BEGIN
UPDATE `person_auth_type_lookup_hist`
SET `expired_by` = @app_user_id,
`expired_on` = current_timestamp(6)
WHERE `id` = OLD.`id` and `expired_on` is null;

INSERT INTO `person_auth_type_lookup_hist`
(`id`,
`name`,
`code`,
`created_by`,
`created_on`,
`last_modified_by`,
`last_modified_on`,
`version`)
VALUES (NEW.`id`,
NEW.`name`,
NEW.`code`,
NEW.`created_by`,
NEW.`created_on`,
NEW.`last_modified_by`,
NEW.`last_modified_on`,
NEW.`version`);
END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_person_auth_type_lookup_ad`;

DELIMITER $$
CREATE TRIGGER `tr_person_auth_type_lookup_ad` AFTER DELETE
ON `person_auth_type_lookup` FOR EACH ROW

BEGIN
  UPDATE `person_auth_type_lookup_hist`
  SET `expired_by` = @app_user_id,
  `expired_on` = current_timestamp(6)
  WHERE `id` = OLD.`id` and `expired_on` is null;
END;
$$
DELIMITER ;

-- Update mot.person with foreign key to person_2fa_lookup
ALTER TABLE `person`
ADD COLUMN `person_auth_type_lookup_id` BIGINT UNSIGNED NULL DEFAULT NULL AFTER `pin`,
ADD INDEX `ix_person_auth_type_lookup_id` (`person_auth_type_lookup_id` ASC),
ADD CONSTRAINT `fk_person_auth_type_lookup_id` FOREIGN KEY (`person_auth_type_lookup_id`)
REFERENCES `person_auth_type_lookup` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

-- Update history table for mot.person
ALTER TABLE `person_hist`
  ADD COLUMN `person_auth_type_lookup_id` BIGINT UNSIGNED AFTER `pin`;

-- Create history table and update trigger for person
CREATE TABLE  IF NOT EXISTS `person_hist` (
  `hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
  `hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
  `id` int(10) unsigned,
  `username` varchar(50),
  `pin` varchar(60),
  `person_auth_type_lookup_id` bigint(20) unsigned,
  `user_reference` varchar(100),
  `mot_one_user_id` varchar(100),
  `title_id` smallint(5) unsigned,
  `first_name` varchar(45),
  `middle_name` varchar(45),
  `family_name` varchar(45),
  `driving_licence_id` int(10) unsigned,
  `gender_id` smallint(5) unsigned,
  `date_of_birth` date,
  `disability` text,
  `demo_test_tester_status_id` smallint(5) unsigned,
  `otp_failed_attempts` smallint(5) unsigned,
  `is_account_claim_required` tinyint(3) unsigned,
  `is_password_change_required` tinyint(3) unsigned,
  `transition_status_id` smallint(5) unsigned,
  `mot1_userid` varchar(8),
  `mot1_current_smartcard_id` varchar(100),
  `2fa_token_id` varchar(100),
  `2fa_token_sent_on` datetime(6),
  `details_confirmed_on` datetime(6),
  `first_training_test_done_on` datetime(6),
  `first_live_test_done_on` datetime(6),
  `is_deceased` tinyint(1) unsigned,
  `deceased_on` datetime(6),
  `mot1_details_updated_on` datetime(6),
  `mot1_legacy_id` varchar(80),
  `created_by` int(10) unsigned,
  `created_on` datetime(6),
  `last_updated_by` int(10) unsigned,
  `last_updated_on` datetime(6),
  `version` int(10) unsigned,
  `batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_person (`id`,`version`),
  INDEX ix_person_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_person_ai`;
CREATE TRIGGER `tr_person_ai` AFTER INSERT
ON `person` FOR EACH ROW
  INSERT INTO  `person_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
  VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_person_au`;
CREATE TRIGGER `tr_person_au` AFTER UPDATE
ON `person` FOR EACH ROW
  INSERT INTO  `person_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
                              `username`,
                              `pin`,
                              `person_auth_type_lookup_id`,
                              `user_reference`,
                              `mot_one_user_id`,
                              `title_id`,
                              `first_name`,
                              `middle_name`,
                              `family_name`,
                              `driving_licence_id`,
                              `gender_id`,
                              `date_of_birth`,
                              `disability`,
                              `demo_test_tester_status_id`,
                              `otp_failed_attempts`,
                              `is_account_claim_required`,
                              `is_password_change_required`,
                              `transition_status_id`,
                              `mot1_userid`,
                              `mot1_current_smartcard_id`,
                              `2fa_token_id`,
                              `2fa_token_sent_on`,
                              `details_confirmed_on`,
                              `first_training_test_done_on`,
                              `first_live_test_done_on`,
                              `is_deceased`,
                              `deceased_on`,
                              `mot1_details_updated_on`,
                              `mot1_legacy_id`,
                              `created_by`,
                              `created_on`,
                              `last_updated_by`,
                              `last_updated_on`,
                              `version`,
                              `batch_number`)
  VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
          OLD.`username`,
          OLD.`pin`,
          OLD.`person_auth_type_lookup_id`,
          OLD.`user_reference`,
          OLD.`mot_one_user_id`,
          OLD.`title_id`,
          OLD.`first_name`,
          OLD.`middle_name`,
          OLD.`family_name`,
          OLD.`driving_licence_id`,
          OLD.`gender_id`,
          OLD.`date_of_birth`,
          OLD.`disability`,
          OLD.`demo_test_tester_status_id`,
          OLD.`otp_failed_attempts`,
          OLD.`is_account_claim_required`,
          OLD.`is_password_change_required`,
          OLD.`transition_status_id`,
          OLD.`mot1_userid`,
          OLD.`mot1_current_smartcard_id`,
          OLD.`2fa_token_id`,
          OLD.`2fa_token_sent_on`,
          OLD.`details_confirmed_on`,
          OLD.`first_training_test_done_on`,
          OLD.`first_live_test_done_on`,
          OLD.`is_deceased`,
          OLD.`deceased_on`,
          OLD.`mot1_details_updated_on`,
          OLD.`mot1_legacy_id`,
          OLD.`created_by`,
          OLD.`created_on`,
          OLD.`last_updated_by`,
          OLD.`last_updated_on`,
          OLD.`version`,
          OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_person_ad`;
CREATE TRIGGER `tr_person_ad` AFTER DELETE
ON `person` FOR EACH ROW
  INSERT INTO  `person_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
                              `username`,
                              `pin`,
                              `person_auth_type_lookup_id`,
                              `user_reference`,
                              `mot_one_user_id`,
                              `title_id`,
                              `first_name`,
                              `middle_name`,
                              `family_name`,
                              `driving_licence_id`,
                              `gender_id`,
                              `date_of_birth`,
                              `disability`,
                              `demo_test_tester_status_id`,
                              `otp_failed_attempts`,
                              `is_account_claim_required`,
                              `is_password_change_required`,
                              `transition_status_id`,
                              `mot1_userid`,
                              `mot1_current_smartcard_id`,
                              `2fa_token_id`,
                              `2fa_token_sent_on`,
                              `details_confirmed_on`,
                              `first_training_test_done_on`,
                              `first_live_test_done_on`,
                              `is_deceased`,
                              `deceased_on`,
                              `mot1_details_updated_on`,
                              `mot1_legacy_id`,
                              `created_by`,
                              `created_on`,
                              `last_updated_by`,
                              `last_updated_on`,
                              `version`,
                              `batch_number`)
  VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
          OLD.`username`,
          OLD.`pin`,
          OLD.`person_auth_type_lookup_id`,
          OLD.`user_reference`,
          OLD.`mot_one_user_id`,
          OLD.`title_id`,
          OLD.`first_name`,
          OLD.`middle_name`,
          OLD.`family_name`,
          OLD.`driving_licence_id`,
          OLD.`gender_id`,
          OLD.`date_of_birth`,
          OLD.`disability`,
          OLD.`demo_test_tester_status_id`,
          OLD.`otp_failed_attempts`,
          OLD.`is_account_claim_required`,
          OLD.`is_password_change_required`,
          OLD.`transition_status_id`,
          OLD.`mot1_userid`,
          OLD.`mot1_current_smartcard_id`,
          OLD.`2fa_token_id`,
          OLD.`2fa_token_sent_on`,
          OLD.`details_confirmed_on`,
          OLD.`first_training_test_done_on`,
          OLD.`first_live_test_done_on`,
          OLD.`is_deceased`,
          OLD.`deceased_on`,
          OLD.`mot1_details_updated_on`,
          OLD.`mot1_legacy_id`,
          OLD.`created_by`,
          OLD.`created_on`,
          OLD.`last_updated_by`,
          OLD.`last_updated_on`,
          OLD.`version`,
          OLD.`batch_number`);

-- insert two current forms of supported 2FA
INSERT into `person_auth_type_lookup` (`name`, `code`, `created_by`, `version`) VALUES
  ('Pin', 'PIN', @app_user_id, 1),
  ('Card', 'CARD', @app_user_id, 1);

-- update all person entries with the PIN 2FA
UPDATE `person` SET `person_auth_type_lookup_id` = (SELECT `id` FROM `person_auth_type_lookup` WHERE `code` = 'PIN')
WHERE `person_auth_type_lookup_id` IS NULL;

-- set person_auth_type_lookup_id to NOT NULL
ALTER TABLE `person`
    MODIFY COLUMN `person_auth_type_lookup_id` BIGINT UNSIGNED NOT NULL;
