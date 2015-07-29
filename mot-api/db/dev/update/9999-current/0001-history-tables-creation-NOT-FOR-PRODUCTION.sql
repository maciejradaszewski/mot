-- historical tables and triggers - align dev to preproduction
-- Create history table and update trigger for address
CREATE TABLE  IF NOT EXISTS `address_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`address_line_1` varchar(50),
`address_line_2` varchar(50),
`address_line_3` varchar(50),
`address_line_4` varchar(50),
`postcode` varchar(10),
`town` varchar(50),
`country` varchar(50),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_address (`id`,`version`),
  INDEX ix_address_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_address_ai`;
CREATE TRIGGER `tr_address_ai` AFTER INSERT
ON `address` FOR EACH ROW
INSERT INTO  `address_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_address_au`;
CREATE TRIGGER `tr_address_au` AFTER UPDATE
ON `address` FOR EACH ROW 
INSERT INTO  `address_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`address_line_1`,
`address_line_2`,
`address_line_3`,
`address_line_4`,
`postcode`,
`town`,
`country`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`address_line_1`,
OLD.`address_line_2`,
OLD.`address_line_3`,
OLD.`address_line_4`,
OLD.`postcode`,
OLD.`town`,
OLD.`country`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_address_ad`;
CREATE TRIGGER `tr_address_ad` AFTER DELETE
ON `address` FOR EACH ROW 
INSERT INTO  `address_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`address_line_1`,
`address_line_2`,
`address_line_3`,
`address_line_4`,
`postcode`,
`town`,
`country`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`address_line_1`,
OLD.`address_line_2`,
OLD.`address_line_3`,
OLD.`address_line_4`,
OLD.`postcode`,
OLD.`town`,
OLD.`country`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for application
CREATE TABLE  IF NOT EXISTS `application_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`application_reference` varchar(15),
`person_id` int(10) unsigned,
`status_id` smallint(5) unsigned,
`status_changed_on` datetime(6),
`locked_by` int(10) unsigned,
`locked_on` datetime(6),
`submitted_on` datetime(6),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_application (`id`,`version`),
  INDEX ix_application_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_application_ai`;
CREATE TRIGGER `tr_application_ai` AFTER INSERT
ON `application` FOR EACH ROW
INSERT INTO  `application_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_application_au`;
CREATE TRIGGER `tr_application_au` AFTER UPDATE
ON `application` FOR EACH ROW 
INSERT INTO  `application_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`application_reference`,
`person_id`,
`status_id`,
`status_changed_on`,
`locked_by`,
`locked_on`,
`submitted_on`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`application_reference`,
OLD.`person_id`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`locked_by`,
OLD.`locked_on`,
OLD.`submitted_on`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_application_ad`;
CREATE TRIGGER `tr_application_ad` AFTER DELETE
ON `application` FOR EACH ROW 
INSERT INTO  `application_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`application_reference`,
`person_id`,
`status_id`,
`status_changed_on`,
`locked_by`,
`locked_on`,
`submitted_on`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`application_reference`,
OLD.`person_id`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`locked_by`,
OLD.`locked_on`,
OLD.`submitted_on`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for approval_condition_appointment_map
CREATE TABLE  IF NOT EXISTS `approval_condition_appointment_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`site_condition_approval_id` int(10) unsigned,
`condition_appointment_id` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_approval_condition_appointment_map (`id`,`version`),
  INDEX ix_approval_condition_appointment_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_approval_condition_appointment_map_ai`;
CREATE TRIGGER `tr_approval_condition_appointment_map_ai` AFTER INSERT
ON `approval_condition_appointment_map` FOR EACH ROW
INSERT INTO  `approval_condition_appointment_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_approval_condition_appointment_map_au`;
CREATE TRIGGER `tr_approval_condition_appointment_map_au` AFTER UPDATE
ON `approval_condition_appointment_map` FOR EACH ROW 
INSERT INTO  `approval_condition_appointment_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_condition_approval_id`,
`condition_appointment_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`site_condition_approval_id`,
OLD.`condition_appointment_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_approval_condition_appointment_map_ad`;
CREATE TRIGGER `tr_approval_condition_appointment_map_ad` AFTER DELETE
ON `approval_condition_appointment_map` FOR EACH ROW 
INSERT INTO  `approval_condition_appointment_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_condition_approval_id`,
`condition_appointment_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`site_condition_approval_id`,
OLD.`condition_appointment_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for app_auth_site_evidence_map
CREATE TABLE  IF NOT EXISTS `app_auth_site_evidence_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`app_for_auth_testing_mot_at_site_id` int(10) unsigned,
`evidence_id` int(10) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_app_auth_site_evidence_map (`id`,`version`),
  INDEX ix_app_auth_site_evidence_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_app_auth_site_evidence_map_ai`;
CREATE TRIGGER `tr_app_auth_site_evidence_map_ai` AFTER INSERT
ON `app_auth_site_evidence_map` FOR EACH ROW
INSERT INTO  `app_auth_site_evidence_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_app_auth_site_evidence_map_au`;
CREATE TRIGGER `tr_app_auth_site_evidence_map_au` AFTER UPDATE
ON `app_auth_site_evidence_map` FOR EACH ROW 
INSERT INTO  `app_auth_site_evidence_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`app_for_auth_testing_mot_at_site_id`,
`evidence_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`app_for_auth_testing_mot_at_site_id`,
OLD.`evidence_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_app_auth_site_evidence_map_ad`;
CREATE TRIGGER `tr_app_auth_site_evidence_map_ad` AFTER DELETE
ON `app_auth_site_evidence_map` FOR EACH ROW 
INSERT INTO  `app_auth_site_evidence_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`app_for_auth_testing_mot_at_site_id`,
`evidence_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`app_for_auth_testing_mot_at_site_id`,
OLD.`evidence_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for app_for_auth_for_ae
CREATE TABLE  IF NOT EXISTS `app_for_auth_for_ae_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`application_id` int(10) unsigned,
`auth_for_ae_id` int(10) unsigned,
`principle_person_id` int(10) unsigned,
`designated_manager_person_id` int(10) unsigned,
`status_id` smallint(5) unsigned,
`status_changed_on` datetime(6),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_app_for_auth_for_ae (`id`,`version`),
  INDEX ix_app_for_auth_for_ae_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_app_for_auth_for_ae_ai`;
CREATE TRIGGER `tr_app_for_auth_for_ae_ai` AFTER INSERT
ON `app_for_auth_for_ae` FOR EACH ROW
INSERT INTO  `app_for_auth_for_ae_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_app_for_auth_for_ae_au`;
CREATE TRIGGER `tr_app_for_auth_for_ae_au` AFTER UPDATE
ON `app_for_auth_for_ae` FOR EACH ROW 
INSERT INTO  `app_for_auth_for_ae_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`application_id`,
`auth_for_ae_id`,
`principle_person_id`,
`designated_manager_person_id`,
`status_id`,
`status_changed_on`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`application_id`,
OLD.`auth_for_ae_id`,
OLD.`principle_person_id`,
OLD.`designated_manager_person_id`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_app_for_auth_for_ae_ad`;
CREATE TRIGGER `tr_app_for_auth_for_ae_ad` AFTER DELETE
ON `app_for_auth_for_ae` FOR EACH ROW 
INSERT INTO  `app_for_auth_for_ae_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`application_id`,
`auth_for_ae_id`,
`principle_person_id`,
`designated_manager_person_id`,
`status_id`,
`status_changed_on`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`application_id`,
OLD.`auth_for_ae_id`,
OLD.`principle_person_id`,
OLD.`designated_manager_person_id`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for app_for_auth_testing_mot
CREATE TABLE  IF NOT EXISTS `app_for_auth_testing_mot_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`application_id` int(10) unsigned,
`authorisation_for_testing_mot_id` int(10) unsigned,
`status_id` smallint(5) unsigned,
`status_changed_on` datetime(6),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_app_for_auth_testing_mot (`id`,`version`),
  INDEX ix_app_for_auth_testing_mot_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_app_for_auth_testing_mot_ai`;
CREATE TRIGGER `tr_app_for_auth_testing_mot_ai` AFTER INSERT
ON `app_for_auth_testing_mot` FOR EACH ROW
INSERT INTO  `app_for_auth_testing_mot_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_app_for_auth_testing_mot_au`;
CREATE TRIGGER `tr_app_for_auth_testing_mot_au` AFTER UPDATE
ON `app_for_auth_testing_mot` FOR EACH ROW 
INSERT INTO  `app_for_auth_testing_mot_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`application_id`,
`authorisation_for_testing_mot_id`,
`status_id`,
`status_changed_on`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`application_id`,
OLD.`authorisation_for_testing_mot_id`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_app_for_auth_testing_mot_ad`;
CREATE TRIGGER `tr_app_for_auth_testing_mot_ad` AFTER DELETE
ON `app_for_auth_testing_mot` FOR EACH ROW 
INSERT INTO  `app_for_auth_testing_mot_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`application_id`,
`authorisation_for_testing_mot_id`,
`status_id`,
`status_changed_on`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`application_id`,
OLD.`authorisation_for_testing_mot_id`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for app_for_auth_testing_mot_at_site
CREATE TABLE  IF NOT EXISTS `app_for_auth_testing_mot_at_site_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`application_id` int(10) unsigned,
`site_id` int(10) unsigned,
`status_id` smallint(5) unsigned,
`status_changed_on` datetime(6),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_app_for_auth_testing_mot_at_site (`id`,`version`),
  INDEX ix_app_for_auth_testing_mot_at_site_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_app_for_auth_testing_mot_at_site_ai`;
CREATE TRIGGER `tr_app_for_auth_testing_mot_at_site_ai` AFTER INSERT
ON `app_for_auth_testing_mot_at_site` FOR EACH ROW
INSERT INTO  `app_for_auth_testing_mot_at_site_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_app_for_auth_testing_mot_at_site_au`;
CREATE TRIGGER `tr_app_for_auth_testing_mot_at_site_au` AFTER UPDATE
ON `app_for_auth_testing_mot_at_site` FOR EACH ROW 
INSERT INTO  `app_for_auth_testing_mot_at_site_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`application_id`,
`site_id`,
`status_id`,
`status_changed_on`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`application_id`,
OLD.`site_id`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_app_for_auth_testing_mot_at_site_ad`;
CREATE TRIGGER `tr_app_for_auth_testing_mot_at_site_ad` AFTER DELETE
ON `app_for_auth_testing_mot_at_site` FOR EACH ROW 
INSERT INTO  `app_for_auth_testing_mot_at_site_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`application_id`,
`site_id`,
`status_id`,
`status_changed_on`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`application_id`,
OLD.`site_id`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for app_status
CREATE TABLE  IF NOT EXISTS `app_status_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_app_status (`id`,`version`),
  INDEX ix_app_status_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_app_status_ai`;
CREATE TRIGGER `tr_app_status_ai` AFTER INSERT
ON `app_status` FOR EACH ROW
INSERT INTO  `app_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_app_status_au`;
CREATE TRIGGER `tr_app_status_au` AFTER UPDATE
ON `app_status` FOR EACH ROW 
INSERT INTO  `app_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_app_status_ad`;
CREATE TRIGGER `tr_app_status_ad` AFTER DELETE
ON `app_status` FOR EACH ROW 
INSERT INTO  `app_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for app_to_auth_testing_mot_at_site_map
CREATE TABLE  IF NOT EXISTS `app_to_auth_testing_mot_at_site_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`app_for_auth_testing_mot_at_site_id` int(10) unsigned,
`authorisation_testing_mot_at_site_id` int(10) unsigned,
`status_id` smallint(5) unsigned,
`status_changed_on` datetime(6),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_app_to_auth_testing_mot_at_site_map (`id`,`version`),
  INDEX ix_app_to_auth_testing_mot_at_site_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_app_to_auth_testing_mot_at_site_map_ai`;
CREATE TRIGGER `tr_app_to_auth_testing_mot_at_site_map_ai` AFTER INSERT
ON `app_to_auth_testing_mot_at_site_map` FOR EACH ROW
INSERT INTO  `app_to_auth_testing_mot_at_site_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_app_to_auth_testing_mot_at_site_map_au`;
CREATE TRIGGER `tr_app_to_auth_testing_mot_at_site_map_au` AFTER UPDATE
ON `app_to_auth_testing_mot_at_site_map` FOR EACH ROW 
INSERT INTO  `app_to_auth_testing_mot_at_site_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`app_for_auth_testing_mot_at_site_id`,
`authorisation_testing_mot_at_site_id`,
`status_id`,
`status_changed_on`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`app_for_auth_testing_mot_at_site_id`,
OLD.`authorisation_testing_mot_at_site_id`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_app_to_auth_testing_mot_at_site_map_ad`;
CREATE TRIGGER `tr_app_to_auth_testing_mot_at_site_map_ad` AFTER DELETE
ON `app_to_auth_testing_mot_at_site_map` FOR EACH ROW 
INSERT INTO  `app_to_auth_testing_mot_at_site_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`app_for_auth_testing_mot_at_site_id`,
`authorisation_testing_mot_at_site_id`,
`status_id`,
`status_changed_on`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`app_for_auth_testing_mot_at_site_id`,
OLD.`authorisation_testing_mot_at_site_id`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for assembly
CREATE TABLE  IF NOT EXISTS `assembly_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`name` varchar(50),
`code` varchar(10),
`assembly_type_id` smallint(5) unsigned,
`parent_assembly_id` int(10) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_assembly (`id`,`version`),
  INDEX ix_assembly_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_assembly_ai`;
CREATE TRIGGER `tr_assembly_ai` AFTER INSERT
ON `assembly` FOR EACH ROW
INSERT INTO  `assembly_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_assembly_au`;
CREATE TRIGGER `tr_assembly_au` AFTER UPDATE
ON `assembly` FOR EACH ROW 
INSERT INTO  `assembly_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`assembly_type_id`,
`parent_assembly_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`assembly_type_id`,
OLD.`parent_assembly_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_assembly_ad`;
CREATE TRIGGER `tr_assembly_ad` AFTER DELETE
ON `assembly` FOR EACH ROW 
INSERT INTO  `assembly_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`assembly_type_id`,
`parent_assembly_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`assembly_type_id`,
OLD.`parent_assembly_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for assembly_role_type
CREATE TABLE  IF NOT EXISTS `assembly_role_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_assembly_role_type (`id`,`version`),
  INDEX ix_assembly_role_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_assembly_role_type_ai`;
CREATE TRIGGER `tr_assembly_role_type_ai` AFTER INSERT
ON `assembly_role_type` FOR EACH ROW
INSERT INTO  `assembly_role_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_assembly_role_type_au`;
CREATE TRIGGER `tr_assembly_role_type_au` AFTER UPDATE
ON `assembly_role_type` FOR EACH ROW 
INSERT INTO  `assembly_role_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_assembly_role_type_ad`;
CREATE TRIGGER `tr_assembly_role_type_ad` AFTER DELETE
ON `assembly_role_type` FOR EACH ROW 
INSERT INTO  `assembly_role_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for assembly_type
CREATE TABLE  IF NOT EXISTS `assembly_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_assembly_type (`id`,`version`),
  INDEX ix_assembly_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_assembly_type_ai`;
CREATE TRIGGER `tr_assembly_type_ai` AFTER INSERT
ON `assembly_type` FOR EACH ROW
INSERT INTO  `assembly_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_assembly_type_au`;
CREATE TRIGGER `tr_assembly_type_au` AFTER UPDATE
ON `assembly_type` FOR EACH ROW 
INSERT INTO  `assembly_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_assembly_type_ad`;
CREATE TRIGGER `tr_assembly_type_ad` AFTER DELETE
ON `assembly_type` FOR EACH ROW 
INSERT INTO  `assembly_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for auth_for_ae
CREATE TABLE  IF NOT EXISTS `auth_for_ae_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`ae_ref` varchar(12),
`organisation_id` int(10) unsigned,
`ao_site_id` int(10) unsigned,
`status_id` smallint(5) unsigned,
`status_changed_on` datetime(6),
`valid_from` datetime(6),
`expiry_date` datetime(6),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_auth_for_ae (`id`,`version`),
  INDEX ix_auth_for_ae_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_auth_for_ae_ai`;
CREATE TRIGGER `tr_auth_for_ae_ai` AFTER INSERT
ON `auth_for_ae` FOR EACH ROW
INSERT INTO  `auth_for_ae_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_auth_for_ae_au`;
CREATE TRIGGER `tr_auth_for_ae_au` AFTER UPDATE
ON `auth_for_ae` FOR EACH ROW 
INSERT INTO  `auth_for_ae_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`ae_ref`,
`organisation_id`,
`ao_site_id`,
`status_id`,
`status_changed_on`,
`valid_from`,
`expiry_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`ae_ref`,
OLD.`organisation_id`,
OLD.`ao_site_id`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`valid_from`,
OLD.`expiry_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_auth_for_ae_ad`;
CREATE TRIGGER `tr_auth_for_ae_ad` AFTER DELETE
ON `auth_for_ae` FOR EACH ROW 
INSERT INTO  `auth_for_ae_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`ae_ref`,
`organisation_id`,
`ao_site_id`,
`status_id`,
`status_changed_on`,
`valid_from`,
`expiry_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`ae_ref`,
OLD.`organisation_id`,
OLD.`ao_site_id`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`valid_from`,
OLD.`expiry_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for auth_for_ae_person_as_principal_map
CREATE TABLE  IF NOT EXISTS `auth_for_ae_person_as_principal_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`person_id` int(10) unsigned,
`auth_for_ae_id` int(11) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_auth_for_ae_person_as_principal_map (`id`,`version`),
  INDEX ix_auth_for_ae_person_as_principal_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_auth_for_ae_person_as_principal_map_ai`;
CREATE TRIGGER `tr_auth_for_ae_person_as_principal_map_ai` AFTER INSERT
ON `auth_for_ae_person_as_principal_map` FOR EACH ROW
INSERT INTO  `auth_for_ae_person_as_principal_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_auth_for_ae_person_as_principal_map_au`;
CREATE TRIGGER `tr_auth_for_ae_person_as_principal_map_au` AFTER UPDATE
ON `auth_for_ae_person_as_principal_map` FOR EACH ROW 
INSERT INTO  `auth_for_ae_person_as_principal_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`person_id`,
`auth_for_ae_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`person_id`,
OLD.`auth_for_ae_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_auth_for_ae_person_as_principal_map_ad`;
CREATE TRIGGER `tr_auth_for_ae_person_as_principal_map_ad` AFTER DELETE
ON `auth_for_ae_person_as_principal_map` FOR EACH ROW 
INSERT INTO  `auth_for_ae_person_as_principal_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`person_id`,
`auth_for_ae_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`person_id`,
OLD.`auth_for_ae_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for auth_for_ae_status
CREATE TABLE  IF NOT EXISTS `auth_for_ae_status_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_auth_for_ae_status (`id`,`version`),
  INDEX ix_auth_for_ae_status_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_auth_for_ae_status_ai`;
CREATE TRIGGER `tr_auth_for_ae_status_ai` AFTER INSERT
ON `auth_for_ae_status` FOR EACH ROW
INSERT INTO  `auth_for_ae_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_auth_for_ae_status_au`;
CREATE TRIGGER `tr_auth_for_ae_status_au` AFTER UPDATE
ON `auth_for_ae_status` FOR EACH ROW 
INSERT INTO  `auth_for_ae_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_auth_for_ae_status_ad`;
CREATE TRIGGER `tr_auth_for_ae_status_ad` AFTER DELETE
ON `auth_for_ae_status` FOR EACH ROW 
INSERT INTO  `auth_for_ae_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for auth_for_testing_mot
CREATE TABLE  IF NOT EXISTS `auth_for_testing_mot_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`person_id` int(10) unsigned,
`vehicle_class_id` smallint(5) unsigned,
`status_id` smallint(5) unsigned,
`status_changed_on` datetime(6),
`valid_from` datetime(6),
`expiry_date` datetime(6),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_auth_for_testing_mot (`id`,`version`),
  INDEX ix_auth_for_testing_mot_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_auth_for_testing_mot_ai`;
CREATE TRIGGER `tr_auth_for_testing_mot_ai` AFTER INSERT
ON `auth_for_testing_mot` FOR EACH ROW
INSERT INTO  `auth_for_testing_mot_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_auth_for_testing_mot_au`;
CREATE TRIGGER `tr_auth_for_testing_mot_au` AFTER UPDATE
ON `auth_for_testing_mot` FOR EACH ROW 
INSERT INTO  `auth_for_testing_mot_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`person_id`,
`vehicle_class_id`,
`status_id`,
`status_changed_on`,
`valid_from`,
`expiry_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`person_id`,
OLD.`vehicle_class_id`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`valid_from`,
OLD.`expiry_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_auth_for_testing_mot_ad`;
CREATE TRIGGER `tr_auth_for_testing_mot_ad` AFTER DELETE
ON `auth_for_testing_mot` FOR EACH ROW 
INSERT INTO  `auth_for_testing_mot_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`person_id`,
`vehicle_class_id`,
`status_id`,
`status_changed_on`,
`valid_from`,
`expiry_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`person_id`,
OLD.`vehicle_class_id`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`valid_from`,
OLD.`expiry_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for auth_for_testing_mot_at_site
CREATE TABLE  IF NOT EXISTS `auth_for_testing_mot_at_site_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`site_id` int(10) unsigned,
`vehicle_class_id` smallint(5) unsigned,
`status_id` smallint(5) unsigned,
`status_changed_on` datetime(6),
`valid_from` datetime(6),
`expiry_date` datetime(6),
`fuel_type_id` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_auth_for_testing_mot_at_site (`id`,`version`),
  INDEX ix_auth_for_testing_mot_at_site_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_auth_for_testing_mot_at_site_ai`;
CREATE TRIGGER `tr_auth_for_testing_mot_at_site_ai` AFTER INSERT
ON `auth_for_testing_mot_at_site` FOR EACH ROW
INSERT INTO  `auth_for_testing_mot_at_site_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_auth_for_testing_mot_at_site_au`;
CREATE TRIGGER `tr_auth_for_testing_mot_at_site_au` AFTER UPDATE
ON `auth_for_testing_mot_at_site` FOR EACH ROW 
INSERT INTO  `auth_for_testing_mot_at_site_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_id`,
`vehicle_class_id`,
`status_id`,
`status_changed_on`,
`valid_from`,
`expiry_date`,
`fuel_type_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`site_id`,
OLD.`vehicle_class_id`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`valid_from`,
OLD.`expiry_date`,
OLD.`fuel_type_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_auth_for_testing_mot_at_site_ad`;
CREATE TRIGGER `tr_auth_for_testing_mot_at_site_ad` AFTER DELETE
ON `auth_for_testing_mot_at_site` FOR EACH ROW 
INSERT INTO  `auth_for_testing_mot_at_site_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_id`,
`vehicle_class_id`,
`status_id`,
`status_changed_on`,
`valid_from`,
`expiry_date`,
`fuel_type_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`site_id`,
OLD.`vehicle_class_id`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`valid_from`,
OLD.`expiry_date`,
OLD.`fuel_type_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for auth_for_testing_mot_at_site_status
CREATE TABLE  IF NOT EXISTS `auth_for_testing_mot_at_site_status_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_auth_for_testing_mot_at_site_status (`id`,`version`),
  INDEX ix_auth_for_testing_mot_at_site_status_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_auth_for_testing_mot_at_site_status_ai`;
CREATE TRIGGER `tr_auth_for_testing_mot_at_site_status_ai` AFTER INSERT
ON `auth_for_testing_mot_at_site_status` FOR EACH ROW
INSERT INTO  `auth_for_testing_mot_at_site_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_auth_for_testing_mot_at_site_status_au`;
CREATE TRIGGER `tr_auth_for_testing_mot_at_site_status_au` AFTER UPDATE
ON `auth_for_testing_mot_at_site_status` FOR EACH ROW 
INSERT INTO  `auth_for_testing_mot_at_site_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_auth_for_testing_mot_at_site_status_ad`;
CREATE TRIGGER `tr_auth_for_testing_mot_at_site_status_ad` AFTER DELETE
ON `auth_for_testing_mot_at_site_status` FOR EACH ROW 
INSERT INTO  `auth_for_testing_mot_at_site_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for auth_for_testing_mot_role_map
CREATE TABLE  IF NOT EXISTS `auth_for_testing_mot_role_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`vehicle_class_id` smallint(5) unsigned,
`auth_status_id` smallint(5) unsigned,
`role_id` int(10) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_auth_for_testing_mot_role_map (`id`,`version`),
  INDEX ix_auth_for_testing_mot_role_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_auth_for_testing_mot_role_map_ai`;
CREATE TRIGGER `tr_auth_for_testing_mot_role_map_ai` AFTER INSERT
ON `auth_for_testing_mot_role_map` FOR EACH ROW
INSERT INTO  `auth_for_testing_mot_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_auth_for_testing_mot_role_map_au`;
CREATE TRIGGER `tr_auth_for_testing_mot_role_map_au` AFTER UPDATE
ON `auth_for_testing_mot_role_map` FOR EACH ROW 
INSERT INTO  `auth_for_testing_mot_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`vehicle_class_id`,
`auth_status_id`,
`role_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`vehicle_class_id`,
OLD.`auth_status_id`,
OLD.`role_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_auth_for_testing_mot_role_map_ad`;
CREATE TRIGGER `tr_auth_for_testing_mot_role_map_ad` AFTER DELETE
ON `auth_for_testing_mot_role_map` FOR EACH ROW 
INSERT INTO  `auth_for_testing_mot_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`vehicle_class_id`,
`auth_status_id`,
`role_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`vehicle_class_id`,
OLD.`auth_status_id`,
OLD.`role_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for auth_for_testing_mot_status
CREATE TABLE  IF NOT EXISTS `auth_for_testing_mot_status_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_auth_for_testing_mot_status (`id`,`version`),
  INDEX ix_auth_for_testing_mot_status_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_auth_for_testing_mot_status_ai`;
CREATE TRIGGER `tr_auth_for_testing_mot_status_ai` AFTER INSERT
ON `auth_for_testing_mot_status` FOR EACH ROW
INSERT INTO  `auth_for_testing_mot_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_auth_for_testing_mot_status_au`;
CREATE TRIGGER `tr_auth_for_testing_mot_status_au` AFTER UPDATE
ON `auth_for_testing_mot_status` FOR EACH ROW 
INSERT INTO  `auth_for_testing_mot_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_auth_for_testing_mot_status_ad`;
CREATE TRIGGER `tr_auth_for_testing_mot_status_ad` AFTER DELETE
ON `auth_for_testing_mot_status` FOR EACH ROW 
INSERT INTO  `auth_for_testing_mot_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for auth_status
CREATE TABLE  IF NOT EXISTS `auth_status_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_auth_status (`id`,`version`),
  INDEX ix_auth_status_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_auth_status_ai`;
CREATE TRIGGER `tr_auth_status_ai` AFTER INSERT
ON `auth_status` FOR EACH ROW
INSERT INTO  `auth_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_auth_status_au`;
CREATE TRIGGER `tr_auth_status_au` AFTER UPDATE
ON `auth_status` FOR EACH ROW 
INSERT INTO  `auth_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_auth_status_ad`;
CREATE TRIGGER `tr_auth_status_ad` AFTER DELETE
ON `auth_status` FOR EACH ROW 
INSERT INTO  `auth_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for body_type
CREATE TABLE  IF NOT EXISTS `body_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`display_order` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_body_type (`id`,`version`),
  INDEX ix_body_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_body_type_ai`;
CREATE TRIGGER `tr_body_type_ai` AFTER INSERT
ON `body_type` FOR EACH ROW
INSERT INTO  `body_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_body_type_au`;
CREATE TRIGGER `tr_body_type_au` AFTER UPDATE
ON `body_type` FOR EACH ROW 
INSERT INTO  `body_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_body_type_ad`;
CREATE TRIGGER `tr_body_type_ad` AFTER DELETE
ON `body_type` FOR EACH ROW 
INSERT INTO  `body_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for brake_test_result_class_1_2
CREATE TABLE  IF NOT EXISTS `brake_test_result_class_1_2_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` bigint(20) unsigned,
`mot_test_id` bigint(20) unsigned,
`brake_test_type_id` smallint(5) unsigned,
`vehicle_weight_front` smallint(6) unsigned,
`vehicle_weight_rear` smallint(6) unsigned,
`rider_weight` smallint(6) unsigned,
`sidecar_weight` smallint(6) unsigned,
`control_1_effort_front` int(11),
`control_1_effort_rear` int(11),
`control_1_effort_sidecar` int(11),
`control_2_effort_front` int(11),
`control_2_effort_rear` int(11),
`control_2_effort_sidecar` int(11),
`control_1_lock_front` tinyint(4),
`control_1_lock_rear` tinyint(4),
`control_2_lock_front` tinyint(4),
`control_2_lock_rear` tinyint(4),
`control_1_brake_efficiency` smallint(5) unsigned,
`control_2_brake_efficiency` smallint(5) unsigned,
`gradient_control_1_below_minimum` tinyint(4),
`gradient_control_2_below_minimum` tinyint(4),
`control_1_efficiency_pass` tinyint(4),
`control_2_efficiency_pass` tinyint(4),
`general_pass` tinyint(4),
`is_latest` tinyint(4) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_brake_test_result_class_1_2 (`id`,`version`),
  INDEX ix_brake_test_result_class_1_2_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_brake_test_result_class_1_2_ai`;
CREATE TRIGGER `tr_brake_test_result_class_1_2_ai` AFTER INSERT
ON `brake_test_result_class_1_2` FOR EACH ROW
INSERT INTO  `brake_test_result_class_1_2_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_brake_test_result_class_1_2_au`;
CREATE TRIGGER `tr_brake_test_result_class_1_2_au` AFTER UPDATE
ON `brake_test_result_class_1_2` FOR EACH ROW 
INSERT INTO  `brake_test_result_class_1_2_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`mot_test_id`,
`brake_test_type_id`,
`vehicle_weight_front`,
`vehicle_weight_rear`,
`rider_weight`,
`sidecar_weight`,
`control_1_effort_front`,
`control_1_effort_rear`,
`control_1_effort_sidecar`,
`control_2_effort_front`,
`control_2_effort_rear`,
`control_2_effort_sidecar`,
`control_1_lock_front`,
`control_1_lock_rear`,
`control_2_lock_front`,
`control_2_lock_rear`,
`control_1_brake_efficiency`,
`control_2_brake_efficiency`,
`gradient_control_1_below_minimum`,
`gradient_control_2_below_minimum`,
`control_1_efficiency_pass`,
`control_2_efficiency_pass`,
`general_pass`,
`is_latest`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`mot_test_id`,
OLD.`brake_test_type_id`,
OLD.`vehicle_weight_front`,
OLD.`vehicle_weight_rear`,
OLD.`rider_weight`,
OLD.`sidecar_weight`,
OLD.`control_1_effort_front`,
OLD.`control_1_effort_rear`,
OLD.`control_1_effort_sidecar`,
OLD.`control_2_effort_front`,
OLD.`control_2_effort_rear`,
OLD.`control_2_effort_sidecar`,
OLD.`control_1_lock_front`,
OLD.`control_1_lock_rear`,
OLD.`control_2_lock_front`,
OLD.`control_2_lock_rear`,
OLD.`control_1_brake_efficiency`,
OLD.`control_2_brake_efficiency`,
OLD.`gradient_control_1_below_minimum`,
OLD.`gradient_control_2_below_minimum`,
OLD.`control_1_efficiency_pass`,
OLD.`control_2_efficiency_pass`,
OLD.`general_pass`,
OLD.`is_latest`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_brake_test_result_class_1_2_ad`;
CREATE TRIGGER `tr_brake_test_result_class_1_2_ad` AFTER DELETE
ON `brake_test_result_class_1_2` FOR EACH ROW 
INSERT INTO  `brake_test_result_class_1_2_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`mot_test_id`,
`brake_test_type_id`,
`vehicle_weight_front`,
`vehicle_weight_rear`,
`rider_weight`,
`sidecar_weight`,
`control_1_effort_front`,
`control_1_effort_rear`,
`control_1_effort_sidecar`,
`control_2_effort_front`,
`control_2_effort_rear`,
`control_2_effort_sidecar`,
`control_1_lock_front`,
`control_1_lock_rear`,
`control_2_lock_front`,
`control_2_lock_rear`,
`control_1_brake_efficiency`,
`control_2_brake_efficiency`,
`gradient_control_1_below_minimum`,
`gradient_control_2_below_minimum`,
`control_1_efficiency_pass`,
`control_2_efficiency_pass`,
`general_pass`,
`is_latest`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`mot_test_id`,
OLD.`brake_test_type_id`,
OLD.`vehicle_weight_front`,
OLD.`vehicle_weight_rear`,
OLD.`rider_weight`,
OLD.`sidecar_weight`,
OLD.`control_1_effort_front`,
OLD.`control_1_effort_rear`,
OLD.`control_1_effort_sidecar`,
OLD.`control_2_effort_front`,
OLD.`control_2_effort_rear`,
OLD.`control_2_effort_sidecar`,
OLD.`control_1_lock_front`,
OLD.`control_1_lock_rear`,
OLD.`control_2_lock_front`,
OLD.`control_2_lock_rear`,
OLD.`control_1_brake_efficiency`,
OLD.`control_2_brake_efficiency`,
OLD.`gradient_control_1_below_minimum`,
OLD.`gradient_control_2_below_minimum`,
OLD.`control_1_efficiency_pass`,
OLD.`control_2_efficiency_pass`,
OLD.`general_pass`,
OLD.`is_latest`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for brake_test_result_class_3_and_above
CREATE TABLE  IF NOT EXISTS `brake_test_result_class_3_and_above_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` bigint(20) unsigned,
`mot_test_id` bigint(20) unsigned,
`service_brake_1_test_type_id` smallint(5) unsigned,
`service_brake_2_test_type_id` smallint(5) unsigned,
`parking_brake_test_type_id` smallint(5) unsigned,
`service_brake_total_axles_applied_to` tinyint(4),
`parking_brake_total_axles_applied_to` tinyint(4),
`service_brake_1_data_id` bigint(20) unsigned,
`service_brake_2_data_id` bigint(20) unsigned,
`parking_brake_effort_nearside` int(11),
`parking_brake_effort_offside` int(11),
`parking_brake_effort_secondary_nearside` int(11),
`parking_brake_effort_secondary_offside` int(11),
`parking_brake_effort_single` int(11),
`parking_brake_lock_nearside` tinyint(4),
`parking_brake_lock_offside` tinyint(4),
`parking_brake_lock_secondary_nearside` tinyint(4),
`parking_brake_lock_secondary_offside` tinyint(4),
`parking_brake_lock_single` tinyint(4),
`service_brake_is_single_line` tinyint(4),
`is_single_in_front` tinyint(4) unsigned,
`is_commercial_vehicle` tinyint(4),
`vehicle_weight` int(10) unsigned,
`weight_type_id` smallint(5) unsigned,
`weight_is_unladen` tinyint(4),
`service_brake_1_efficiency` smallint(5) unsigned,
`service_brake_2_efficiency` smallint(5) unsigned,
`parking_brake_efficiency` smallint(5) unsigned,
`service_brake_1_efficiency_pass` tinyint(4),
`service_brake_2_efficiency_pass` tinyint(4),
`parking_brake_efficiency_pass` tinyint(4),
`parking_brake_imbalance` tinyint(4),
`parking_brake_secondary_imbalance` tinyint(4),
`parking_brake_imbalance_pass` tinyint(4),
`general_pass` tinyint(4),
`is_latest` tinyint(4) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_brake_test_result_class_3_and_above (`id`,`version`),
  INDEX ix_brake_test_result_class_3_and_above_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_brake_test_result_class_3_and_above_ai`;
CREATE TRIGGER `tr_brake_test_result_class_3_and_above_ai` AFTER INSERT
ON `brake_test_result_class_3_and_above` FOR EACH ROW
INSERT INTO  `brake_test_result_class_3_and_above_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_brake_test_result_class_3_and_above_au`;
CREATE TRIGGER `tr_brake_test_result_class_3_and_above_au` AFTER UPDATE
ON `brake_test_result_class_3_and_above` FOR EACH ROW 
INSERT INTO  `brake_test_result_class_3_and_above_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`mot_test_id`,
`service_brake_1_test_type_id`,
`service_brake_2_test_type_id`,
`parking_brake_test_type_id`,
`service_brake_total_axles_applied_to`,
`parking_brake_total_axles_applied_to`,
`service_brake_1_data_id`,
`service_brake_2_data_id`,
`parking_brake_effort_nearside`,
`parking_brake_effort_offside`,
`parking_brake_effort_secondary_nearside`,
`parking_brake_effort_secondary_offside`,
`parking_brake_effort_single`,
`parking_brake_lock_nearside`,
`parking_brake_lock_offside`,
`parking_brake_lock_secondary_nearside`,
`parking_brake_lock_secondary_offside`,
`parking_brake_lock_single`,
`service_brake_is_single_line`,
`is_single_in_front`,
`is_commercial_vehicle`,
`vehicle_weight`,
`weight_type_id`,
`weight_is_unladen`,
`service_brake_1_efficiency`,
`service_brake_2_efficiency`,
`parking_brake_efficiency`,
`service_brake_1_efficiency_pass`,
`service_brake_2_efficiency_pass`,
`parking_brake_efficiency_pass`,
`parking_brake_imbalance`,
`parking_brake_secondary_imbalance`,
`parking_brake_imbalance_pass`,
`general_pass`,
`is_latest`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`mot_test_id`,
OLD.`service_brake_1_test_type_id`,
OLD.`service_brake_2_test_type_id`,
OLD.`parking_brake_test_type_id`,
OLD.`service_brake_total_axles_applied_to`,
OLD.`parking_brake_total_axles_applied_to`,
OLD.`service_brake_1_data_id`,
OLD.`service_brake_2_data_id`,
OLD.`parking_brake_effort_nearside`,
OLD.`parking_brake_effort_offside`,
OLD.`parking_brake_effort_secondary_nearside`,
OLD.`parking_brake_effort_secondary_offside`,
OLD.`parking_brake_effort_single`,
OLD.`parking_brake_lock_nearside`,
OLD.`parking_brake_lock_offside`,
OLD.`parking_brake_lock_secondary_nearside`,
OLD.`parking_brake_lock_secondary_offside`,
OLD.`parking_brake_lock_single`,
OLD.`service_brake_is_single_line`,
OLD.`is_single_in_front`,
OLD.`is_commercial_vehicle`,
OLD.`vehicle_weight`,
OLD.`weight_type_id`,
OLD.`weight_is_unladen`,
OLD.`service_brake_1_efficiency`,
OLD.`service_brake_2_efficiency`,
OLD.`parking_brake_efficiency`,
OLD.`service_brake_1_efficiency_pass`,
OLD.`service_brake_2_efficiency_pass`,
OLD.`parking_brake_efficiency_pass`,
OLD.`parking_brake_imbalance`,
OLD.`parking_brake_secondary_imbalance`,
OLD.`parking_brake_imbalance_pass`,
OLD.`general_pass`,
OLD.`is_latest`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_brake_test_result_class_3_and_above_ad`;
CREATE TRIGGER `tr_brake_test_result_class_3_and_above_ad` AFTER DELETE
ON `brake_test_result_class_3_and_above` FOR EACH ROW 
INSERT INTO  `brake_test_result_class_3_and_above_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`mot_test_id`,
`service_brake_1_test_type_id`,
`service_brake_2_test_type_id`,
`parking_brake_test_type_id`,
`service_brake_total_axles_applied_to`,
`parking_brake_total_axles_applied_to`,
`service_brake_1_data_id`,
`service_brake_2_data_id`,
`parking_brake_effort_nearside`,
`parking_brake_effort_offside`,
`parking_brake_effort_secondary_nearside`,
`parking_brake_effort_secondary_offside`,
`parking_brake_effort_single`,
`parking_brake_lock_nearside`,
`parking_brake_lock_offside`,
`parking_brake_lock_secondary_nearside`,
`parking_brake_lock_secondary_offside`,
`parking_brake_lock_single`,
`service_brake_is_single_line`,
`is_single_in_front`,
`is_commercial_vehicle`,
`vehicle_weight`,
`weight_type_id`,
`weight_is_unladen`,
`service_brake_1_efficiency`,
`service_brake_2_efficiency`,
`parking_brake_efficiency`,
`service_brake_1_efficiency_pass`,
`service_brake_2_efficiency_pass`,
`parking_brake_efficiency_pass`,
`parking_brake_imbalance`,
`parking_brake_secondary_imbalance`,
`parking_brake_imbalance_pass`,
`general_pass`,
`is_latest`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`mot_test_id`,
OLD.`service_brake_1_test_type_id`,
OLD.`service_brake_2_test_type_id`,
OLD.`parking_brake_test_type_id`,
OLD.`service_brake_total_axles_applied_to`,
OLD.`parking_brake_total_axles_applied_to`,
OLD.`service_brake_1_data_id`,
OLD.`service_brake_2_data_id`,
OLD.`parking_brake_effort_nearside`,
OLD.`parking_brake_effort_offside`,
OLD.`parking_brake_effort_secondary_nearside`,
OLD.`parking_brake_effort_secondary_offside`,
OLD.`parking_brake_effort_single`,
OLD.`parking_brake_lock_nearside`,
OLD.`parking_brake_lock_offside`,
OLD.`parking_brake_lock_secondary_nearside`,
OLD.`parking_brake_lock_secondary_offside`,
OLD.`parking_brake_lock_single`,
OLD.`service_brake_is_single_line`,
OLD.`is_single_in_front`,
OLD.`is_commercial_vehicle`,
OLD.`vehicle_weight`,
OLD.`weight_type_id`,
OLD.`weight_is_unladen`,
OLD.`service_brake_1_efficiency`,
OLD.`service_brake_2_efficiency`,
OLD.`parking_brake_efficiency`,
OLD.`service_brake_1_efficiency_pass`,
OLD.`service_brake_2_efficiency_pass`,
OLD.`parking_brake_efficiency_pass`,
OLD.`parking_brake_imbalance`,
OLD.`parking_brake_secondary_imbalance`,
OLD.`parking_brake_imbalance_pass`,
OLD.`general_pass`,
OLD.`is_latest`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for brake_test_result_service_brake_data
CREATE TABLE  IF NOT EXISTS `brake_test_result_service_brake_data_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` bigint(20) unsigned,
`effort_nearside_axle1` int(11),
`effort_offside_axle1` int(11),
`effort_nearside_axle2` int(11),
`effort_offside_axle2` int(11),
`effort_nearside_axle3` int(11),
`effort_offside_axle3` int(11),
`effort_single` int(11),
`lock_nearside_axle1` tinyint(4),
`lock_offside_axle1` tinyint(4),
`lock_nearside_axle2` tinyint(4),
`lock_offside_axle2` tinyint(4),
`lock_nearside_axle3` tinyint(4),
`lock_offside_axle3` tinyint(4),
`lock_single` tinyint(4),
`imbalance_axle1` tinyint(4),
`imbalance_axle2` tinyint(4),
`imbalance_axle3` tinyint(4),
`imbalance_pass` tinyint(4),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_brake_test_result_service_brake_data (`id`,`version`),
  INDEX ix_brake_test_result_service_brake_data_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_brake_test_result_service_brake_data_ai`;
CREATE TRIGGER `tr_brake_test_result_service_brake_data_ai` AFTER INSERT
ON `brake_test_result_service_brake_data` FOR EACH ROW
INSERT INTO  `brake_test_result_service_brake_data_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_brake_test_result_service_brake_data_au`;
CREATE TRIGGER `tr_brake_test_result_service_brake_data_au` AFTER UPDATE
ON `brake_test_result_service_brake_data` FOR EACH ROW 
INSERT INTO  `brake_test_result_service_brake_data_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`effort_nearside_axle1`,
`effort_offside_axle1`,
`effort_nearside_axle2`,
`effort_offside_axle2`,
`effort_nearside_axle3`,
`effort_offside_axle3`,
`effort_single`,
`lock_nearside_axle1`,
`lock_offside_axle1`,
`lock_nearside_axle2`,
`lock_offside_axle2`,
`lock_nearside_axle3`,
`lock_offside_axle3`,
`lock_single`,
`imbalance_axle1`,
`imbalance_axle2`,
`imbalance_axle3`,
`imbalance_pass`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`effort_nearside_axle1`,
OLD.`effort_offside_axle1`,
OLD.`effort_nearside_axle2`,
OLD.`effort_offside_axle2`,
OLD.`effort_nearside_axle3`,
OLD.`effort_offside_axle3`,
OLD.`effort_single`,
OLD.`lock_nearside_axle1`,
OLD.`lock_offside_axle1`,
OLD.`lock_nearside_axle2`,
OLD.`lock_offside_axle2`,
OLD.`lock_nearside_axle3`,
OLD.`lock_offside_axle3`,
OLD.`lock_single`,
OLD.`imbalance_axle1`,
OLD.`imbalance_axle2`,
OLD.`imbalance_axle3`,
OLD.`imbalance_pass`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_brake_test_result_service_brake_data_ad`;
CREATE TRIGGER `tr_brake_test_result_service_brake_data_ad` AFTER DELETE
ON `brake_test_result_service_brake_data` FOR EACH ROW 
INSERT INTO  `brake_test_result_service_brake_data_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`effort_nearside_axle1`,
`effort_offside_axle1`,
`effort_nearside_axle2`,
`effort_offside_axle2`,
`effort_nearside_axle3`,
`effort_offside_axle3`,
`effort_single`,
`lock_nearside_axle1`,
`lock_offside_axle1`,
`lock_nearside_axle2`,
`lock_offside_axle2`,
`lock_nearside_axle3`,
`lock_offside_axle3`,
`lock_single`,
`imbalance_axle1`,
`imbalance_axle2`,
`imbalance_axle3`,
`imbalance_pass`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`effort_nearside_axle1`,
OLD.`effort_offside_axle1`,
OLD.`effort_nearside_axle2`,
OLD.`effort_offside_axle2`,
OLD.`effort_nearside_axle3`,
OLD.`effort_offside_axle3`,
OLD.`effort_single`,
OLD.`lock_nearside_axle1`,
OLD.`lock_offside_axle1`,
OLD.`lock_nearside_axle2`,
OLD.`lock_offside_axle2`,
OLD.`lock_nearside_axle3`,
OLD.`lock_offside_axle3`,
OLD.`lock_single`,
OLD.`imbalance_axle1`,
OLD.`imbalance_axle2`,
OLD.`imbalance_axle3`,
OLD.`imbalance_pass`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for brake_test_type
CREATE TABLE  IF NOT EXISTS `brake_test_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`description` varchar(250),
`code` varchar(5),
`display_order` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_brake_test_type (`id`,`version`),
  INDEX ix_brake_test_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_brake_test_type_ai`;
CREATE TRIGGER `tr_brake_test_type_ai` AFTER INSERT
ON `brake_test_type` FOR EACH ROW
INSERT INTO  `brake_test_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_brake_test_type_au`;
CREATE TRIGGER `tr_brake_test_type_au` AFTER UPDATE
ON `brake_test_type` FOR EACH ROW 
INSERT INTO  `brake_test_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`description`,
`code`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`description`,
OLD.`code`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_brake_test_type_ad`;
CREATE TRIGGER `tr_brake_test_type_ad` AFTER DELETE
ON `brake_test_type` FOR EACH ROW 
INSERT INTO  `brake_test_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`description`,
`code`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`description`,
OLD.`code`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for business_role_status
CREATE TABLE  IF NOT EXISTS `business_role_status_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_business_role_status (`id`,`version`),
  INDEX ix_business_role_status_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_business_role_status_ai`;
CREATE TRIGGER `tr_business_role_status_ai` AFTER INSERT
ON `business_role_status` FOR EACH ROW
INSERT INTO  `business_role_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_business_role_status_au`;
CREATE TRIGGER `tr_business_role_status_au` AFTER UPDATE
ON `business_role_status` FOR EACH ROW 
INSERT INTO  `business_role_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_business_role_status_ad`;
CREATE TRIGGER `tr_business_role_status_ad` AFTER DELETE
ON `business_role_status` FOR EACH ROW 
INSERT INTO  `business_role_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for business_rule
CREATE TABLE  IF NOT EXISTS `business_rule_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`name` varchar(50),
`definition` text,
`business_rule_type_id` smallint(5) unsigned,
`comparison` varchar(2),
`date_value` date,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_business_rule (`id`,`version`),
  INDEX ix_business_rule_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_business_rule_ai`;
CREATE TRIGGER `tr_business_rule_ai` AFTER INSERT
ON `business_rule` FOR EACH ROW
INSERT INTO  `business_rule_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_business_rule_au`;
CREATE TRIGGER `tr_business_rule_au` AFTER UPDATE
ON `business_rule` FOR EACH ROW 
INSERT INTO  `business_rule_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`definition`,
`business_rule_type_id`,
`comparison`,
`date_value`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`definition`,
OLD.`business_rule_type_id`,
OLD.`comparison`,
OLD.`date_value`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_business_rule_ad`;
CREATE TRIGGER `tr_business_rule_ad` AFTER DELETE
ON `business_rule` FOR EACH ROW 
INSERT INTO  `business_rule_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`definition`,
`business_rule_type_id`,
`comparison`,
`date_value`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`definition`,
OLD.`business_rule_type_id`,
OLD.`comparison`,
OLD.`date_value`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for business_rule_type
CREATE TABLE  IF NOT EXISTS `business_rule_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_business_rule_type (`id`,`version`),
  INDEX ix_business_rule_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_business_rule_type_ai`;
CREATE TRIGGER `tr_business_rule_type_ai` AFTER INSERT
ON `business_rule_type` FOR EACH ROW
INSERT INTO  `business_rule_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_business_rule_type_au`;
CREATE TRIGGER `tr_business_rule_type_au` AFTER UPDATE
ON `business_rule_type` FOR EACH ROW 
INSERT INTO  `business_rule_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_business_rule_type_ad`;
CREATE TRIGGER `tr_business_rule_type_ad` AFTER DELETE
ON `business_rule_type` FOR EACH ROW 
INSERT INTO  `business_rule_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for card_payment_token_usage
CREATE TABLE  IF NOT EXISTS `card_payment_token_usage_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`token` varchar(50),
`status` varchar(50),
`payment_id` int(10) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_card_payment_token_usage (`id`,`version`),
  INDEX ix_card_payment_token_usage_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_card_payment_token_usage_ai`;
CREATE TRIGGER `tr_card_payment_token_usage_ai` AFTER INSERT
ON `card_payment_token_usage` FOR EACH ROW
INSERT INTO  `card_payment_token_usage_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_card_payment_token_usage_au`;
CREATE TRIGGER `tr_card_payment_token_usage_au` AFTER UPDATE
ON `card_payment_token_usage` FOR EACH ROW 
INSERT INTO  `card_payment_token_usage_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`token`,
`status`,
`payment_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`token`,
OLD.`status`,
OLD.`payment_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_card_payment_token_usage_ad`;
CREATE TRIGGER `tr_card_payment_token_usage_ad` AFTER DELETE
ON `card_payment_token_usage` FOR EACH ROW 
INSERT INTO  `card_payment_token_usage_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`token`,
`status`,
`payment_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`token`,
OLD.`status`,
OLD.`payment_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for censor_blacklist
CREATE TABLE  IF NOT EXISTS `censor_blacklist_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`phrase` varchar(100),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_censor_blacklist (`id`,`version`),
  INDEX ix_censor_blacklist_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_censor_blacklist_ai`;
CREATE TRIGGER `tr_censor_blacklist_ai` AFTER INSERT
ON `censor_blacklist` FOR EACH ROW
INSERT INTO  `censor_blacklist_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_censor_blacklist_au`;
CREATE TRIGGER `tr_censor_blacklist_au` AFTER UPDATE
ON `censor_blacklist` FOR EACH ROW 
INSERT INTO  `censor_blacklist_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`phrase`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`phrase`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_censor_blacklist_ad`;
CREATE TRIGGER `tr_censor_blacklist_ad` AFTER DELETE
ON `censor_blacklist` FOR EACH ROW 
INSERT INTO  `censor_blacklist_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`phrase`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`phrase`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for certificate_change_different_tester_reason_lookup
CREATE TABLE  IF NOT EXISTS `certificate_change_different_tester_reason_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`code` varchar(5),
`description` varchar(100),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_certificate_change_different_tester_reason_lookup (`id`,`version`),
  INDEX ix_certificate_change_different_tester_reason_l_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_certificate_change_different_tester_reason_lookup_ai`;
CREATE TRIGGER `tr_certificate_change_different_tester_reason_lookup_ai` AFTER INSERT
ON `certificate_change_different_tester_reason_lookup` FOR EACH ROW
INSERT INTO  `certificate_change_different_tester_reason_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_certificate_change_different_tester_reason_lookup_au`;
CREATE TRIGGER `tr_certificate_change_different_tester_reason_lookup_au` AFTER UPDATE
ON `certificate_change_different_tester_reason_lookup` FOR EACH ROW 
INSERT INTO  `certificate_change_different_tester_reason_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`description`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`code`,
OLD.`description`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_certificate_change_different_tester_reason_lookup_ad`;
CREATE TRIGGER `tr_certificate_change_different_tester_reason_lookup_ad` AFTER DELETE
ON `certificate_change_different_tester_reason_lookup` FOR EACH ROW 
INSERT INTO  `certificate_change_different_tester_reason_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`description`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`code`,
OLD.`description`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for certificate_replacement
CREATE TABLE  IF NOT EXISTS `certificate_replacement_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` bigint(10) unsigned,
`mot_test_id` bigint(20) unsigned,
`mot_test_version` int(10) unsigned,
`different_tester_reason_id` smallint(5) unsigned,
`document_id` bigint(20) unsigned,
`certificate_status_id` smallint(5) unsigned,
`tester_person_id` int(10) unsigned,
`reason` text,
`is_vin_registration_changed` tinyint(3) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_certificate_replacement (`id`,`version`),
  INDEX ix_certificate_replacement_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_certificate_replacement_ai`;
CREATE TRIGGER `tr_certificate_replacement_ai` AFTER INSERT
ON `certificate_replacement` FOR EACH ROW
INSERT INTO  `certificate_replacement_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_certificate_replacement_au`;
CREATE TRIGGER `tr_certificate_replacement_au` AFTER UPDATE
ON `certificate_replacement` FOR EACH ROW 
INSERT INTO  `certificate_replacement_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`mot_test_id`,
`mot_test_version`,
`different_tester_reason_id`,
`document_id`,
`certificate_status_id`,
`tester_person_id`,
`reason`,
`is_vin_registration_changed`,
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
OLD.`certificate_status_id`,
OLD.`tester_person_id`,
OLD.`reason`,
OLD.`is_vin_registration_changed`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_certificate_replacement_ad`;
CREATE TRIGGER `tr_certificate_replacement_ad` AFTER DELETE
ON `certificate_replacement` FOR EACH ROW 
INSERT INTO  `certificate_replacement_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`mot_test_id`,
`mot_test_version`,
`different_tester_reason_id`,
`document_id`,
`certificate_status_id`,
`tester_person_id`,
`reason`,
`is_vin_registration_changed`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`mot_test_id`,
OLD.`mot_test_version`,
OLD.`different_tester_reason_id`,
OLD.`document_id`,
OLD.`certificate_status_id`,
OLD.`tester_person_id`,
OLD.`reason`,
OLD.`is_vin_registration_changed`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for certificate_status
CREATE TABLE  IF NOT EXISTS `certificate_status_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(30),
`display_order` smallint(5) unsigned,
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_certificate_status (`id`,`version`),
  INDEX ix_certificate_status_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_certificate_status_ai`;
CREATE TRIGGER `tr_certificate_status_ai` AFTER INSERT
ON `certificate_status` FOR EACH ROW
INSERT INTO  `certificate_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_certificate_status_au`;
CREATE TRIGGER `tr_certificate_status_au` AFTER UPDATE
ON `certificate_status` FOR EACH ROW 
INSERT INTO  `certificate_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`display_order`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`display_order`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_certificate_status_ad`;
CREATE TRIGGER `tr_certificate_status_ad` AFTER DELETE
ON `certificate_status` FOR EACH ROW 
INSERT INTO  `certificate_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`display_order`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`display_order`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for colour_lookup
CREATE TABLE  IF NOT EXISTS `colour_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`code` varchar(5),
`name` varchar(50),
`display_order` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_colour_lookup (`id`,`version`),
  INDEX ix_colour_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_colour_lookup_ai`;
CREATE TRIGGER `tr_colour_lookup_ai` AFTER INSERT
ON `colour_lookup` FOR EACH ROW
INSERT INTO  `colour_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_colour_lookup_au`;
CREATE TRIGGER `tr_colour_lookup_au` AFTER UPDATE
ON `colour_lookup` FOR EACH ROW 
INSERT INTO  `colour_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_colour_lookup_ad`;
CREATE TRIGGER `tr_colour_lookup_ad` AFTER DELETE
ON `colour_lookup` FOR EACH ROW 
INSERT INTO  `colour_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for comment
CREATE TABLE  IF NOT EXISTS `comment_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` bigint(20) unsigned,
`comment` text,
`author_person_id` int(10) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_comment (`id`,`version`),
  INDEX ix_comment_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_comment_ai`;
CREATE TRIGGER `tr_comment_ai` AFTER INSERT
ON `comment` FOR EACH ROW
INSERT INTO  `comment_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_comment_au`;
CREATE TRIGGER `tr_comment_au` AFTER UPDATE
ON `comment` FOR EACH ROW 
INSERT INTO  `comment_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`comment`,
`author_person_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`comment`,
OLD.`author_person_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_comment_ad`;
CREATE TRIGGER `tr_comment_ad` AFTER DELETE
ON `comment` FOR EACH ROW 
INSERT INTO  `comment_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`comment`,
`author_person_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`comment`,
OLD.`author_person_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for company_type
CREATE TABLE  IF NOT EXISTS `company_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_company_type (`id`,`version`),
  INDEX ix_company_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_company_type_ai`;
CREATE TRIGGER `tr_company_type_ai` AFTER INSERT
ON `company_type` FOR EACH ROW
INSERT INTO  `company_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_company_type_au`;
CREATE TRIGGER `tr_company_type_au` AFTER UPDATE
ON `company_type` FOR EACH ROW 
INSERT INTO  `company_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_company_type_ad`;
CREATE TRIGGER `tr_company_type_ad` AFTER DELETE
ON `company_type` FOR EACH ROW 
INSERT INTO  `company_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for configuration
CREATE TABLE  IF NOT EXISTS `configuration_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`key` varchar(50),
`value` varchar(50),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_configuration (`id`,`version`),
  INDEX ix_configuration_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_configuration_ai`;
CREATE TRIGGER `tr_configuration_ai` AFTER INSERT
ON `configuration` FOR EACH ROW
INSERT INTO  `configuration_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_configuration_au`;
CREATE TRIGGER `tr_configuration_au` AFTER UPDATE
ON `configuration` FOR EACH ROW 
INSERT INTO  `configuration_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`key`,
`value`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`key`,
OLD.`value`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_configuration_ad`;
CREATE TRIGGER `tr_configuration_ad` AFTER DELETE
ON `configuration` FOR EACH ROW 
INSERT INTO  `configuration_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`key`,
`value`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`key`,
OLD.`value`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for contact_detail
CREATE TABLE  IF NOT EXISTS `contact_detail_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`for_attention_of` varchar(50),
`address_id` int(10) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_contact_detail (`id`,`version`),
  INDEX ix_contact_detail_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_contact_detail_ai`;
CREATE TRIGGER `tr_contact_detail_ai` AFTER INSERT
ON `contact_detail` FOR EACH ROW
INSERT INTO  `contact_detail_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_contact_detail_au`;
CREATE TRIGGER `tr_contact_detail_au` AFTER UPDATE
ON `contact_detail` FOR EACH ROW 
INSERT INTO  `contact_detail_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`for_attention_of`,
`address_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`for_attention_of`,
OLD.`address_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_contact_detail_ad`;
CREATE TRIGGER `tr_contact_detail_ad` AFTER DELETE
ON `contact_detail` FOR EACH ROW 
INSERT INTO  `contact_detail_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`for_attention_of`,
`address_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`for_attention_of`,
OLD.`address_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for contact_type
CREATE TABLE  IF NOT EXISTS `contact_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_contact_type (`id`,`version`),
  INDEX ix_contact_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_contact_type_ai`;
CREATE TRIGGER `tr_contact_type_ai` AFTER INSERT
ON `contact_type` FOR EACH ROW
INSERT INTO  `contact_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_contact_type_au`;
CREATE TRIGGER `tr_contact_type_au` AFTER UPDATE
ON `contact_type` FOR EACH ROW 
INSERT INTO  `contact_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_contact_type_ad`;
CREATE TRIGGER `tr_contact_type_ad` AFTER DELETE
ON `contact_type` FOR EACH ROW 
INSERT INTO  `contact_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for content_type
CREATE TABLE  IF NOT EXISTS `content_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_content_type (`id`,`version`),
  INDEX ix_content_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_content_type_ai`;
CREATE TRIGGER `tr_content_type_ai` AFTER INSERT
ON `content_type` FOR EACH ROW
INSERT INTO  `content_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_content_type_au`;
CREATE TRIGGER `tr_content_type_au` AFTER UPDATE
ON `content_type` FOR EACH ROW 
INSERT INTO  `content_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_content_type_ad`;
CREATE TRIGGER `tr_content_type_ad` AFTER DELETE
ON `content_type` FOR EACH ROW 
INSERT INTO  `content_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for conviction
CREATE TABLE  IF NOT EXISTS `conviction_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`application_id` int(10) unsigned,
`reference` varchar(100),
`date_time` date,
`court` varchar(50),
`offence` varchar(50),
`creation_time` datetime(6),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_conviction (`id`,`version`),
  INDEX ix_conviction_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_conviction_ai`;
CREATE TRIGGER `tr_conviction_ai` AFTER INSERT
ON `conviction` FOR EACH ROW
INSERT INTO  `conviction_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_conviction_au`;
CREATE TRIGGER `tr_conviction_au` AFTER UPDATE
ON `conviction` FOR EACH ROW 
INSERT INTO  `conviction_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`application_id`,
`reference`,
`date_time`,
`court`,
`offence`,
`creation_time`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`application_id`,
OLD.`reference`,
OLD.`date_time`,
OLD.`court`,
OLD.`offence`,
OLD.`creation_time`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_conviction_ad`;
CREATE TRIGGER `tr_conviction_ad` AFTER DELETE
ON `conviction` FOR EACH ROW 
INSERT INTO  `conviction_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`application_id`,
`reference`,
`date_time`,
`court`,
`offence`,
`creation_time`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`application_id`,
OLD.`reference`,
OLD.`date_time`,
OLD.`court`,
OLD.`offence`,
OLD.`creation_time`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for country_lookup
CREATE TABLE  IF NOT EXISTS `country_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
`display_order` smallint(5) unsigned,
`iso_code` varchar(5),
  PRIMARY KEY (`hist_id`),
  INDEX uq_country_lookup (`id`,`version`),
  INDEX ix_country_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_country_lookup_ai`;
CREATE TRIGGER `tr_country_lookup_ai` AFTER INSERT
ON `country_lookup` FOR EACH ROW
INSERT INTO  `country_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_country_lookup_au`;
CREATE TRIGGER `tr_country_lookup_au` AFTER UPDATE
ON `country_lookup` FOR EACH ROW 
INSERT INTO  `country_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`,
`display_order`,
`iso_code`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`,
OLD.`display_order`,
OLD.`iso_code`);

DROP TRIGGER IF EXISTS `tr_country_lookup_ad`;
CREATE TRIGGER `tr_country_lookup_ad` AFTER DELETE
ON `country_lookup` FOR EACH ROW 
INSERT INTO  `country_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`,
`display_order`,
`iso_code`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`,
OLD.`display_order`,
OLD.`iso_code`);


-- Create history table and update trigger for country_of_registration_lookup
CREATE TABLE  IF NOT EXISTS `country_of_registration_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`country_lookup_id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`licensing_copy` varchar(5),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_country_of_registration_lookup (`id`,`version`),
  INDEX ix_country_of_registration_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_country_of_registration_lookup_ai`;
CREATE TRIGGER `tr_country_of_registration_lookup_ai` AFTER INSERT
ON `country_of_registration_lookup` FOR EACH ROW
INSERT INTO  `country_of_registration_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_country_of_registration_lookup_au`;
CREATE TRIGGER `tr_country_of_registration_lookup_au` AFTER UPDATE
ON `country_of_registration_lookup` FOR EACH ROW 
INSERT INTO  `country_of_registration_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`country_lookup_id`,
`name`,
`code`,
`mot1_legacy_id`,
`licensing_copy`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`country_lookup_id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`licensing_copy`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_country_of_registration_lookup_ad`;
CREATE TRIGGER `tr_country_of_registration_lookup_ad` AFTER DELETE
ON `country_of_registration_lookup` FOR EACH ROW 
INSERT INTO  `country_of_registration_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`country_lookup_id`,
`name`,
`code`,
`mot1_legacy_id`,
`licensing_copy`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`country_lookup_id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`licensing_copy`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for direct_debit
CREATE TABLE  IF NOT EXISTS `direct_debit_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`organisation_id` int(10) unsigned,
`person_id` int(10) unsigned,
`status_id` smallint(5) unsigned,
`mandate_reference` varchar(50),
`slots` int(10) unsigned,
`setup_date` datetime,
`next_collection_date` date,
`last_increment_date` date,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
`is_active` tinyint(1),
  PRIMARY KEY (`hist_id`),
  INDEX uq_direct_debit (`id`,`version`),
  INDEX ix_direct_debit_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_direct_debit_ai`;
CREATE TRIGGER `tr_direct_debit_ai` AFTER INSERT
ON `direct_debit` FOR EACH ROW
INSERT INTO  `direct_debit_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_direct_debit_au`;
CREATE TRIGGER `tr_direct_debit_au` AFTER UPDATE
ON `direct_debit` FOR EACH ROW 
INSERT INTO  `direct_debit_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`organisation_id`,
`person_id`,
`status_id`,
`mandate_reference`,
`slots`,
`setup_date`,
`next_collection_date`,
`last_increment_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`,
`is_active`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`organisation_id`,
OLD.`person_id`,
OLD.`status_id`,
OLD.`mandate_reference`,
OLD.`slots`,
OLD.`setup_date`,
OLD.`next_collection_date`,
OLD.`last_increment_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`,
OLD.`is_active`);

DROP TRIGGER IF EXISTS `tr_direct_debit_ad`;
CREATE TRIGGER `tr_direct_debit_ad` AFTER DELETE
ON `direct_debit` FOR EACH ROW 
INSERT INTO  `direct_debit_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`organisation_id`,
`person_id`,
`status_id`,
`mandate_reference`,
`slots`,
`setup_date`,
`next_collection_date`,
`last_increment_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`,
`is_active`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`organisation_id`,
OLD.`person_id`,
OLD.`status_id`,
OLD.`mandate_reference`,
OLD.`slots`,
OLD.`setup_date`,
OLD.`next_collection_date`,
OLD.`last_increment_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`,
OLD.`is_active`);


-- Create history table and update trigger for direct_debit_history
CREATE TABLE  IF NOT EXISTS `direct_debit_history_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`direct_debit_id` int(10) unsigned,
`transaction_id` int(10) unsigned,
`status_id` smallint(5) unsigned,
`increment_date` datetime,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_direct_debit_history (`id`,`version`),
  INDEX ix_direct_debit_history_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_direct_debit_history_ai`;
CREATE TRIGGER `tr_direct_debit_history_ai` AFTER INSERT
ON `direct_debit_history` FOR EACH ROW
INSERT INTO  `direct_debit_history_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_direct_debit_history_au`;
CREATE TRIGGER `tr_direct_debit_history_au` AFTER UPDATE
ON `direct_debit_history` FOR EACH ROW 
INSERT INTO  `direct_debit_history_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`direct_debit_id`,
`transaction_id`,
`status_id`,
`increment_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`direct_debit_id`,
OLD.`transaction_id`,
OLD.`status_id`,
OLD.`increment_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_direct_debit_history_ad`;
CREATE TRIGGER `tr_direct_debit_history_ad` AFTER DELETE
ON `direct_debit_history` FOR EACH ROW 
INSERT INTO  `direct_debit_history_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`direct_debit_id`,
`transaction_id`,
`status_id`,
`increment_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`direct_debit_id`,
OLD.`transaction_id`,
OLD.`status_id`,
OLD.`increment_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for direct_debit_history_status
CREATE TABLE  IF NOT EXISTS `direct_debit_history_status_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_direct_debit_history_status (`id`,`version`),
  INDEX ix_direct_debit_history_status_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_direct_debit_history_status_ai`;
CREATE TRIGGER `tr_direct_debit_history_status_ai` AFTER INSERT
ON `direct_debit_history_status` FOR EACH ROW
INSERT INTO  `direct_debit_history_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_direct_debit_history_status_au`;
CREATE TRIGGER `tr_direct_debit_history_status_au` AFTER UPDATE
ON `direct_debit_history_status` FOR EACH ROW 
INSERT INTO  `direct_debit_history_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_direct_debit_history_status_ad`;
CREATE TRIGGER `tr_direct_debit_history_status_ad` AFTER DELETE
ON `direct_debit_history_status` FOR EACH ROW 
INSERT INTO  `direct_debit_history_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for direct_debit_status
CREATE TABLE  IF NOT EXISTS `direct_debit_status_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
`cpms_code` varchar(5),
  PRIMARY KEY (`hist_id`),
  INDEX uq_direct_debit_status (`id`,`version`),
  INDEX ix_direct_debit_status_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_direct_debit_status_ai`;
CREATE TRIGGER `tr_direct_debit_status_ai` AFTER INSERT
ON `direct_debit_status` FOR EACH ROW
INSERT INTO  `direct_debit_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_direct_debit_status_au`;
CREATE TRIGGER `tr_direct_debit_status_au` AFTER UPDATE
ON `direct_debit_status` FOR EACH ROW 
INSERT INTO  `direct_debit_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`,
`cpms_code`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`,
OLD.`cpms_code`);

DROP TRIGGER IF EXISTS `tr_direct_debit_status_ad`;
CREATE TRIGGER `tr_direct_debit_status_ad` AFTER DELETE
ON `direct_debit_status` FOR EACH ROW 
INSERT INTO  `direct_debit_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`,
`cpms_code`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`,
OLD.`cpms_code`);


-- Create history table and update trigger for dvla_make
CREATE TABLE  IF NOT EXISTS `dvla_make_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_dvla_make (`id`,`version`),
  INDEX ix_dvla_make_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_dvla_make_ai`;
CREATE TRIGGER `tr_dvla_make_ai` AFTER INSERT
ON `dvla_make` FOR EACH ROW
INSERT INTO  `dvla_make_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_dvla_make_au`;
CREATE TRIGGER `tr_dvla_make_au` AFTER UPDATE
ON `dvla_make` FOR EACH ROW 
INSERT INTO  `dvla_make_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_dvla_make_ad`;
CREATE TRIGGER `tr_dvla_make_ad` AFTER DELETE
ON `dvla_make` FOR EACH ROW 
INSERT INTO  `dvla_make_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for dvla_model
CREATE TABLE  IF NOT EXISTS `dvla_model_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`name` varchar(50),
`make_code` varchar(5),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_dvla_model (`id`,`version`),
  INDEX ix_dvla_model_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_dvla_model_ai`;
CREATE TRIGGER `tr_dvla_model_ai` AFTER INSERT
ON `dvla_model` FOR EACH ROW
INSERT INTO  `dvla_model_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_dvla_model_au`;
CREATE TRIGGER `tr_dvla_model_au` AFTER UPDATE
ON `dvla_model` FOR EACH ROW 
INSERT INTO  `dvla_model_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`make_code`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`make_code`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_dvla_model_ad`;
CREATE TRIGGER `tr_dvla_model_ad` AFTER DELETE
ON `dvla_model` FOR EACH ROW 
INSERT INTO  `dvla_model_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`make_code`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`make_code`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for dvla_model_model_detail_code_map
CREATE TABLE  IF NOT EXISTS `dvla_model_model_detail_code_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`dvla_make_code` varchar(5),
`dvla_model_code` varchar(5),
`make_id` int(10) unsigned,
`model_id` int(10) unsigned,
`model_detail_id` int(10) unsigned,
`vsi_code` varchar(10),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_dvla_model_model_detail_code_map (`id`,`version`),
  INDEX ix_dvla_model_model_detail_code_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_dvla_model_model_detail_code_map_ai`;
CREATE TRIGGER `tr_dvla_model_model_detail_code_map_ai` AFTER INSERT
ON `dvla_model_model_detail_code_map` FOR EACH ROW
INSERT INTO  `dvla_model_model_detail_code_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_dvla_model_model_detail_code_map_au`;
CREATE TRIGGER `tr_dvla_model_model_detail_code_map_au` AFTER UPDATE
ON `dvla_model_model_detail_code_map` FOR EACH ROW 
INSERT INTO  `dvla_model_model_detail_code_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`dvla_make_code`,
`dvla_model_code`,
`make_id`,
`model_id`,
`model_detail_id`,
`vsi_code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`dvla_make_code`,
OLD.`dvla_model_code`,
OLD.`make_id`,
OLD.`model_id`,
OLD.`model_detail_id`,
OLD.`vsi_code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_dvla_model_model_detail_code_map_ad`;
CREATE TRIGGER `tr_dvla_model_model_detail_code_map_ad` AFTER DELETE
ON `dvla_model_model_detail_code_map` FOR EACH ROW 
INSERT INTO  `dvla_model_model_detail_code_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`dvla_make_code`,
`dvla_model_code`,
`make_id`,
`model_id`,
`model_detail_id`,
`vsi_code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`dvla_make_code`,
OLD.`dvla_model_code`,
OLD.`make_id`,
OLD.`model_id`,
OLD.`model_detail_id`,
OLD.`vsi_code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for dvla_vehicle
CREATE TABLE  IF NOT EXISTS `dvla_vehicle_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`registration` varchar(7),
`registration_collapsed` varchar(13),
`registration_validation_character` varchar(1),
`vin` varchar(20),
`vin_reversed` varchar(20),
`vin_collapsed` varchar(20),
`vin_collapsed_reversed` varchar(20),
`model_code` varchar(5),
`make_code` varchar(5),
`make_in_full` varchar(20),
`colour_1_code` varchar(1),
`colour_2_code` varchar(1),
`propulsion_code` varchar(2),
`designed_gross_weight` int(11),
`unladen_weight` int(11),
`engine_number` varchar(20),
`engine_capacity` int(11),
`seating_capacity` smallint(5) unsigned,
`manufacture_date` date,
`first_registration_date` date,
`is_seriously_damaged` tinyint(4),
`recent_v5_document_number` varchar(11),
`is_vehicle_new_at_first_registration` tinyint(4),
`body_type_code` varchar(2),
`wheelplan_code` varchar(1),
`sva_emission_standard` varchar(6),
`ct_related_mark` varchar(13),
`vehicle_id` int(10) unsigned,
`dvla_vehicle_id` int(9) unsigned,
`eu_classification` varchar(2),
`mass_in_service_weight` int(9) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_dvla_vehicle (`id`,`version`),
  INDEX ix_dvla_vehicle_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_dvla_vehicle_ai`;
CREATE TRIGGER `tr_dvla_vehicle_ai` AFTER INSERT
ON `dvla_vehicle` FOR EACH ROW
INSERT INTO  `dvla_vehicle_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_dvla_vehicle_au`;
CREATE TRIGGER `tr_dvla_vehicle_au` AFTER UPDATE
ON `dvla_vehicle` FOR EACH ROW 
INSERT INTO  `dvla_vehicle_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`registration`,
`registration_collapsed`,
`registration_validation_character`,
`vin`,
`vin_reversed`,
`vin_collapsed`,
`vin_collapsed_reversed`,
`model_code`,
`make_code`,
`make_in_full`,
`colour_1_code`,
`colour_2_code`,
`propulsion_code`,
`designed_gross_weight`,
`unladen_weight`,
`engine_number`,
`engine_capacity`,
`seating_capacity`,
`manufacture_date`,
`first_registration_date`,
`is_seriously_damaged`,
`recent_v5_document_number`,
`is_vehicle_new_at_first_registration`,
`body_type_code`,
`wheelplan_code`,
`sva_emission_standard`,
`ct_related_mark`,
`vehicle_id`,
`dvla_vehicle_id`,
`eu_classification`,
`mass_in_service_weight`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`registration`,
OLD.`registration_collapsed`,
OLD.`registration_validation_character`,
OLD.`vin`,
OLD.`vin_reversed`,
OLD.`vin_collapsed`,
OLD.`vin_collapsed_reversed`,
OLD.`model_code`,
OLD.`make_code`,
OLD.`make_in_full`,
OLD.`colour_1_code`,
OLD.`colour_2_code`,
OLD.`propulsion_code`,
OLD.`designed_gross_weight`,
OLD.`unladen_weight`,
OLD.`engine_number`,
OLD.`engine_capacity`,
OLD.`seating_capacity`,
OLD.`manufacture_date`,
OLD.`first_registration_date`,
OLD.`is_seriously_damaged`,
OLD.`recent_v5_document_number`,
OLD.`is_vehicle_new_at_first_registration`,
OLD.`body_type_code`,
OLD.`wheelplan_code`,
OLD.`sva_emission_standard`,
OLD.`ct_related_mark`,
OLD.`vehicle_id`,
OLD.`dvla_vehicle_id`,
OLD.`eu_classification`,
OLD.`mass_in_service_weight`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_dvla_vehicle_ad`;
CREATE TRIGGER `tr_dvla_vehicle_ad` AFTER DELETE
ON `dvla_vehicle` FOR EACH ROW 
INSERT INTO  `dvla_vehicle_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`registration`,
`registration_collapsed`,
`registration_validation_character`,
`vin`,
`vin_reversed`,
`vin_collapsed`,
`vin_collapsed_reversed`,
`model_code`,
`make_code`,
`make_in_full`,
`colour_1_code`,
`colour_2_code`,
`propulsion_code`,
`designed_gross_weight`,
`unladen_weight`,
`engine_number`,
`engine_capacity`,
`seating_capacity`,
`manufacture_date`,
`first_registration_date`,
`is_seriously_damaged`,
`recent_v5_document_number`,
`is_vehicle_new_at_first_registration`,
`body_type_code`,
`wheelplan_code`,
`sva_emission_standard`,
`ct_related_mark`,
`vehicle_id`,
`dvla_vehicle_id`,
`eu_classification`,
`mass_in_service_weight`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`registration`,
OLD.`registration_collapsed`,
OLD.`registration_validation_character`,
OLD.`vin`,
OLD.`vin_reversed`,
OLD.`vin_collapsed`,
OLD.`vin_collapsed_reversed`,
OLD.`model_code`,
OLD.`make_code`,
OLD.`make_in_full`,
OLD.`colour_1_code`,
OLD.`colour_2_code`,
OLD.`propulsion_code`,
OLD.`designed_gross_weight`,
OLD.`unladen_weight`,
OLD.`engine_number`,
OLD.`engine_capacity`,
OLD.`seating_capacity`,
OLD.`manufacture_date`,
OLD.`first_registration_date`,
OLD.`is_seriously_damaged`,
OLD.`recent_v5_document_number`,
OLD.`is_vehicle_new_at_first_registration`,
OLD.`body_type_code`,
OLD.`wheelplan_code`,
OLD.`sva_emission_standard`,
OLD.`ct_related_mark`,
OLD.`vehicle_id`,
OLD.`dvla_vehicle_id`,
OLD.`eu_classification`,
OLD.`mass_in_service_weight`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for email
CREATE TABLE  IF NOT EXISTS `email_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`contact_detail_id` int(10) unsigned,
`email` varchar(255),
`is_primary` tinyint(4) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_email (`id`,`version`),
  INDEX ix_email_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_email_ai`;
CREATE TRIGGER `tr_email_ai` AFTER INSERT
ON `email` FOR EACH ROW
INSERT INTO  `email_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_email_au`;
CREATE TRIGGER `tr_email_au` AFTER UPDATE
ON `email` FOR EACH ROW 
INSERT INTO  `email_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`contact_detail_id`,
`email`,
`is_primary`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`contact_detail_id`,
OLD.`email`,
OLD.`is_primary`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_email_ad`;
CREATE TRIGGER `tr_email_ad` AFTER DELETE
ON `email` FOR EACH ROW 
INSERT INTO  `email_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`contact_detail_id`,
`email`,
`is_primary`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`contact_detail_id`,
OLD.`email`,
OLD.`is_primary`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for emergency_reason_lookup
CREATE TABLE  IF NOT EXISTS `emergency_reason_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`description` varchar(50),
`display_order` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_emergency_reason_lookup (`id`,`version`),
  INDEX ix_emergency_reason_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_emergency_reason_lookup_ai`;
CREATE TRIGGER `tr_emergency_reason_lookup_ai` AFTER INSERT
ON `emergency_reason_lookup` FOR EACH ROW
INSERT INTO  `emergency_reason_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_emergency_reason_lookup_au`;
CREATE TRIGGER `tr_emergency_reason_lookup_au` AFTER UPDATE
ON `emergency_reason_lookup` FOR EACH ROW 
INSERT INTO  `emergency_reason_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`description`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`description`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_emergency_reason_lookup_ad`;
CREATE TRIGGER `tr_emergency_reason_lookup_ad` AFTER DELETE
ON `emergency_reason_lookup` FOR EACH ROW 
INSERT INTO  `emergency_reason_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`description`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`description`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for empty_vin_reason_lookup
CREATE TABLE  IF NOT EXISTS `empty_vin_reason_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`code` varchar(5),
`name` varchar(50),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_empty_vin_reason_lookup (`id`,`version`),
  INDEX ix_empty_vin_reason_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_empty_vin_reason_lookup_ai`;
CREATE TRIGGER `tr_empty_vin_reason_lookup_ai` AFTER INSERT
ON `empty_vin_reason_lookup` FOR EACH ROW
INSERT INTO  `empty_vin_reason_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_empty_vin_reason_lookup_au`;
CREATE TRIGGER `tr_empty_vin_reason_lookup_au` AFTER UPDATE
ON `empty_vin_reason_lookup` FOR EACH ROW 
INSERT INTO  `empty_vin_reason_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_empty_vin_reason_lookup_ad`;
CREATE TRIGGER `tr_empty_vin_reason_lookup_ad` AFTER DELETE
ON `empty_vin_reason_lookup` FOR EACH ROW 
INSERT INTO  `empty_vin_reason_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for empty_vrm_reason_lookup
CREATE TABLE  IF NOT EXISTS `empty_vrm_reason_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`code` varchar(5),
`name` varchar(50),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_empty_vrm_reason_lookup (`id`,`version`),
  INDEX ix_empty_vrm_reason_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_empty_vrm_reason_lookup_ai`;
CREATE TRIGGER `tr_empty_vrm_reason_lookup_ai` AFTER INSERT
ON `empty_vrm_reason_lookup` FOR EACH ROW
INSERT INTO  `empty_vrm_reason_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_empty_vrm_reason_lookup_au`;
CREATE TRIGGER `tr_empty_vrm_reason_lookup_au` AFTER UPDATE
ON `empty_vrm_reason_lookup` FOR EACH ROW 
INSERT INTO  `empty_vrm_reason_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_empty_vrm_reason_lookup_ad`;
CREATE TRIGGER `tr_empty_vrm_reason_lookup_ad` AFTER DELETE
ON `empty_vrm_reason_lookup` FOR EACH ROW 
INSERT INTO  `empty_vrm_reason_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for enforcement_condition_appointment_lookup
CREATE TABLE  IF NOT EXISTS `enforcement_condition_appointment_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`description` varchar(50),
`display_order` smallint(5) unsigned,
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_enforcement_condition_appointment_lookup (`id`,`version`),
  INDEX ix_enforcement_condition_appointment_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_enforcement_condition_appointment_lookup_ai`;
CREATE TRIGGER `tr_enforcement_condition_appointment_lookup_ai` AFTER INSERT
ON `enforcement_condition_appointment_lookup` FOR EACH ROW
INSERT INTO  `enforcement_condition_appointment_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_enforcement_condition_appointment_lookup_au`;
CREATE TRIGGER `tr_enforcement_condition_appointment_lookup_au` AFTER UPDATE
ON `enforcement_condition_appointment_lookup` FOR EACH ROW 
INSERT INTO  `enforcement_condition_appointment_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`description`,
`display_order`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`description`,
OLD.`display_order`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_enforcement_condition_appointment_lookup_ad`;
CREATE TRIGGER `tr_enforcement_condition_appointment_lookup_ad` AFTER DELETE
ON `enforcement_condition_appointment_lookup` FOR EACH ROW 
INSERT INTO  `enforcement_condition_appointment_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`description`,
`display_order`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`description`,
OLD.`display_order`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for enforcement_decision_category_lookup
CREATE TABLE  IF NOT EXISTS `enforcement_decision_category_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`category` varchar(50),
`display_order` smallint(5) unsigned,
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_enforcement_decision_category_lookup (`id`,`version`),
  INDEX ix_enforcement_decision_category_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_enforcement_decision_category_lookup_ai`;
CREATE TRIGGER `tr_enforcement_decision_category_lookup_ai` AFTER INSERT
ON `enforcement_decision_category_lookup` FOR EACH ROW
INSERT INTO  `enforcement_decision_category_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_enforcement_decision_category_lookup_au`;
CREATE TRIGGER `tr_enforcement_decision_category_lookup_au` AFTER UPDATE
ON `enforcement_decision_category_lookup` FOR EACH ROW 
INSERT INTO  `enforcement_decision_category_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`category`,
`display_order`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`category`,
OLD.`display_order`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_enforcement_decision_category_lookup_ad`;
CREATE TRIGGER `tr_enforcement_decision_category_lookup_ad` AFTER DELETE
ON `enforcement_decision_category_lookup` FOR EACH ROW 
INSERT INTO  `enforcement_decision_category_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`category`,
`display_order`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`category`,
OLD.`display_order`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for enforcement_decision_lookup
CREATE TABLE  IF NOT EXISTS `enforcement_decision_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`decision` varchar(100),
`display_order` smallint(5) unsigned,
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_enforcement_decision_lookup (`id`,`version`),
  INDEX ix_enforcement_decision_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_enforcement_decision_lookup_ai`;
CREATE TRIGGER `tr_enforcement_decision_lookup_ai` AFTER INSERT
ON `enforcement_decision_lookup` FOR EACH ROW
INSERT INTO  `enforcement_decision_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_enforcement_decision_lookup_au`;
CREATE TRIGGER `tr_enforcement_decision_lookup_au` AFTER UPDATE
ON `enforcement_decision_lookup` FOR EACH ROW 
INSERT INTO  `enforcement_decision_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`decision`,
`display_order`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`decision`,
OLD.`display_order`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_enforcement_decision_lookup_ad`;
CREATE TRIGGER `tr_enforcement_decision_lookup_ad` AFTER DELETE
ON `enforcement_decision_lookup` FOR EACH ROW 
INSERT INTO  `enforcement_decision_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`decision`,
`display_order`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`decision`,
OLD.`display_order`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for enforcement_decision_outcome_lookup
CREATE TABLE  IF NOT EXISTS `enforcement_decision_outcome_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`outcome` varchar(50),
`display_order` smallint(5) unsigned,
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_enforcement_decision_outcome_lookup (`id`,`version`),
  INDEX ix_enforcement_decision_outcome_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_enforcement_decision_outcome_lookup_ai`;
CREATE TRIGGER `tr_enforcement_decision_outcome_lookup_ai` AFTER INSERT
ON `enforcement_decision_outcome_lookup` FOR EACH ROW
INSERT INTO  `enforcement_decision_outcome_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_enforcement_decision_outcome_lookup_au`;
CREATE TRIGGER `tr_enforcement_decision_outcome_lookup_au` AFTER UPDATE
ON `enforcement_decision_outcome_lookup` FOR EACH ROW 
INSERT INTO  `enforcement_decision_outcome_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`outcome`,
`display_order`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`outcome`,
OLD.`display_order`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_enforcement_decision_outcome_lookup_ad`;
CREATE TRIGGER `tr_enforcement_decision_outcome_lookup_ad` AFTER DELETE
ON `enforcement_decision_outcome_lookup` FOR EACH ROW 
INSERT INTO  `enforcement_decision_outcome_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`outcome`,
`display_order`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`outcome`,
OLD.`display_order`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for enforcement_decision_reinspection_outcome_lookup
CREATE TABLE  IF NOT EXISTS `enforcement_decision_reinspection_outcome_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`decision` varchar(50),
`display_order` smallint(5) unsigned,
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_enforcement_decision_reinspection_outcome_lookup (`id`,`version`),
  INDEX ix_enforcement_decision_reinspection_outcome_lo_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_enforcement_decision_reinspection_outcome_lookup_ai`;
CREATE TRIGGER `tr_enforcement_decision_reinspection_outcome_lookup_ai` AFTER INSERT
ON `enforcement_decision_reinspection_outcome_lookup` FOR EACH ROW
INSERT INTO  `enforcement_decision_reinspection_outcome_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_enforcement_decision_reinspection_outcome_lookup_au`;
CREATE TRIGGER `tr_enforcement_decision_reinspection_outcome_lookup_au` AFTER UPDATE
ON `enforcement_decision_reinspection_outcome_lookup` FOR EACH ROW 
INSERT INTO  `enforcement_decision_reinspection_outcome_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`decision`,
`display_order`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`decision`,
OLD.`display_order`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_enforcement_decision_reinspection_outcome_lookup_ad`;
CREATE TRIGGER `tr_enforcement_decision_reinspection_outcome_lookup_ad` AFTER DELETE
ON `enforcement_decision_reinspection_outcome_lookup` FOR EACH ROW 
INSERT INTO  `enforcement_decision_reinspection_outcome_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`decision`,
`display_order`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`decision`,
OLD.`display_order`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for enforcement_decision_score_lookup
CREATE TABLE  IF NOT EXISTS `enforcement_decision_score_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`score` smallint(5) unsigned,
`description` varchar(50),
`display_order` smallint(5) unsigned,
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_enforcement_decision_score_lookup (`id`,`version`),
  INDEX ix_enforcement_decision_score_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_enforcement_decision_score_lookup_ai`;
CREATE TRIGGER `tr_enforcement_decision_score_lookup_ai` AFTER INSERT
ON `enforcement_decision_score_lookup` FOR EACH ROW
INSERT INTO  `enforcement_decision_score_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_enforcement_decision_score_lookup_au`;
CREATE TRIGGER `tr_enforcement_decision_score_lookup_au` AFTER UPDATE
ON `enforcement_decision_score_lookup` FOR EACH ROW 
INSERT INTO  `enforcement_decision_score_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`score`,
`description`,
`display_order`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`score`,
OLD.`description`,
OLD.`display_order`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_enforcement_decision_score_lookup_ad`;
CREATE TRIGGER `tr_enforcement_decision_score_lookup_ad` AFTER DELETE
ON `enforcement_decision_score_lookup` FOR EACH ROW 
INSERT INTO  `enforcement_decision_score_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`score`,
`description`,
`display_order`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`score`,
OLD.`description`,
OLD.`display_order`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for enforcement_fuel_type_lookup
CREATE TABLE  IF NOT EXISTS `enforcement_fuel_type_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`description` varchar(50),
`display_order` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_enforcement_fuel_type_lookup (`id`,`version`),
  INDEX ix_enforcement_fuel_type_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_enforcement_fuel_type_lookup_ai`;
CREATE TRIGGER `tr_enforcement_fuel_type_lookup_ai` AFTER INSERT
ON `enforcement_fuel_type_lookup` FOR EACH ROW
INSERT INTO  `enforcement_fuel_type_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_enforcement_fuel_type_lookup_au`;
CREATE TRIGGER `tr_enforcement_fuel_type_lookup_au` AFTER UPDATE
ON `enforcement_fuel_type_lookup` FOR EACH ROW 
INSERT INTO  `enforcement_fuel_type_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`description`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`description`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_enforcement_fuel_type_lookup_ad`;
CREATE TRIGGER `tr_enforcement_fuel_type_lookup_ad` AFTER DELETE
ON `enforcement_fuel_type_lookup` FOR EACH ROW 
INSERT INTO  `enforcement_fuel_type_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`description`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`description`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for enforcement_full_partial_retest_lookup
CREATE TABLE  IF NOT EXISTS `enforcement_full_partial_retest_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`description` varchar(50),
`code` varchar(5),
`display_order` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_enforcement_full_partial_retest_lookup (`id`,`version`),
  INDEX ix_enforcement_full_partial_retest_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_enforcement_full_partial_retest_lookup_ai`;
CREATE TRIGGER `tr_enforcement_full_partial_retest_lookup_ai` AFTER INSERT
ON `enforcement_full_partial_retest_lookup` FOR EACH ROW
INSERT INTO  `enforcement_full_partial_retest_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_enforcement_full_partial_retest_lookup_au`;
CREATE TRIGGER `tr_enforcement_full_partial_retest_lookup_au` AFTER UPDATE
ON `enforcement_full_partial_retest_lookup` FOR EACH ROW 
INSERT INTO  `enforcement_full_partial_retest_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`description`,
`code`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`description`,
OLD.`code`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_enforcement_full_partial_retest_lookup_ad`;
CREATE TRIGGER `tr_enforcement_full_partial_retest_lookup_ad` AFTER DELETE
ON `enforcement_full_partial_retest_lookup` FOR EACH ROW 
INSERT INTO  `enforcement_full_partial_retest_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`description`,
`code`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`description`,
OLD.`code`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for enforcement_mot_demo_test
CREATE TABLE  IF NOT EXISTS `enforcement_mot_demo_test_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`mot_test_id` bigint(20) unsigned,
`result_id` tinyint(4),
`is_satisfactory` tinyint(4),
`comment_id` bigint(20) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_enforcement_mot_demo_test (`id`,`version`),
  INDEX ix_enforcement_mot_demo_test_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_enforcement_mot_demo_test_ai`;
CREATE TRIGGER `tr_enforcement_mot_demo_test_ai` AFTER INSERT
ON `enforcement_mot_demo_test` FOR EACH ROW
INSERT INTO  `enforcement_mot_demo_test_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_enforcement_mot_demo_test_au`;
CREATE TRIGGER `tr_enforcement_mot_demo_test_au` AFTER UPDATE
ON `enforcement_mot_demo_test` FOR EACH ROW 
INSERT INTO  `enforcement_mot_demo_test_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`mot_test_id`,
`result_id`,
`is_satisfactory`,
`comment_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`mot_test_id`,
OLD.`result_id`,
OLD.`is_satisfactory`,
OLD.`comment_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_enforcement_mot_demo_test_ad`;
CREATE TRIGGER `tr_enforcement_mot_demo_test_ad` AFTER DELETE
ON `enforcement_mot_demo_test` FOR EACH ROW 
INSERT INTO  `enforcement_mot_demo_test_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`mot_test_id`,
`result_id`,
`is_satisfactory`,
`comment_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`mot_test_id`,
OLD.`result_id`,
OLD.`is_satisfactory`,
OLD.`comment_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for enforcement_mot_test_differences
CREATE TABLE  IF NOT EXISTS `enforcement_mot_test_differences_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`enforcement_mot_test_result_id` int(10) unsigned,
`rfr_id` int(10) unsigned,
`mot_test_id` bigint(20) unsigned,
`mot_test_rfr_map_id` bigint(20) unsigned,
`mot_test_type_id` smallint(5) unsigned,
`enforcement_decision_score_lookup_id` smallint(5) unsigned,
`enforcement_decision_lookup_id` smallint(5) unsigned,
`enforcement_decision_category_lookup_id` smallint(5) unsigned,
`comment_id` bigint(20) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_enforcement_mot_test_differences (`id`,`version`),
  INDEX ix_enforcement_mot_test_differences_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_enforcement_mot_test_differences_ai`;
CREATE TRIGGER `tr_enforcement_mot_test_differences_ai` AFTER INSERT
ON `enforcement_mot_test_differences` FOR EACH ROW
INSERT INTO  `enforcement_mot_test_differences_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_enforcement_mot_test_differences_au`;
CREATE TRIGGER `tr_enforcement_mot_test_differences_au` AFTER UPDATE
ON `enforcement_mot_test_differences` FOR EACH ROW 
INSERT INTO  `enforcement_mot_test_differences_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`enforcement_mot_test_result_id`,
`rfr_id`,
`mot_test_id`,
`mot_test_rfr_map_id`,
`mot_test_type_id`,
`enforcement_decision_score_lookup_id`,
`enforcement_decision_lookup_id`,
`enforcement_decision_category_lookup_id`,
`comment_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`enforcement_mot_test_result_id`,
OLD.`rfr_id`,
OLD.`mot_test_id`,
OLD.`mot_test_rfr_map_id`,
OLD.`mot_test_type_id`,
OLD.`enforcement_decision_score_lookup_id`,
OLD.`enforcement_decision_lookup_id`,
OLD.`enforcement_decision_category_lookup_id`,
OLD.`comment_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_enforcement_mot_test_differences_ad`;
CREATE TRIGGER `tr_enforcement_mot_test_differences_ad` AFTER DELETE
ON `enforcement_mot_test_differences` FOR EACH ROW 
INSERT INTO  `enforcement_mot_test_differences_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`enforcement_mot_test_result_id`,
`rfr_id`,
`mot_test_id`,
`mot_test_rfr_map_id`,
`mot_test_type_id`,
`enforcement_decision_score_lookup_id`,
`enforcement_decision_lookup_id`,
`enforcement_decision_category_lookup_id`,
`comment_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`enforcement_mot_test_result_id`,
OLD.`rfr_id`,
OLD.`mot_test_id`,
OLD.`mot_test_rfr_map_id`,
OLD.`mot_test_type_id`,
OLD.`enforcement_decision_score_lookup_id`,
OLD.`enforcement_decision_lookup_id`,
OLD.`enforcement_decision_category_lookup_id`,
OLD.`comment_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for enforcement_mot_test_result
CREATE TABLE  IF NOT EXISTS `enforcement_mot_test_result_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`re_inspection_mot_test_id` bigint(20) unsigned,
`mot_test_id` bigint(20) unsigned,
`total_score` smallint(5) unsigned,
`enforcement_decision_outcome_lookup_id` smallint(5) unsigned,
`comment_id` bigint(20) unsigned,
`step` varchar(100),
`enforcement_decision_reinspection_outcome_lookup_id` smallint(5) unsigned,
`awl_advice_given` varchar(255),
`awl_immediate_attention` varchar(255),
`awl_reply_comments` varchar(255),
`awl_name_a_ere` varchar(255),
`awl_mot_roles` varchar(255),
`awl_position_vts` varchar(255),
`awl_user_id` varchar(255),
`complainant_name` varchar(255),
`complaint_detail` varchar(255),
`repairs_detail` varchar(255),
`complainant_address` varchar(255),
`complainant_postcode` varchar(255),
`complainant_phone_number` varchar(255),
`ve_completed` varchar(255),
`agree_vehicle_to_certificate` varchar(255),
`input_agree_vehicle_to_certificate` varchar(255),
`agree_vehicle_to_fail` varchar(255),
`input_agree_vehicle_to_fail` varchar(255),
`vehicle_switch` varchar(255),
`input_vehicle_switch` varchar(255),
`switch_police_status_report` varchar(255),
`input_switch_detail_report` varchar(255),
`switch_vehicle_result` varchar(255),
`input_switch_police_status_report` varchar(255),
`promote_sale_interest` varchar(255),
`input_promote_sale_interest` varchar(255),
`vehicle_defects` varchar(255),
`reason_of_defects` varchar(255),
`items_discussed` varchar(255),
`concluding_remarks_tester` varchar(255),
`concluding_remarks_ae` varchar(4000),
`concluding_remarks_recommendation` varchar(4000),
`concluding_remarks_name` varchar(200),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_enforcement_mot_test_result (`id`,`version`),
  INDEX ix_enforcement_mot_test_result_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_enforcement_mot_test_result_ai`;
CREATE TRIGGER `tr_enforcement_mot_test_result_ai` AFTER INSERT
ON `enforcement_mot_test_result` FOR EACH ROW
INSERT INTO  `enforcement_mot_test_result_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_enforcement_mot_test_result_au`;
CREATE TRIGGER `tr_enforcement_mot_test_result_au` AFTER UPDATE
ON `enforcement_mot_test_result` FOR EACH ROW 
INSERT INTO  `enforcement_mot_test_result_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`re_inspection_mot_test_id`,
`mot_test_id`,
`total_score`,
`enforcement_decision_outcome_lookup_id`,
`comment_id`,
`step`,
`enforcement_decision_reinspection_outcome_lookup_id`,
`awl_advice_given`,
`awl_immediate_attention`,
`awl_reply_comments`,
`awl_name_a_ere`,
`awl_mot_roles`,
`awl_position_vts`,
`awl_user_id`,
`complainant_name`,
`complaint_detail`,
`repairs_detail`,
`complainant_address`,
`complainant_postcode`,
`complainant_phone_number`,
`ve_completed`,
`agree_vehicle_to_certificate`,
`input_agree_vehicle_to_certificate`,
`agree_vehicle_to_fail`,
`input_agree_vehicle_to_fail`,
`vehicle_switch`,
`input_vehicle_switch`,
`switch_police_status_report`,
`input_switch_detail_report`,
`switch_vehicle_result`,
`input_switch_police_status_report`,
`promote_sale_interest`,
`input_promote_sale_interest`,
`vehicle_defects`,
`reason_of_defects`,
`items_discussed`,
`concluding_remarks_tester`,
`concluding_remarks_ae`,
`concluding_remarks_recommendation`,
`concluding_remarks_name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`re_inspection_mot_test_id`,
OLD.`mot_test_id`,
OLD.`total_score`,
OLD.`enforcement_decision_outcome_lookup_id`,
OLD.`comment_id`,
OLD.`step`,
OLD.`enforcement_decision_reinspection_outcome_lookup_id`,
OLD.`awl_advice_given`,
OLD.`awl_immediate_attention`,
OLD.`awl_reply_comments`,
OLD.`awl_name_a_ere`,
OLD.`awl_mot_roles`,
OLD.`awl_position_vts`,
OLD.`awl_user_id`,
OLD.`complainant_name`,
OLD.`complaint_detail`,
OLD.`repairs_detail`,
OLD.`complainant_address`,
OLD.`complainant_postcode`,
OLD.`complainant_phone_number`,
OLD.`ve_completed`,
OLD.`agree_vehicle_to_certificate`,
OLD.`input_agree_vehicle_to_certificate`,
OLD.`agree_vehicle_to_fail`,
OLD.`input_agree_vehicle_to_fail`,
OLD.`vehicle_switch`,
OLD.`input_vehicle_switch`,
OLD.`switch_police_status_report`,
OLD.`input_switch_detail_report`,
OLD.`switch_vehicle_result`,
OLD.`input_switch_police_status_report`,
OLD.`promote_sale_interest`,
OLD.`input_promote_sale_interest`,
OLD.`vehicle_defects`,
OLD.`reason_of_defects`,
OLD.`items_discussed`,
OLD.`concluding_remarks_tester`,
OLD.`concluding_remarks_ae`,
OLD.`concluding_remarks_recommendation`,
OLD.`concluding_remarks_name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_enforcement_mot_test_result_ad`;
CREATE TRIGGER `tr_enforcement_mot_test_result_ad` AFTER DELETE
ON `enforcement_mot_test_result` FOR EACH ROW 
INSERT INTO  `enforcement_mot_test_result_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`re_inspection_mot_test_id`,
`mot_test_id`,
`total_score`,
`enforcement_decision_outcome_lookup_id`,
`comment_id`,
`step`,
`enforcement_decision_reinspection_outcome_lookup_id`,
`awl_advice_given`,
`awl_immediate_attention`,
`awl_reply_comments`,
`awl_name_a_ere`,
`awl_mot_roles`,
`awl_position_vts`,
`awl_user_id`,
`complainant_name`,
`complaint_detail`,
`repairs_detail`,
`complainant_address`,
`complainant_postcode`,
`complainant_phone_number`,
`ve_completed`,
`agree_vehicle_to_certificate`,
`input_agree_vehicle_to_certificate`,
`agree_vehicle_to_fail`,
`input_agree_vehicle_to_fail`,
`vehicle_switch`,
`input_vehicle_switch`,
`switch_police_status_report`,
`input_switch_detail_report`,
`switch_vehicle_result`,
`input_switch_police_status_report`,
`promote_sale_interest`,
`input_promote_sale_interest`,
`vehicle_defects`,
`reason_of_defects`,
`items_discussed`,
`concluding_remarks_tester`,
`concluding_remarks_ae`,
`concluding_remarks_recommendation`,
`concluding_remarks_name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`re_inspection_mot_test_id`,
OLD.`mot_test_id`,
OLD.`total_score`,
OLD.`enforcement_decision_outcome_lookup_id`,
OLD.`comment_id`,
OLD.`step`,
OLD.`enforcement_decision_reinspection_outcome_lookup_id`,
OLD.`awl_advice_given`,
OLD.`awl_immediate_attention`,
OLD.`awl_reply_comments`,
OLD.`awl_name_a_ere`,
OLD.`awl_mot_roles`,
OLD.`awl_position_vts`,
OLD.`awl_user_id`,
OLD.`complainant_name`,
OLD.`complaint_detail`,
OLD.`repairs_detail`,
OLD.`complainant_address`,
OLD.`complainant_postcode`,
OLD.`complainant_phone_number`,
OLD.`ve_completed`,
OLD.`agree_vehicle_to_certificate`,
OLD.`input_agree_vehicle_to_certificate`,
OLD.`agree_vehicle_to_fail`,
OLD.`input_agree_vehicle_to_fail`,
OLD.`vehicle_switch`,
OLD.`input_vehicle_switch`,
OLD.`switch_police_status_report`,
OLD.`input_switch_detail_report`,
OLD.`switch_vehicle_result`,
OLD.`input_switch_police_status_report`,
OLD.`promote_sale_interest`,
OLD.`input_promote_sale_interest`,
OLD.`vehicle_defects`,
OLD.`reason_of_defects`,
OLD.`items_discussed`,
OLD.`concluding_remarks_tester`,
OLD.`concluding_remarks_ae`,
OLD.`concluding_remarks_recommendation`,
OLD.`concluding_remarks_name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for enforcement_mot_test_result_witnesses
CREATE TABLE  IF NOT EXISTS `enforcement_mot_test_result_witnesses_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`name` varchar(255),
`position` varchar(255),
`enforcement_mot_test_result_id` int(10) unsigned,
`type` varchar(20),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_enforcement_mot_test_result_witnesses (`id`,`version`),
  INDEX ix_enforcement_mot_test_result_witnesses_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_enforcement_mot_test_result_witnesses_ai`;
CREATE TRIGGER `tr_enforcement_mot_test_result_witnesses_ai` AFTER INSERT
ON `enforcement_mot_test_result_witnesses` FOR EACH ROW
INSERT INTO  `enforcement_mot_test_result_witnesses_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_enforcement_mot_test_result_witnesses_au`;
CREATE TRIGGER `tr_enforcement_mot_test_result_witnesses_au` AFTER UPDATE
ON `enforcement_mot_test_result_witnesses` FOR EACH ROW 
INSERT INTO  `enforcement_mot_test_result_witnesses_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`position`,
`enforcement_mot_test_result_id`,
`type`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`position`,
OLD.`enforcement_mot_test_result_id`,
OLD.`type`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_enforcement_mot_test_result_witnesses_ad`;
CREATE TRIGGER `tr_enforcement_mot_test_result_witnesses_ad` AFTER DELETE
ON `enforcement_mot_test_result_witnesses` FOR EACH ROW 
INSERT INTO  `enforcement_mot_test_result_witnesses_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`position`,
`enforcement_mot_test_result_id`,
`type`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`position`,
OLD.`enforcement_mot_test_result_id`,
OLD.`type`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for enforcement_site_assessment
CREATE TABLE  IF NOT EXISTS `enforcement_site_assessment_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`site_id` int(10) unsigned,
`site_assessment_score` decimal(9,2),
`authorisation_for_authorised_examiner_id` int(11) unsigned,
`ae_representative_name` varchar(100),
`ae_representative_position` varchar(100),
`person_id` int(10) unsigned,
`visit_outcome_id` smallint(5) unsigned,
`advisory_issued` tinyint(4),
`visit_date` datetime(6),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_enforcement_site_assessment (`id`,`version`),
  INDEX ix_enforcement_site_assessment_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_enforcement_site_assessment_ai`;
CREATE TRIGGER `tr_enforcement_site_assessment_ai` AFTER INSERT
ON `enforcement_site_assessment` FOR EACH ROW
INSERT INTO  `enforcement_site_assessment_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_enforcement_site_assessment_au`;
CREATE TRIGGER `tr_enforcement_site_assessment_au` AFTER UPDATE
ON `enforcement_site_assessment` FOR EACH ROW 
INSERT INTO  `enforcement_site_assessment_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_id`,
`site_assessment_score`,
`authorisation_for_authorised_examiner_id`,
`ae_representative_name`,
`ae_representative_position`,
`person_id`,
`visit_outcome_id`,
`advisory_issued`,
`visit_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`site_id`,
OLD.`site_assessment_score`,
OLD.`authorisation_for_authorised_examiner_id`,
OLD.`ae_representative_name`,
OLD.`ae_representative_position`,
OLD.`person_id`,
OLD.`visit_outcome_id`,
OLD.`advisory_issued`,
OLD.`visit_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_enforcement_site_assessment_ad`;
CREATE TRIGGER `tr_enforcement_site_assessment_ad` AFTER DELETE
ON `enforcement_site_assessment` FOR EACH ROW 
INSERT INTO  `enforcement_site_assessment_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_id`,
`site_assessment_score`,
`authorisation_for_authorised_examiner_id`,
`ae_representative_name`,
`ae_representative_position`,
`person_id`,
`visit_outcome_id`,
`advisory_issued`,
`visit_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`site_id`,
OLD.`site_assessment_score`,
OLD.`authorisation_for_authorised_examiner_id`,
OLD.`ae_representative_name`,
OLD.`ae_representative_position`,
OLD.`person_id`,
OLD.`visit_outcome_id`,
OLD.`advisory_issued`,
OLD.`visit_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for enforcement_visit_outcome_lookup
CREATE TABLE  IF NOT EXISTS `enforcement_visit_outcome_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`description` varchar(50),
`display_order` smallint(5) unsigned,
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_enforcement_visit_outcome_lookup (`id`,`version`),
  INDEX ix_enforcement_visit_outcome_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_enforcement_visit_outcome_lookup_ai`;
CREATE TRIGGER `tr_enforcement_visit_outcome_lookup_ai` AFTER INSERT
ON `enforcement_visit_outcome_lookup` FOR EACH ROW
INSERT INTO  `enforcement_visit_outcome_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_enforcement_visit_outcome_lookup_au`;
CREATE TRIGGER `tr_enforcement_visit_outcome_lookup_au` AFTER UPDATE
ON `enforcement_visit_outcome_lookup` FOR EACH ROW 
INSERT INTO  `enforcement_visit_outcome_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`description`,
`display_order`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`description`,
OLD.`display_order`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_enforcement_visit_outcome_lookup_ad`;
CREATE TRIGGER `tr_enforcement_visit_outcome_lookup_ad` AFTER DELETE
ON `enforcement_visit_outcome_lookup` FOR EACH ROW 
INSERT INTO  `enforcement_visit_outcome_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`description`,
`display_order`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`description`,
OLD.`display_order`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for equipment
CREATE TABLE  IF NOT EXISTS `equipment_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`equipment_model_id` int(10) unsigned,
`site_id` int(10) unsigned,
`serial_number` varchar(50),
`date_added` datetime(6),
`date_removed` datetime(6),
`equipment_status_id` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_equipment (`id`,`version`),
  INDEX ix_equipment_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_equipment_ai`;
CREATE TRIGGER `tr_equipment_ai` AFTER INSERT
ON `equipment` FOR EACH ROW
INSERT INTO  `equipment_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_equipment_au`;
CREATE TRIGGER `tr_equipment_au` AFTER UPDATE
ON `equipment` FOR EACH ROW 
INSERT INTO  `equipment_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`equipment_model_id`,
`site_id`,
`serial_number`,
`date_added`,
`date_removed`,
`equipment_status_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`equipment_model_id`,
OLD.`site_id`,
OLD.`serial_number`,
OLD.`date_added`,
OLD.`date_removed`,
OLD.`equipment_status_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_equipment_ad`;
CREATE TRIGGER `tr_equipment_ad` AFTER DELETE
ON `equipment` FOR EACH ROW 
INSERT INTO  `equipment_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`equipment_model_id`,
`site_id`,
`serial_number`,
`date_added`,
`date_removed`,
`equipment_status_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`equipment_model_id`,
OLD.`site_id`,
OLD.`serial_number`,
OLD.`date_added`,
OLD.`date_removed`,
OLD.`equipment_status_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for equipment_make
CREATE TABLE  IF NOT EXISTS `equipment_make_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`code` varchar(5),
`name` varchar(100),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_equipment_make (`id`,`version`),
  INDEX ix_equipment_make_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_equipment_make_ai`;
CREATE TRIGGER `tr_equipment_make_ai` AFTER INSERT
ON `equipment_make` FOR EACH ROW
INSERT INTO  `equipment_make_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_equipment_make_au`;
CREATE TRIGGER `tr_equipment_make_au` AFTER UPDATE
ON `equipment_make` FOR EACH ROW 
INSERT INTO  `equipment_make_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_equipment_make_ad`;
CREATE TRIGGER `tr_equipment_make_ad` AFTER DELETE
ON `equipment_make` FOR EACH ROW 
INSERT INTO  `equipment_make_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for equipment_model
CREATE TABLE  IF NOT EXISTS `equipment_model_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`code` varchar(8),
`name` varchar(100),
`equipment_identification_number` varchar(25),
`equipment_make_id` int(10) unsigned,
`equipment_type_id` int(10) unsigned,
`software_version` varchar(20),
`certified` date,
`last_used_date` datetime(6),
`last_installable_date` datetime(6),
`equipment_model_status_id` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_equipment_model (`id`,`version`),
  INDEX ix_equipment_model_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_equipment_model_ai`;
CREATE TRIGGER `tr_equipment_model_ai` AFTER INSERT
ON `equipment_model` FOR EACH ROW
INSERT INTO  `equipment_model_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_equipment_model_au`;
CREATE TRIGGER `tr_equipment_model_au` AFTER UPDATE
ON `equipment_model` FOR EACH ROW 
INSERT INTO  `equipment_model_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`equipment_identification_number`,
`equipment_make_id`,
`equipment_type_id`,
`software_version`,
`certified`,
`last_used_date`,
`last_installable_date`,
`equipment_model_status_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`equipment_identification_number`,
OLD.`equipment_make_id`,
OLD.`equipment_type_id`,
OLD.`software_version`,
OLD.`certified`,
OLD.`last_used_date`,
OLD.`last_installable_date`,
OLD.`equipment_model_status_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_equipment_model_ad`;
CREATE TRIGGER `tr_equipment_model_ad` AFTER DELETE
ON `equipment_model` FOR EACH ROW 
INSERT INTO  `equipment_model_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`equipment_identification_number`,
`equipment_make_id`,
`equipment_type_id`,
`software_version`,
`certified`,
`last_used_date`,
`last_installable_date`,
`equipment_model_status_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`equipment_identification_number`,
OLD.`equipment_make_id`,
OLD.`equipment_type_id`,
OLD.`software_version`,
OLD.`certified`,
OLD.`last_used_date`,
OLD.`last_installable_date`,
OLD.`equipment_model_status_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for equipment_model_status
CREATE TABLE  IF NOT EXISTS `equipment_model_status_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(30),
`code` varchar(5),
`description` varchar(100),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_equipment_model_status (`id`,`version`),
  INDEX ix_equipment_model_status_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_equipment_model_status_ai`;
CREATE TRIGGER `tr_equipment_model_status_ai` AFTER INSERT
ON `equipment_model_status` FOR EACH ROW
INSERT INTO  `equipment_model_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_equipment_model_status_au`;
CREATE TRIGGER `tr_equipment_model_status_au` AFTER UPDATE
ON `equipment_model_status` FOR EACH ROW 
INSERT INTO  `equipment_model_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`description`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`description`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_equipment_model_status_ad`;
CREATE TRIGGER `tr_equipment_model_status_ad` AFTER DELETE
ON `equipment_model_status` FOR EACH ROW 
INSERT INTO  `equipment_model_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`description`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`description`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for equipment_model_vehicle_class_link
CREATE TABLE  IF NOT EXISTS `equipment_model_vehicle_class_link_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`vehicle_class_id` smallint(5) unsigned,
`equipment_model_id` int(10) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_equipment_model_vehicle_class_link (`id`,`version`),
  INDEX ix_equipment_model_vehicle_class_link_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_equipment_model_vehicle_class_link_ai`;
CREATE TRIGGER `tr_equipment_model_vehicle_class_link_ai` AFTER INSERT
ON `equipment_model_vehicle_class_link` FOR EACH ROW
INSERT INTO  `equipment_model_vehicle_class_link_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_equipment_model_vehicle_class_link_au`;
CREATE TRIGGER `tr_equipment_model_vehicle_class_link_au` AFTER UPDATE
ON `equipment_model_vehicle_class_link` FOR EACH ROW 
INSERT INTO  `equipment_model_vehicle_class_link_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`vehicle_class_id`,
`equipment_model_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`vehicle_class_id`,
OLD.`equipment_model_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_equipment_model_vehicle_class_link_ad`;
CREATE TRIGGER `tr_equipment_model_vehicle_class_link_ad` AFTER DELETE
ON `equipment_model_vehicle_class_link` FOR EACH ROW 
INSERT INTO  `equipment_model_vehicle_class_link_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`vehicle_class_id`,
`equipment_model_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`vehicle_class_id`,
OLD.`equipment_model_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for equipment_status
CREATE TABLE  IF NOT EXISTS `equipment_status_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(30),
`code` varchar(5),
`description` varchar(100),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_equipment_status (`id`,`version`),
  INDEX ix_equipment_status_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_equipment_status_ai`;
CREATE TRIGGER `tr_equipment_status_ai` AFTER INSERT
ON `equipment_status` FOR EACH ROW
INSERT INTO  `equipment_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_equipment_status_au`;
CREATE TRIGGER `tr_equipment_status_au` AFTER UPDATE
ON `equipment_status` FOR EACH ROW 
INSERT INTO  `equipment_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`description`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`description`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_equipment_status_ad`;
CREATE TRIGGER `tr_equipment_status_ad` AFTER DELETE
ON `equipment_status` FOR EACH ROW 
INSERT INTO  `equipment_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`description`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`description`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for equipment_type
CREATE TABLE  IF NOT EXISTS `equipment_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`code` varchar(5),
`name` varchar(50),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_equipment_type (`id`,`version`),
  INDEX ix_equipment_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_equipment_type_ai`;
CREATE TRIGGER `tr_equipment_type_ai` AFTER INSERT
ON `equipment_type` FOR EACH ROW
INSERT INTO  `equipment_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_equipment_type_au`;
CREATE TRIGGER `tr_equipment_type_au` AFTER UPDATE
ON `equipment_type` FOR EACH ROW 
INSERT INTO  `equipment_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_equipment_type_ad`;
CREATE TRIGGER `tr_equipment_type_ad` AFTER DELETE
ON `equipment_type` FOR EACH ROW 
INSERT INTO  `equipment_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for event
CREATE TABLE  IF NOT EXISTS `event_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`event_type_id` smallint(5) unsigned,
`event_outcome_id` smallint(5) unsigned,
`description` varchar(250),
`comment_id` bigint(20) unsigned,
`is_manual_event` tinyint(4),
`event_date` datetime(6),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_event (`id`,`version`),
  INDEX ix_event_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_event_ai`;
CREATE TRIGGER `tr_event_ai` AFTER INSERT
ON `event` FOR EACH ROW
INSERT INTO  `event_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_event_au`;
CREATE TRIGGER `tr_event_au` AFTER UPDATE
ON `event` FOR EACH ROW 
INSERT INTO  `event_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`event_type_id`,
`event_outcome_id`,
`description`,
`comment_id`,
`is_manual_event`,
`event_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`event_type_id`,
OLD.`event_outcome_id`,
OLD.`description`,
OLD.`comment_id`,
OLD.`is_manual_event`,
OLD.`event_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_event_ad`;
CREATE TRIGGER `tr_event_ad` AFTER DELETE
ON `event` FOR EACH ROW 
INSERT INTO  `event_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`event_type_id`,
`event_outcome_id`,
`description`,
`comment_id`,
`is_manual_event`,
`event_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`event_type_id`,
OLD.`event_outcome_id`,
OLD.`description`,
OLD.`comment_id`,
OLD.`is_manual_event`,
OLD.`event_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for event_category_lookup
CREATE TABLE  IF NOT EXISTS `event_category_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`code` varchar(5),
`name` varchar(50),
`description` varchar(250),
`display_order` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_event_category_lookup (`id`,`version`),
  INDEX ix_event_category_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_event_category_lookup_ai`;
CREATE TRIGGER `tr_event_category_lookup_ai` AFTER INSERT
ON `event_category_lookup` FOR EACH ROW
INSERT INTO  `event_category_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_event_category_lookup_au`;
CREATE TRIGGER `tr_event_category_lookup_au` AFTER UPDATE
ON `event_category_lookup` FOR EACH ROW 
INSERT INTO  `event_category_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`description`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`description`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_event_category_lookup_ad`;
CREATE TRIGGER `tr_event_category_lookup_ad` AFTER DELETE
ON `event_category_lookup` FOR EACH ROW 
INSERT INTO  `event_category_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`description`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`description`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for event_organisation_map
CREATE TABLE  IF NOT EXISTS `event_organisation_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`event_id` int(10) unsigned,
`organisation_id` int(10) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_event_organisation_map (`id`,`version`),
  INDEX ix_event_organisation_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_event_organisation_map_ai`;
CREATE TRIGGER `tr_event_organisation_map_ai` AFTER INSERT
ON `event_organisation_map` FOR EACH ROW
INSERT INTO  `event_organisation_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_event_organisation_map_au`;
CREATE TRIGGER `tr_event_organisation_map_au` AFTER UPDATE
ON `event_organisation_map` FOR EACH ROW 
INSERT INTO  `event_organisation_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`event_id`,
`organisation_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`event_id`,
OLD.`organisation_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_event_organisation_map_ad`;
CREATE TRIGGER `tr_event_organisation_map_ad` AFTER DELETE
ON `event_organisation_map` FOR EACH ROW 
INSERT INTO  `event_organisation_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`event_id`,
`organisation_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`event_id`,
OLD.`organisation_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for event_outcome_lookup
CREATE TABLE  IF NOT EXISTS `event_outcome_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`code` varchar(5),
`name` varchar(100),
`display_order` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_event_outcome_lookup (`id`,`version`),
  INDEX ix_event_outcome_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_event_outcome_lookup_ai`;
CREATE TRIGGER `tr_event_outcome_lookup_ai` AFTER INSERT
ON `event_outcome_lookup` FOR EACH ROW
INSERT INTO  `event_outcome_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_event_outcome_lookup_au`;
CREATE TRIGGER `tr_event_outcome_lookup_au` AFTER UPDATE
ON `event_outcome_lookup` FOR EACH ROW 
INSERT INTO  `event_outcome_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_event_outcome_lookup_ad`;
CREATE TRIGGER `tr_event_outcome_lookup_ad` AFTER DELETE
ON `event_outcome_lookup` FOR EACH ROW 
INSERT INTO  `event_outcome_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for event_person_map
CREATE TABLE  IF NOT EXISTS `event_person_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`event_id` int(10) unsigned,
`person_id` int(10) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_event_person_map (`id`,`version`),
  INDEX ix_event_person_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_event_person_map_ai`;
CREATE TRIGGER `tr_event_person_map_ai` AFTER INSERT
ON `event_person_map` FOR EACH ROW
INSERT INTO  `event_person_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_event_person_map_au`;
CREATE TRIGGER `tr_event_person_map_au` AFTER UPDATE
ON `event_person_map` FOR EACH ROW 
INSERT INTO  `event_person_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`event_id`,
`person_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`event_id`,
OLD.`person_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_event_person_map_ad`;
CREATE TRIGGER `tr_event_person_map_ad` AFTER DELETE
ON `event_person_map` FOR EACH ROW 
INSERT INTO  `event_person_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`event_id`,
`person_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`event_id`,
OLD.`person_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for event_site_map
CREATE TABLE  IF NOT EXISTS `event_site_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`event_id` int(10) unsigned,
`site_id` int(10) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_event_site_map (`id`,`version`),
  INDEX ix_event_site_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_event_site_map_ai`;
CREATE TRIGGER `tr_event_site_map_ai` AFTER INSERT
ON `event_site_map` FOR EACH ROW
INSERT INTO  `event_site_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_event_site_map_au`;
CREATE TRIGGER `tr_event_site_map_au` AFTER UPDATE
ON `event_site_map` FOR EACH ROW 
INSERT INTO  `event_site_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`event_id`,
`site_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`event_id`,
OLD.`site_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_event_site_map_ad`;
CREATE TRIGGER `tr_event_site_map_ad` AFTER DELETE
ON `event_site_map` FOR EACH ROW 
INSERT INTO  `event_site_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`event_id`,
`site_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`event_id`,
OLD.`site_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for event_type_lookup
CREATE TABLE  IF NOT EXISTS `event_type_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`code` varchar(5),
`name` varchar(50),
`display_order` smallint(5) unsigned,
`start_date` date,
`end_date` date,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_event_type_lookup (`id`,`version`),
  INDEX ix_event_type_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_event_type_lookup_ai`;
CREATE TRIGGER `tr_event_type_lookup_ai` AFTER INSERT
ON `event_type_lookup` FOR EACH ROW
INSERT INTO  `event_type_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_event_type_lookup_au`;
CREATE TRIGGER `tr_event_type_lookup_au` AFTER UPDATE
ON `event_type_lookup` FOR EACH ROW 
INSERT INTO  `event_type_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`display_order`,
`start_date`,
`end_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`display_order`,
OLD.`start_date`,
OLD.`end_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_event_type_lookup_ad`;
CREATE TRIGGER `tr_event_type_lookup_ad` AFTER DELETE
ON `event_type_lookup` FOR EACH ROW 
INSERT INTO  `event_type_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`display_order`,
`start_date`,
`end_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`display_order`,
OLD.`start_date`,
OLD.`end_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for event_type_outcome_category_map
CREATE TABLE  IF NOT EXISTS `event_type_outcome_category_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`event_type_id` smallint(5) unsigned,
`event_outcome_id` smallint(5) unsigned,
`event_category_id` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_event_type_outcome_category_map (`id`,`version`),
  INDEX ix_event_type_outcome_category_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_event_type_outcome_category_map_ai`;
CREATE TRIGGER `tr_event_type_outcome_category_map_ai` AFTER INSERT
ON `event_type_outcome_category_map` FOR EACH ROW
INSERT INTO  `event_type_outcome_category_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_event_type_outcome_category_map_au`;
CREATE TRIGGER `tr_event_type_outcome_category_map_au` AFTER UPDATE
ON `event_type_outcome_category_map` FOR EACH ROW 
INSERT INTO  `event_type_outcome_category_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`event_type_id`,
`event_outcome_id`,
`event_category_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`event_type_id`,
OLD.`event_outcome_id`,
OLD.`event_category_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_event_type_outcome_category_map_ad`;
CREATE TRIGGER `tr_event_type_outcome_category_map_ad` AFTER DELETE
ON `event_type_outcome_category_map` FOR EACH ROW 
INSERT INTO  `event_type_outcome_category_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`event_type_id`,
`event_outcome_id`,
`event_category_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`event_type_id`,
OLD.`event_outcome_id`,
OLD.`event_category_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for evidence
CREATE TABLE  IF NOT EXISTS `evidence_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`document_type_id` smallint(5) unsigned,
`document_ref` varchar(45),
`method_of_delivery_id` smallint(5) unsigned zerofill,
`recieved_on` datetime(6),
`status_id` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_evidence (`id`,`version`),
  INDEX ix_evidence_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_evidence_ai`;
CREATE TRIGGER `tr_evidence_ai` AFTER INSERT
ON `evidence` FOR EACH ROW
INSERT INTO  `evidence_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_evidence_au`;
CREATE TRIGGER `tr_evidence_au` AFTER UPDATE
ON `evidence` FOR EACH ROW 
INSERT INTO  `evidence_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`document_type_id`,
`document_ref`,
`method_of_delivery_id`,
`recieved_on`,
`status_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`document_type_id`,
OLD.`document_ref`,
OLD.`method_of_delivery_id`,
OLD.`recieved_on`,
OLD.`status_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_evidence_ad`;
CREATE TRIGGER `tr_evidence_ad` AFTER DELETE
ON `evidence` FOR EACH ROW 
INSERT INTO  `evidence_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`document_type_id`,
`document_ref`,
`method_of_delivery_id`,
`recieved_on`,
`status_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`document_type_id`,
OLD.`document_ref`,
OLD.`method_of_delivery_id`,
OLD.`recieved_on`,
OLD.`status_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for experience
CREATE TABLE  IF NOT EXISTS `experience_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`employer` varchar(100),
`description` varchar(255),
`person_id` int(10) unsigned,
`date_from` datetime(6),
`date_to` datetime(6),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_experience (`id`,`version`),
  INDEX ix_experience_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_experience_ai`;
CREATE TRIGGER `tr_experience_ai` AFTER INSERT
ON `experience` FOR EACH ROW
INSERT INTO  `experience_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_experience_au`;
CREATE TRIGGER `tr_experience_au` AFTER UPDATE
ON `experience` FOR EACH ROW 
INSERT INTO  `experience_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`employer`,
`description`,
`person_id`,
`date_from`,
`date_to`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`employer`,
OLD.`description`,
OLD.`person_id`,
OLD.`date_from`,
OLD.`date_to`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_experience_ad`;
CREATE TRIGGER `tr_experience_ad` AFTER DELETE
ON `experience` FOR EACH ROW 
INSERT INTO  `experience_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`employer`,
`description`,
`person_id`,
`date_from`,
`date_to`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`employer`,
OLD.`description`,
OLD.`person_id`,
OLD.`date_from`,
OLD.`date_to`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for facility_type
CREATE TABLE  IF NOT EXISTS `facility_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_facility_type (`id`,`version`),
  INDEX ix_facility_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_facility_type_ai`;
CREATE TRIGGER `tr_facility_type_ai` AFTER INSERT
ON `facility_type` FOR EACH ROW
INSERT INTO  `facility_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_facility_type_au`;
CREATE TRIGGER `tr_facility_type_au` AFTER UPDATE
ON `facility_type` FOR EACH ROW 
INSERT INTO  `facility_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_facility_type_ad`;
CREATE TRIGGER `tr_facility_type_ad` AFTER DELETE
ON `facility_type` FOR EACH ROW 
INSERT INTO  `facility_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for failure_location_lookup
CREATE TABLE  IF NOT EXISTS `failure_location_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_failure_location_lookup (`id`,`version`),
  INDEX ix_failure_location_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_failure_location_lookup_ai`;
CREATE TRIGGER `tr_failure_location_lookup_ai` AFTER INSERT
ON `failure_location_lookup` FOR EACH ROW
INSERT INTO  `failure_location_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_failure_location_lookup_au`;
CREATE TRIGGER `tr_failure_location_lookup_au` AFTER UPDATE
ON `failure_location_lookup` FOR EACH ROW 
INSERT INTO  `failure_location_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_failure_location_lookup_ad`;
CREATE TRIGGER `tr_failure_location_lookup_ad` AFTER DELETE
ON `failure_location_lookup` FOR EACH ROW 
INSERT INTO  `failure_location_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for fuel_type
CREATE TABLE  IF NOT EXISTS `fuel_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`dvla_propulsion_code` varchar(2),
`display_order` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_fuel_type (`id`,`version`),
  INDEX ix_fuel_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_fuel_type_ai`;
CREATE TRIGGER `tr_fuel_type_ai` AFTER INSERT
ON `fuel_type` FOR EACH ROW
INSERT INTO  `fuel_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_fuel_type_au`;
CREATE TRIGGER `tr_fuel_type_au` AFTER UPDATE
ON `fuel_type` FOR EACH ROW 
INSERT INTO  `fuel_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`dvla_propulsion_code`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`dvla_propulsion_code`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_fuel_type_ad`;
CREATE TRIGGER `tr_fuel_type_ad` AFTER DELETE
ON `fuel_type` FOR EACH ROW 
INSERT INTO  `fuel_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`dvla_propulsion_code`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`dvla_propulsion_code`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for gender
CREATE TABLE  IF NOT EXISTS `gender_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_gender (`id`,`version`),
  INDEX ix_gender_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_gender_ai`;
CREATE TRIGGER `tr_gender_ai` AFTER INSERT
ON `gender` FOR EACH ROW
INSERT INTO  `gender_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_gender_au`;
CREATE TRIGGER `tr_gender_au` AFTER UPDATE
ON `gender` FOR EACH ROW 
INSERT INTO  `gender_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_gender_ad`;
CREATE TRIGGER `tr_gender_ad` AFTER DELETE
ON `gender` FOR EACH ROW 
INSERT INTO  `gender_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for identifying_token
CREATE TABLE  IF NOT EXISTS `identifying_token_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`name` varchar(50),
`serial_number` varchar(20),
`token_lookup_id` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_identifying_token (`id`,`version`),
  INDEX ix_identifying_token_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_identifying_token_ai`;
CREATE TRIGGER `tr_identifying_token_ai` AFTER INSERT
ON `identifying_token` FOR EACH ROW
INSERT INTO  `identifying_token_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_identifying_token_au`;
CREATE TRIGGER `tr_identifying_token_au` AFTER UPDATE
ON `identifying_token` FOR EACH ROW 
INSERT INTO  `identifying_token_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`serial_number`,
`token_lookup_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`serial_number`,
OLD.`token_lookup_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_identifying_token_ad`;
CREATE TRIGGER `tr_identifying_token_ad` AFTER DELETE
ON `identifying_token` FOR EACH ROW 
INSERT INTO  `identifying_token_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`serial_number`,
`token_lookup_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`serial_number`,
OLD.`token_lookup_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for incognito_vehicle
CREATE TABLE  IF NOT EXISTS `incognito_vehicle_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`vehicle_id` int(10) unsigned,
`start_date` date,
`end_date` date,
`test_date` date,
`expiry_date` date,
`site_id` int(10) unsigned,
`person_id` int(10) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_incognito_vehicle (`id`,`version`),
  INDEX ix_incognito_vehicle_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_incognito_vehicle_ai`;
CREATE TRIGGER `tr_incognito_vehicle_ai` AFTER INSERT
ON `incognito_vehicle` FOR EACH ROW
INSERT INTO  `incognito_vehicle_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_incognito_vehicle_au`;
CREATE TRIGGER `tr_incognito_vehicle_au` AFTER UPDATE
ON `incognito_vehicle` FOR EACH ROW 
INSERT INTO  `incognito_vehicle_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`vehicle_id`,
`start_date`,
`end_date`,
`test_date`,
`expiry_date`,
`site_id`,
`person_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`vehicle_id`,
OLD.`start_date`,
OLD.`end_date`,
OLD.`test_date`,
OLD.`expiry_date`,
OLD.`site_id`,
OLD.`person_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_incognito_vehicle_ad`;
CREATE TRIGGER `tr_incognito_vehicle_ad` AFTER DELETE
ON `incognito_vehicle` FOR EACH ROW 
INSERT INTO  `incognito_vehicle_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`vehicle_id`,
`start_date`,
`end_date`,
`test_date`,
`expiry_date`,
`site_id`,
`person_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`vehicle_id`,
OLD.`start_date`,
OLD.`end_date`,
OLD.`test_date`,
OLD.`expiry_date`,
OLD.`site_id`,
OLD.`person_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for jasper_document
CREATE TABLE  IF NOT EXISTS `jasper_document_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` bigint(20) unsigned,
`template_id` int(10) unsigned,
`document_content` text,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_jasper_document (`id`,`version`),
  INDEX ix_jasper_document_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_jasper_document_ai`;
CREATE TRIGGER `tr_jasper_document_ai` AFTER INSERT
ON `jasper_document` FOR EACH ROW
INSERT INTO  `jasper_document_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_jasper_document_au`;
CREATE TRIGGER `tr_jasper_document_au` AFTER UPDATE
ON `jasper_document` FOR EACH ROW 
INSERT INTO  `jasper_document_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`template_id`,
`document_content`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`template_id`,
OLD.`document_content`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_jasper_document_ad`;
CREATE TRIGGER `tr_jasper_document_ad` AFTER DELETE
ON `jasper_document` FOR EACH ROW 
INSERT INTO  `jasper_document_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`template_id`,
`document_content`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`template_id`,
OLD.`document_content`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for jasper_hard_copy
CREATE TABLE  IF NOT EXISTS `jasper_hard_copy_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` bigint(20) unsigned,
`document_id` bigint(20) unsigned,
`file_path` varchar(255),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_jasper_hard_copy (`id`,`version`),
  INDEX ix_jasper_hard_copy_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_jasper_hard_copy_ai`;
CREATE TRIGGER `tr_jasper_hard_copy_ai` AFTER INSERT
ON `jasper_hard_copy` FOR EACH ROW
INSERT INTO  `jasper_hard_copy_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_jasper_hard_copy_au`;
CREATE TRIGGER `tr_jasper_hard_copy_au` AFTER UPDATE
ON `jasper_hard_copy` FOR EACH ROW 
INSERT INTO  `jasper_hard_copy_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`document_id`,
`file_path`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`document_id`,
OLD.`file_path`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_jasper_hard_copy_ad`;
CREATE TRIGGER `tr_jasper_hard_copy_ad` AFTER DELETE
ON `jasper_hard_copy` FOR EACH ROW 
INSERT INTO  `jasper_hard_copy_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`document_id`,
`file_path`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`document_id`,
OLD.`file_path`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for jasper_template
CREATE TABLE  IF NOT EXISTS `jasper_template_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`template_type_id` int(10) unsigned,
`jasper_report_name` varchar(255),
`is_active` tinyint(4),
`active_from` datetime(6),
`active_to` datetime(6),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_jasper_template (`id`,`version`),
  INDEX ix_jasper_template_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_jasper_template_ai`;
CREATE TRIGGER `tr_jasper_template_ai` AFTER INSERT
ON `jasper_template` FOR EACH ROW
INSERT INTO  `jasper_template_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_jasper_template_au`;
CREATE TRIGGER `tr_jasper_template_au` AFTER UPDATE
ON `jasper_template` FOR EACH ROW 
INSERT INTO  `jasper_template_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`template_type_id`,
`jasper_report_name`,
`is_active`,
`active_from`,
`active_to`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`template_type_id`,
OLD.`jasper_report_name`,
OLD.`is_active`,
OLD.`active_from`,
OLD.`active_to`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_jasper_template_ad`;
CREATE TRIGGER `tr_jasper_template_ad` AFTER DELETE
ON `jasper_template` FOR EACH ROW 
INSERT INTO  `jasper_template_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`template_type_id`,
`jasper_report_name`,
`is_active`,
`active_from`,
`active_to`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`template_type_id`,
OLD.`jasper_report_name`,
OLD.`is_active`,
OLD.`active_from`,
OLD.`active_to`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for jasper_template_type
CREATE TABLE  IF NOT EXISTS `jasper_template_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`name` varchar(255),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_jasper_template_type (`id`,`version`),
  INDEX ix_jasper_template_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_jasper_template_type_ai`;
CREATE TRIGGER `tr_jasper_template_type_ai` AFTER INSERT
ON `jasper_template_type` FOR EACH ROW
INSERT INTO  `jasper_template_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_jasper_template_type_au`;
CREATE TRIGGER `tr_jasper_template_type_au` AFTER UPDATE
ON `jasper_template_type` FOR EACH ROW 
INSERT INTO  `jasper_template_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_jasper_template_type_ad`;
CREATE TRIGGER `tr_jasper_template_type_ad` AFTER DELETE
ON `jasper_template_type` FOR EACH ROW 
INSERT INTO  `jasper_template_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for jasper_template_variation
CREATE TABLE  IF NOT EXISTS `jasper_template_variation_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`template_id` int(10) unsigned,
`name` varchar(255),
`jasper_report_name` varchar(255),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_jasper_template_variation (`id`,`version`),
  INDEX ix_jasper_template_variation_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_jasper_template_variation_ai`;
CREATE TRIGGER `tr_jasper_template_variation_ai` AFTER INSERT
ON `jasper_template_variation` FOR EACH ROW
INSERT INTO  `jasper_template_variation_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_jasper_template_variation_au`;
CREATE TRIGGER `tr_jasper_template_variation_au` AFTER UPDATE
ON `jasper_template_variation` FOR EACH ROW 
INSERT INTO  `jasper_template_variation_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`template_id`,
`name`,
`jasper_report_name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`template_id`,
OLD.`name`,
OLD.`jasper_report_name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_jasper_template_variation_ad`;
CREATE TRIGGER `tr_jasper_template_variation_ad` AFTER DELETE
ON `jasper_template_variation` FOR EACH ROW 
INSERT INTO  `jasper_template_variation_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`template_id`,
`name`,
`jasper_report_name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`template_id`,
OLD.`name`,
OLD.`jasper_report_name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for language_type
CREATE TABLE  IF NOT EXISTS `language_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_language_type (`id`,`version`),
  INDEX ix_language_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_language_type_ai`;
CREATE TRIGGER `tr_language_type_ai` AFTER INSERT
ON `language_type` FOR EACH ROW
INSERT INTO  `language_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_language_type_au`;
CREATE TRIGGER `tr_language_type_au` AFTER UPDATE
ON `language_type` FOR EACH ROW 
INSERT INTO  `language_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_language_type_ad`;
CREATE TRIGGER `tr_language_type_ad` AFTER DELETE
ON `language_type` FOR EACH ROW 
INSERT INTO  `language_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for licence
CREATE TABLE  IF NOT EXISTS `licence_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`licence_number` varchar(45),
`licence_type_id` smallint(5) unsigned,
`country_lookup_id` smallint(5) unsigned,
`valid_from` datetime(6),
`expiry_date` datetime(6),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_licence (`id`,`version`),
  INDEX ix_licence_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_licence_ai`;
CREATE TRIGGER `tr_licence_ai` AFTER INSERT
ON `licence` FOR EACH ROW
INSERT INTO  `licence_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_licence_au`;
CREATE TRIGGER `tr_licence_au` AFTER UPDATE
ON `licence` FOR EACH ROW 
INSERT INTO  `licence_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`licence_number`,
`licence_type_id`,
`country_lookup_id`,
`valid_from`,
`expiry_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`licence_number`,
OLD.`licence_type_id`,
OLD.`country_lookup_id`,
OLD.`valid_from`,
OLD.`expiry_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_licence_ad`;
CREATE TRIGGER `tr_licence_ad` AFTER DELETE
ON `licence` FOR EACH ROW 
INSERT INTO  `licence_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`licence_number`,
`licence_type_id`,
`country_lookup_id`,
`valid_from`,
`expiry_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`licence_number`,
OLD.`licence_type_id`,
OLD.`country_lookup_id`,
OLD.`valid_from`,
OLD.`expiry_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for licence_type
CREATE TABLE  IF NOT EXISTS `licence_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_licence_type (`id`,`version`),
  INDEX ix_licence_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_licence_type_ai`;
CREATE TRIGGER `tr_licence_type_ai` AFTER INSERT
ON `licence_type` FOR EACH ROW
INSERT INTO  `licence_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_licence_type_au`;
CREATE TRIGGER `tr_licence_type_au` AFTER UPDATE
ON `licence_type` FOR EACH ROW 
INSERT INTO  `licence_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_licence_type_ad`;
CREATE TRIGGER `tr_licence_type_ad` AFTER DELETE
ON `licence_type` FOR EACH ROW 
INSERT INTO  `licence_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for make
CREATE TABLE  IF NOT EXISTS `make_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`name` varchar(50),
`code` varchar(5),
`is_verified` tinyint(4),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_make (`id`,`version`),
  INDEX ix_make_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_make_ai`;
CREATE TRIGGER `tr_make_ai` AFTER INSERT
ON `make` FOR EACH ROW
INSERT INTO  `make_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_make_au`;
CREATE TRIGGER `tr_make_au` AFTER UPDATE
ON `make` FOR EACH ROW 
INSERT INTO  `make_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`is_verified`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`is_verified`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_make_ad`;
CREATE TRIGGER `tr_make_ad` AFTER DELETE
ON `make` FOR EACH ROW 
INSERT INTO  `make_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`is_verified`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`is_verified`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for message
CREATE TABLE  IF NOT EXISTS `message_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`person_id` int(10) unsigned,
`message_type_id` smallint(5) unsigned,
`issue_date` datetime,
`expiry_date` datetime,
`is_acknowledged` tinyint(4),
`token` varchar(64),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_message (`id`,`version`),
  INDEX ix_message_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_message_ai`;
CREATE TRIGGER `tr_message_ai` AFTER INSERT
ON `message` FOR EACH ROW
INSERT INTO  `message_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_message_au`;
CREATE TRIGGER `tr_message_au` AFTER UPDATE
ON `message` FOR EACH ROW 
INSERT INTO  `message_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`person_id`,
`message_type_id`,
`issue_date`,
`expiry_date`,
`is_acknowledged`,
`token`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`person_id`,
OLD.`message_type_id`,
OLD.`issue_date`,
OLD.`expiry_date`,
OLD.`is_acknowledged`,
OLD.`token`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_message_ad`;
CREATE TRIGGER `tr_message_ad` AFTER DELETE
ON `message` FOR EACH ROW 
INSERT INTO  `message_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`person_id`,
`message_type_id`,
`issue_date`,
`expiry_date`,
`is_acknowledged`,
`token`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`person_id`,
OLD.`message_type_id`,
OLD.`issue_date`,
OLD.`expiry_date`,
OLD.`is_acknowledged`,
OLD.`token`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for message_content
CREATE TABLE  IF NOT EXISTS `message_content_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`message_id` int(10) unsigned,
`recipient_email` varchar(255),
`title` varchar(100),
`content` text,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_message_content (`id`,`version`),
  INDEX ix_message_content_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_message_content_ai`;
CREATE TRIGGER `tr_message_content_ai` AFTER INSERT
ON `message_content` FOR EACH ROW
INSERT INTO  `message_content_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_message_content_au`;
CREATE TRIGGER `tr_message_content_au` AFTER UPDATE
ON `message_content` FOR EACH ROW 
INSERT INTO  `message_content_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`message_id`,
`recipient_email`,
`title`,
`content`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`message_id`,
OLD.`recipient_email`,
OLD.`title`,
OLD.`content`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_message_content_ad`;
CREATE TRIGGER `tr_message_content_ad` AFTER DELETE
ON `message_content` FOR EACH ROW 
INSERT INTO  `message_content_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`message_id`,
`recipient_email`,
`title`,
`content`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`message_id`,
OLD.`recipient_email`,
OLD.`title`,
OLD.`content`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for message_type
CREATE TABLE  IF NOT EXISTS `message_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`expiry_period` smallint(6) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_message_type (`id`,`version`),
  INDEX ix_message_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_message_type_ai`;
CREATE TRIGGER `tr_message_type_ai` AFTER INSERT
ON `message_type` FOR EACH ROW
INSERT INTO  `message_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_message_type_au`;
CREATE TRIGGER `tr_message_type_au` AFTER UPDATE
ON `message_type` FOR EACH ROW 
INSERT INTO  `message_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`expiry_period`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`expiry_period`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_message_type_ad`;
CREATE TRIGGER `tr_message_type_ad` AFTER DELETE
ON `message_type` FOR EACH ROW 
INSERT INTO  `message_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`expiry_period`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`expiry_period`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for message_url
CREATE TABLE  IF NOT EXISTS `message_url_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`message_id` int(10) unsigned,
`url_type_id` smallint(5) unsigned,
`name` varchar(100),
`url` varchar(255),
`used_count` smallint(6) unsigned,
`is_expired` tinyint(4),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_message_url (`id`,`version`),
  INDEX ix_message_url_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_message_url_ai`;
CREATE TRIGGER `tr_message_url_ai` AFTER INSERT
ON `message_url` FOR EACH ROW
INSERT INTO  `message_url_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_message_url_au`;
CREATE TRIGGER `tr_message_url_au` AFTER UPDATE
ON `message_url` FOR EACH ROW 
INSERT INTO  `message_url_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`message_id`,
`url_type_id`,
`name`,
`url`,
`used_count`,
`is_expired`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`message_id`,
OLD.`url_type_id`,
OLD.`name`,
OLD.`url`,
OLD.`used_count`,
OLD.`is_expired`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_message_url_ad`;
CREATE TRIGGER `tr_message_url_ad` AFTER DELETE
ON `message_url` FOR EACH ROW 
INSERT INTO  `message_url_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`message_id`,
`url_type_id`,
`name`,
`url`,
`used_count`,
`is_expired`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`message_id`,
OLD.`url_type_id`,
OLD.`name`,
OLD.`url`,
OLD.`used_count`,
OLD.`is_expired`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for model
CREATE TABLE  IF NOT EXISTS `model_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`code` varchar(5),
`name` varchar(50),
`make_id` int(10) unsigned,
`make_code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
`is_verified` tinyint(4),
  PRIMARY KEY (`hist_id`),
  INDEX uq_model (`id`,`version`),
  INDEX ix_model_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_model_ai`;
CREATE TRIGGER `tr_model_ai` AFTER INSERT
ON `model` FOR EACH ROW
INSERT INTO  `model_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_model_au`;
CREATE TRIGGER `tr_model_au` AFTER UPDATE
ON `model` FOR EACH ROW 
INSERT INTO  `model_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`make_id`,
`make_code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`,
`is_verified`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`make_id`,
OLD.`make_code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`,
OLD.`is_verified`);

DROP TRIGGER IF EXISTS `tr_model_ad`;
CREATE TRIGGER `tr_model_ad` AFTER DELETE
ON `model` FOR EACH ROW 
INSERT INTO  `model_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`make_id`,
`make_code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`,
`is_verified`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`make_id`,
OLD.`make_code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`,
OLD.`is_verified`);


-- Create history table and update trigger for model_detail
CREATE TABLE  IF NOT EXISTS `model_detail_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`name` varchar(50),
`make_id` int(10) unsigned,
`model_id` int(10) unsigned,
`code` varchar(5),
`is_verified` tinyint(4),
`weight` int(11),
`weight_source_id` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_model_detail (`id`,`version`),
  INDEX ix_model_detail_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_model_detail_ai`;
CREATE TRIGGER `tr_model_detail_ai` AFTER INSERT
ON `model_detail` FOR EACH ROW
INSERT INTO  `model_detail_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_model_detail_au`;
CREATE TRIGGER `tr_model_detail_au` AFTER UPDATE
ON `model_detail` FOR EACH ROW 
INSERT INTO  `model_detail_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`make_id`,
`model_id`,
`code`,
`is_verified`,
`weight`,
`weight_source_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`make_id`,
OLD.`model_id`,
OLD.`code`,
OLD.`is_verified`,
OLD.`weight`,
OLD.`weight_source_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_model_detail_ad`;
CREATE TRIGGER `tr_model_detail_ad` AFTER DELETE
ON `model_detail` FOR EACH ROW 
INSERT INTO  `model_detail_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`make_id`,
`model_id`,
`code`,
`is_verified`,
`weight`,
`weight_source_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`make_id`,
OLD.`model_id`,
OLD.`code`,
OLD.`is_verified`,
OLD.`weight`,
OLD.`weight_source_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for mot1_vts_device_status
CREATE TABLE  IF NOT EXISTS `mot1_vts_device_status_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_mot1_vts_device_status (`id`,`version`),
  INDEX ix_mot1_vts_device_status_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_mot1_vts_device_status_ai`;
CREATE TRIGGER `tr_mot1_vts_device_status_ai` AFTER INSERT
ON `mot1_vts_device_status` FOR EACH ROW
INSERT INTO  `mot1_vts_device_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_mot1_vts_device_status_au`;
CREATE TRIGGER `tr_mot1_vts_device_status_au` AFTER UPDATE
ON `mot1_vts_device_status` FOR EACH ROW 
INSERT INTO  `mot1_vts_device_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_mot1_vts_device_status_ad`;
CREATE TRIGGER `tr_mot1_vts_device_status_ad` AFTER DELETE
ON `mot1_vts_device_status` FOR EACH ROW 
INSERT INTO  `mot1_vts_device_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for mot_test
CREATE TABLE  IF NOT EXISTS `mot_test_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` bigint(20) unsigned,
`person_id` int(10) unsigned,
`vehicle_id` int(10) unsigned,
`vehicle_version` int(10) unsigned,
`document_id` bigint(20) unsigned,
`site_id` int(10) unsigned,
`primary_colour_id` smallint(5) unsigned,
`secondary_colour_id` smallint(5) unsigned,
`vehicle_class_id` smallint(5) unsigned,
`tested_as_fuel_type_id` smallint(5) unsigned,
`vin` varchar(30),
`empty_vin_reason_id` smallint(5) unsigned,
`registration` varchar(20),
`empty_vrm_reason_id` smallint(5) unsigned,
`make_id` int(10) unsigned,
`model_id` int(10) unsigned,
`model_detail_id` int(10) unsigned,
`country_of_registration_id` smallint(5) unsigned,
`has_registration` tinyint(4) unsigned,
`mot_test_type_id` smallint(5) unsigned,
`started_date` datetime(6),
`completed_date` datetime(6),
`status_id` smallint(5) unsigned,
`issued_date` datetime(6),
`expiry_date` date,
`mot_test_id_original` bigint(20) unsigned,
`prs_mot_test_id` bigint(20) unsigned,
`mot_test_reason_for_cancel_id` smallint(5) unsigned,
`reason_for_cancel_comment_id` bigint(20) unsigned,
`reason_for_termination_comment` varchar(240),
`full_partial_retest_id` smallint(5) unsigned,
`partial_reinspection_comment_id` bigint(20) unsigned,
`items_not_tested_comment_id` bigint(20) unsigned,
`one_person_test` tinyint(3) unsigned,
`one_person_reinspection` tinyint(3) unsigned,
`complaint_ref` varchar(30),
`number` decimal(12,0),
`odometer_reading_id` bigint(20) unsigned,
`private` tinyint(3) unsigned,
`emergency_log_id` int(10) unsigned,
`emergency_reason_lookup_id` smallint(5) unsigned,
`emergency_reason_comment_id` bigint(20) unsigned,
`vehicle_weight_source_lookup_id` smallint(5) unsigned,
`vehicle_weight` int(10) unsigned,
`incognito_vehicle_id` int(10) unsigned,
`address_comment_id` bigint(20) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
`make_name` varchar(50),
`model_name` varchar(50),
`model_detail_name` varchar(50),
`client_ip` varchar(45),
  PRIMARY KEY (`hist_id`),
  INDEX uq_mot_test (`id`,`version`),
  INDEX ix_mot_test_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_mot_test_ai`;
CREATE TRIGGER `tr_mot_test_ai` AFTER INSERT
ON `mot_test` FOR EACH ROW
INSERT INTO  `mot_test_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_mot_test_au`;
CREATE TRIGGER `tr_mot_test_au` AFTER UPDATE
ON `mot_test` FOR EACH ROW 
INSERT INTO  `mot_test_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`person_id`,
`vehicle_id`,
`vehicle_version`,
`document_id`,
`site_id`,
`primary_colour_id`,
`secondary_colour_id`,
`vehicle_class_id`,
`tested_as_fuel_type_id`,
`vin`,
`empty_vin_reason_id`,
`registration`,
`empty_vrm_reason_id`,
`make_id`,
`model_id`,
`model_detail_id`,
`country_of_registration_id`,
`has_registration`,
`mot_test_type_id`,
`started_date`,
`completed_date`,
`status_id`,
`issued_date`,
`expiry_date`,
`mot_test_id_original`,
`prs_mot_test_id`,
`mot_test_reason_for_cancel_id`,
`reason_for_cancel_comment_id`,
`reason_for_termination_comment`,
`full_partial_retest_id`,
`partial_reinspection_comment_id`,
`items_not_tested_comment_id`,
`one_person_test`,
`one_person_reinspection`,
`complaint_ref`,
`number`,
`odometer_reading_id`,
`private`,
`emergency_log_id`,
`emergency_reason_lookup_id`,
`emergency_reason_comment_id`,
`vehicle_weight_source_lookup_id`,
`vehicle_weight`,
`incognito_vehicle_id`,
`address_comment_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`,
`make_name`,
`model_name`,
`model_detail_name`,
`client_ip`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`person_id`,
OLD.`vehicle_id`,
OLD.`vehicle_version`,
OLD.`document_id`,
OLD.`site_id`,
OLD.`primary_colour_id`,
OLD.`secondary_colour_id`,
OLD.`vehicle_class_id`,
OLD.`tested_as_fuel_type_id`,
OLD.`vin`,
OLD.`empty_vin_reason_id`,
OLD.`registration`,
OLD.`empty_vrm_reason_id`,
OLD.`make_id`,
OLD.`model_id`,
OLD.`model_detail_id`,
OLD.`country_of_registration_id`,
OLD.`has_registration`,
OLD.`mot_test_type_id`,
OLD.`started_date`,
OLD.`completed_date`,
OLD.`status_id`,
OLD.`issued_date`,
OLD.`expiry_date`,
OLD.`mot_test_id_original`,
OLD.`prs_mot_test_id`,
OLD.`mot_test_reason_for_cancel_id`,
OLD.`reason_for_cancel_comment_id`,
OLD.`reason_for_termination_comment`,
OLD.`full_partial_retest_id`,
OLD.`partial_reinspection_comment_id`,
OLD.`items_not_tested_comment_id`,
OLD.`one_person_test`,
OLD.`one_person_reinspection`,
OLD.`complaint_ref`,
OLD.`number`,
OLD.`odometer_reading_id`,
OLD.`private`,
OLD.`emergency_log_id`,
OLD.`emergency_reason_lookup_id`,
OLD.`emergency_reason_comment_id`,
OLD.`vehicle_weight_source_lookup_id`,
OLD.`vehicle_weight`,
OLD.`incognito_vehicle_id`,
OLD.`address_comment_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`,
OLD.`make_name`,
OLD.`model_name`,
OLD.`model_detail_name`,
OLD.`client_ip`);

DROP TRIGGER IF EXISTS `tr_mot_test_ad`;
CREATE TRIGGER `tr_mot_test_ad` AFTER DELETE
ON `mot_test` FOR EACH ROW 
INSERT INTO  `mot_test_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`person_id`,
`vehicle_id`,
`vehicle_version`,
`document_id`,
`site_id`,
`primary_colour_id`,
`secondary_colour_id`,
`vehicle_class_id`,
`tested_as_fuel_type_id`,
`vin`,
`empty_vin_reason_id`,
`registration`,
`empty_vrm_reason_id`,
`make_id`,
`model_id`,
`model_detail_id`,
`country_of_registration_id`,
`has_registration`,
`mot_test_type_id`,
`started_date`,
`completed_date`,
`status_id`,
`issued_date`,
`expiry_date`,
`mot_test_id_original`,
`prs_mot_test_id`,
`mot_test_reason_for_cancel_id`,
`reason_for_cancel_comment_id`,
`reason_for_termination_comment`,
`full_partial_retest_id`,
`partial_reinspection_comment_id`,
`items_not_tested_comment_id`,
`one_person_test`,
`one_person_reinspection`,
`complaint_ref`,
`number`,
`odometer_reading_id`,
`private`,
`emergency_log_id`,
`emergency_reason_lookup_id`,
`emergency_reason_comment_id`,
`vehicle_weight_source_lookup_id`,
`vehicle_weight`,
`incognito_vehicle_id`,
`address_comment_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`,
`make_name`,
`model_name`,
`model_detail_name`,
`client_ip`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`person_id`,
OLD.`vehicle_id`,
OLD.`vehicle_version`,
OLD.`document_id`,
OLD.`site_id`,
OLD.`primary_colour_id`,
OLD.`secondary_colour_id`,
OLD.`vehicle_class_id`,
OLD.`tested_as_fuel_type_id`,
OLD.`vin`,
OLD.`empty_vin_reason_id`,
OLD.`registration`,
OLD.`empty_vrm_reason_id`,
OLD.`make_id`,
OLD.`model_id`,
OLD.`model_detail_id`,
OLD.`country_of_registration_id`,
OLD.`has_registration`,
OLD.`mot_test_type_id`,
OLD.`started_date`,
OLD.`completed_date`,
OLD.`status_id`,
OLD.`issued_date`,
OLD.`expiry_date`,
OLD.`mot_test_id_original`,
OLD.`prs_mot_test_id`,
OLD.`mot_test_reason_for_cancel_id`,
OLD.`reason_for_cancel_comment_id`,
OLD.`reason_for_termination_comment`,
OLD.`full_partial_retest_id`,
OLD.`partial_reinspection_comment_id`,
OLD.`items_not_tested_comment_id`,
OLD.`one_person_test`,
OLD.`one_person_reinspection`,
OLD.`complaint_ref`,
OLD.`number`,
OLD.`odometer_reading_id`,
OLD.`private`,
OLD.`emergency_log_id`,
OLD.`emergency_reason_lookup_id`,
OLD.`emergency_reason_comment_id`,
OLD.`vehicle_weight_source_lookup_id`,
OLD.`vehicle_weight`,
OLD.`incognito_vehicle_id`,
OLD.`address_comment_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`,
OLD.`make_name`,
OLD.`model_name`,
OLD.`model_detail_name`,
OLD.`client_ip`);


-- Create history table and update trigger for mot_test_event
CREATE TABLE  IF NOT EXISTS `mot_test_event_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` bigint(20) unsigned,
`mot_test_id` bigint(20) unsigned,
`mot_test_version` smallint(5) unsigned,
`tester_person_id` int(10) unsigned,
`certificate_status_id` smallint(5) unsigned,
`different_tester_reason_id` smallint(5) unsigned,
`reason_comment_id` bigint(20) unsigned,
`document_id` bigint(20) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_mot_test_event (`id`,`version`),
  INDEX ix_mot_test_event_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_mot_test_event_ai`;
CREATE TRIGGER `tr_mot_test_event_ai` AFTER INSERT
ON `mot_test_event` FOR EACH ROW
INSERT INTO  `mot_test_event_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_mot_test_event_au`;
CREATE TRIGGER `tr_mot_test_event_au` AFTER UPDATE
ON `mot_test_event` FOR EACH ROW 
INSERT INTO  `mot_test_event_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`mot_test_id`,
`mot_test_version`,
`tester_person_id`,
`certificate_status_id`,
`different_tester_reason_id`,
`reason_comment_id`,
`document_id`,
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
OLD.`tester_person_id`,
OLD.`certificate_status_id`,
OLD.`different_tester_reason_id`,
OLD.`reason_comment_id`,
OLD.`document_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_mot_test_event_ad`;
CREATE TRIGGER `tr_mot_test_event_ad` AFTER DELETE
ON `mot_test_event` FOR EACH ROW 
INSERT INTO  `mot_test_event_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`mot_test_id`,
`mot_test_version`,
`tester_person_id`,
`certificate_status_id`,
`different_tester_reason_id`,
`reason_comment_id`,
`document_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`mot_test_id`,
OLD.`mot_test_version`,
OLD.`tester_person_id`,
OLD.`certificate_status_id`,
OLD.`different_tester_reason_id`,
OLD.`reason_comment_id`,
OLD.`document_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for mot_test_reason_for_cancel_lookup
CREATE TABLE  IF NOT EXISTS `mot_test_reason_for_cancel_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`code` varchar(5),
`reason` varchar(250),
`reason_cy` varchar(250),
`is_system_generated` tinyint(4) unsigned,
`is_displayable` tinyint(4) unsigned,
`is_abandoned` tinyint(4) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_mot_test_reason_for_cancel_lookup (`id`,`version`),
  INDEX ix_mot_test_reason_for_cancel_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_mot_test_reason_for_cancel_lookup_ai`;
CREATE TRIGGER `tr_mot_test_reason_for_cancel_lookup_ai` AFTER INSERT
ON `mot_test_reason_for_cancel_lookup` FOR EACH ROW
INSERT INTO  `mot_test_reason_for_cancel_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_mot_test_reason_for_cancel_lookup_au`;
CREATE TRIGGER `tr_mot_test_reason_for_cancel_lookup_au` AFTER UPDATE
ON `mot_test_reason_for_cancel_lookup` FOR EACH ROW 
INSERT INTO  `mot_test_reason_for_cancel_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`reason`,
`reason_cy`,
`is_system_generated`,
`is_displayable`,
`is_abandoned`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`code`,
OLD.`reason`,
OLD.`reason_cy`,
OLD.`is_system_generated`,
OLD.`is_displayable`,
OLD.`is_abandoned`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_mot_test_reason_for_cancel_lookup_ad`;
CREATE TRIGGER `tr_mot_test_reason_for_cancel_lookup_ad` AFTER DELETE
ON `mot_test_reason_for_cancel_lookup` FOR EACH ROW 
INSERT INTO  `mot_test_reason_for_cancel_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`reason`,
`reason_cy`,
`is_system_generated`,
`is_displayable`,
`is_abandoned`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`code`,
OLD.`reason`,
OLD.`reason_cy`,
OLD.`is_system_generated`,
OLD.`is_displayable`,
OLD.`is_abandoned`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for mot_test_reason_for_refusal_lookup
CREATE TABLE  IF NOT EXISTS `mot_test_reason_for_refusal_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`reason` varchar(250),
`reason_cy` varchar(250),
`code` varchar(5),
`display_order` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_mot_test_reason_for_refusal_lookup (`id`,`version`),
  INDEX ix_mot_test_reason_for_refusal_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_mot_test_reason_for_refusal_lookup_ai`;
CREATE TRIGGER `tr_mot_test_reason_for_refusal_lookup_ai` AFTER INSERT
ON `mot_test_reason_for_refusal_lookup` FOR EACH ROW
INSERT INTO  `mot_test_reason_for_refusal_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_mot_test_reason_for_refusal_lookup_au`;
CREATE TRIGGER `tr_mot_test_reason_for_refusal_lookup_au` AFTER UPDATE
ON `mot_test_reason_for_refusal_lookup` FOR EACH ROW 
INSERT INTO  `mot_test_reason_for_refusal_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`reason`,
`reason_cy`,
`code`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`reason`,
OLD.`reason_cy`,
OLD.`code`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_mot_test_reason_for_refusal_lookup_ad`;
CREATE TRIGGER `tr_mot_test_reason_for_refusal_lookup_ad` AFTER DELETE
ON `mot_test_reason_for_refusal_lookup` FOR EACH ROW 
INSERT INTO  `mot_test_reason_for_refusal_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`reason`,
`reason_cy`,
`code`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`reason`,
OLD.`reason_cy`,
OLD.`code`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for mot_test_refusal
CREATE TABLE  IF NOT EXISTS `mot_test_refusal_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`vin` varchar(20),
`registration` varchar(7),
`site_id` int(10) unsigned,
`person_id` int(10) unsigned,
`refused_on` datetime(6),
`reason_for_refusal_id` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_mot_test_refusal (`id`,`version`),
  INDEX ix_mot_test_refusal_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_mot_test_refusal_ai`;
CREATE TRIGGER `tr_mot_test_refusal_ai` AFTER INSERT
ON `mot_test_refusal` FOR EACH ROW
INSERT INTO  `mot_test_refusal_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_mot_test_refusal_au`;
CREATE TRIGGER `tr_mot_test_refusal_au` AFTER UPDATE
ON `mot_test_refusal` FOR EACH ROW 
INSERT INTO  `mot_test_refusal_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`vin`,
`registration`,
`site_id`,
`person_id`,
`refused_on`,
`reason_for_refusal_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`vin`,
OLD.`registration`,
OLD.`site_id`,
OLD.`person_id`,
OLD.`refused_on`,
OLD.`reason_for_refusal_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_mot_test_refusal_ad`;
CREATE TRIGGER `tr_mot_test_refusal_ad` AFTER DELETE
ON `mot_test_refusal` FOR EACH ROW 
INSERT INTO  `mot_test_refusal_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`vin`,
`registration`,
`site_id`,
`person_id`,
`refused_on`,
`reason_for_refusal_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`vin`,
OLD.`registration`,
OLD.`site_id`,
OLD.`person_id`,
OLD.`refused_on`,
OLD.`reason_for_refusal_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for mot_test_rfr_map
CREATE TABLE  IF NOT EXISTS `mot_test_rfr_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` bigint(20) unsigned,
`mot_test_id` bigint(20) unsigned,
`rfr_id` int(10) unsigned,
`type` varchar(50),
`location_lateral` varchar(50),
`location_longitudinal` varchar(50),
`location_vertical` varchar(50),
`comment` varchar(255),
`failure_dangerous` tinyint(4),
`generated` tinyint(4),
`custom_description` varchar(100),
`on_original_test` tinyint(4),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_mot_test_rfr_map (`id`,`version`),
  INDEX ix_mot_test_rfr_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_mot_test_rfr_map_ai`;
CREATE TRIGGER `tr_mot_test_rfr_map_ai` AFTER INSERT
ON `mot_test_rfr_map` FOR EACH ROW
INSERT INTO  `mot_test_rfr_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_mot_test_rfr_map_au`;
CREATE TRIGGER `tr_mot_test_rfr_map_au` AFTER UPDATE
ON `mot_test_rfr_map` FOR EACH ROW 
INSERT INTO  `mot_test_rfr_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`mot_test_id`,
`rfr_id`,
`type`,
`location_lateral`,
`location_longitudinal`,
`location_vertical`,
`comment`,
`failure_dangerous`,
`generated`,
`custom_description`,
`on_original_test`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`mot_test_id`,
OLD.`rfr_id`,
OLD.`type`,
OLD.`location_lateral`,
OLD.`location_longitudinal`,
OLD.`location_vertical`,
OLD.`comment`,
OLD.`failure_dangerous`,
OLD.`generated`,
OLD.`custom_description`,
OLD.`on_original_test`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_mot_test_rfr_map_ad`;
CREATE TRIGGER `tr_mot_test_rfr_map_ad` AFTER DELETE
ON `mot_test_rfr_map` FOR EACH ROW 
INSERT INTO  `mot_test_rfr_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`mot_test_id`,
`rfr_id`,
`type`,
`location_lateral`,
`location_longitudinal`,
`location_vertical`,
`comment`,
`failure_dangerous`,
`generated`,
`custom_description`,
`on_original_test`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`mot_test_id`,
OLD.`rfr_id`,
OLD.`type`,
OLD.`location_lateral`,
OLD.`location_longitudinal`,
OLD.`location_vertical`,
OLD.`comment`,
OLD.`failure_dangerous`,
OLD.`generated`,
OLD.`custom_description`,
OLD.`on_original_test`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for mot_test_status
CREATE TABLE  IF NOT EXISTS `mot_test_status_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(10),
`description` varchar(250),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_mot_test_status (`id`,`version`),
  INDEX ix_mot_test_status_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_mot_test_status_ai`;
CREATE TRIGGER `tr_mot_test_status_ai` AFTER INSERT
ON `mot_test_status` FOR EACH ROW
INSERT INTO  `mot_test_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_mot_test_status_au`;
CREATE TRIGGER `tr_mot_test_status_au` AFTER UPDATE
ON `mot_test_status` FOR EACH ROW 
INSERT INTO  `mot_test_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`description`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`description`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_mot_test_status_ad`;
CREATE TRIGGER `tr_mot_test_status_ad` AFTER DELETE
ON `mot_test_status` FOR EACH ROW 
INSERT INTO  `mot_test_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`description`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`description`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for mot_test_type
CREATE TABLE  IF NOT EXISTS `mot_test_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`code` varchar(5),
`description` varchar(50),
`display_order` smallint(5) unsigned,
`is_demo` tinyint(3) unsigned,
`is_slot_consuming` tinyint(3) unsigned,
`is_reinspection` tinyint(3) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_mot_test_type (`id`,`version`),
  INDEX ix_mot_test_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_mot_test_type_ai`;
CREATE TRIGGER `tr_mot_test_type_ai` AFTER INSERT
ON `mot_test_type` FOR EACH ROW
INSERT INTO  `mot_test_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_mot_test_type_au`;
CREATE TRIGGER `tr_mot_test_type_au` AFTER UPDATE
ON `mot_test_type` FOR EACH ROW 
INSERT INTO  `mot_test_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`description`,
`display_order`,
`is_demo`,
`is_slot_consuming`,
`is_reinspection`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`code`,
OLD.`description`,
OLD.`display_order`,
OLD.`is_demo`,
OLD.`is_slot_consuming`,
OLD.`is_reinspection`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_mot_test_type_ad`;
CREATE TRIGGER `tr_mot_test_type_ad` AFTER DELETE
ON `mot_test_type` FOR EACH ROW 
INSERT INTO  `mot_test_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`description`,
`display_order`,
`is_demo`,
`is_slot_consuming`,
`is_reinspection`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`code`,
OLD.`description`,
OLD.`display_order`,
OLD.`is_demo`,
OLD.`is_slot_consuming`,
OLD.`is_reinspection`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for non_working_day_country_lookup
CREATE TABLE  IF NOT EXISTS `non_working_day_country_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`country_lookup_id` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_non_working_day_country_lookup (`id`,`version`),
  INDEX ix_non_working_day_country_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_non_working_day_country_lookup_ai`;
CREATE TRIGGER `tr_non_working_day_country_lookup_ai` AFTER INSERT
ON `non_working_day_country_lookup` FOR EACH ROW
INSERT INTO  `non_working_day_country_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_non_working_day_country_lookup_au`;
CREATE TRIGGER `tr_non_working_day_country_lookup_au` AFTER UPDATE
ON `non_working_day_country_lookup` FOR EACH ROW 
INSERT INTO  `non_working_day_country_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`country_lookup_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`country_lookup_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_non_working_day_country_lookup_ad`;
CREATE TRIGGER `tr_non_working_day_country_lookup_ad` AFTER DELETE
ON `non_working_day_country_lookup` FOR EACH ROW 
INSERT INTO  `non_working_day_country_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`country_lookup_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`country_lookup_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for non_working_day_lookup
CREATE TABLE  IF NOT EXISTS `non_working_day_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`non_working_day_country_lookup_id` smallint(5) unsigned,
`day` date,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_non_working_day_lookup (`id`,`version`),
  INDEX ix_non_working_day_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_non_working_day_lookup_ai`;
CREATE TRIGGER `tr_non_working_day_lookup_ai` AFTER INSERT
ON `non_working_day_lookup` FOR EACH ROW
INSERT INTO  `non_working_day_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_non_working_day_lookup_au`;
CREATE TRIGGER `tr_non_working_day_lookup_au` AFTER UPDATE
ON `non_working_day_lookup` FOR EACH ROW 
INSERT INTO  `non_working_day_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`non_working_day_country_lookup_id`,
`day`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`non_working_day_country_lookup_id`,
OLD.`day`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_non_working_day_lookup_ad`;
CREATE TRIGGER `tr_non_working_day_lookup_ad` AFTER DELETE
ON `non_working_day_lookup` FOR EACH ROW 
INSERT INTO  `non_working_day_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`non_working_day_country_lookup_id`,
`day`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`non_working_day_country_lookup_id`,
OLD.`day`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for notification
CREATE TABLE  IF NOT EXISTS `notification_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`notification_template_id` int(10) unsigned,
`recipient_id` int(10) unsigned,
`read_on` datetime(6),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_notification (`id`,`version`),
  INDEX ix_notification_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_notification_ai`;
CREATE TRIGGER `tr_notification_ai` AFTER INSERT
ON `notification` FOR EACH ROW
INSERT INTO  `notification_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_notification_au`;
CREATE TRIGGER `tr_notification_au` AFTER UPDATE
ON `notification` FOR EACH ROW 
INSERT INTO  `notification_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`notification_template_id`,
`recipient_id`,
`read_on`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`notification_template_id`,
OLD.`recipient_id`,
OLD.`read_on`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_notification_ad`;
CREATE TRIGGER `tr_notification_ad` AFTER DELETE
ON `notification` FOR EACH ROW 
INSERT INTO  `notification_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`notification_template_id`,
`recipient_id`,
`read_on`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`notification_template_id`,
OLD.`recipient_id`,
OLD.`read_on`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for notification_action_lookup
CREATE TABLE  IF NOT EXISTS `notification_action_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`action` varchar(100),
`display_order` smallint(5) unsigned,
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_notification_action_lookup (`id`,`version`),
  INDEX ix_notification_action_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_notification_action_lookup_ai`;
CREATE TRIGGER `tr_notification_action_lookup_ai` AFTER INSERT
ON `notification_action_lookup` FOR EACH ROW
INSERT INTO  `notification_action_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_notification_action_lookup_au`;
CREATE TRIGGER `tr_notification_action_lookup_au` AFTER UPDATE
ON `notification_action_lookup` FOR EACH ROW 
INSERT INTO  `notification_action_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`action`,
`display_order`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`action`,
OLD.`display_order`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_notification_action_lookup_ad`;
CREATE TRIGGER `tr_notification_action_lookup_ad` AFTER DELETE
ON `notification_action_lookup` FOR EACH ROW 
INSERT INTO  `notification_action_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`action`,
`display_order`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`action`,
OLD.`display_order`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for notification_action_map
CREATE TABLE  IF NOT EXISTS `notification_action_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`notification_id` int(10) unsigned,
`action_id` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_notification_action_map (`id`,`version`),
  INDEX ix_notification_action_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_notification_action_map_ai`;
CREATE TRIGGER `tr_notification_action_map_ai` AFTER INSERT
ON `notification_action_map` FOR EACH ROW
INSERT INTO  `notification_action_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_notification_action_map_au`;
CREATE TRIGGER `tr_notification_action_map_au` AFTER UPDATE
ON `notification_action_map` FOR EACH ROW 
INSERT INTO  `notification_action_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`notification_id`,
`action_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`notification_id`,
OLD.`action_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_notification_action_map_ad`;
CREATE TRIGGER `tr_notification_action_map_ad` AFTER DELETE
ON `notification_action_map` FOR EACH ROW 
INSERT INTO  `notification_action_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`notification_id`,
`action_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`notification_id`,
OLD.`action_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for notification_field
CREATE TABLE  IF NOT EXISTS `notification_field_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`notification_id` int(10) unsigned,
`field` varchar(30),
`content` varchar(250),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_notification_field (`id`,`version`),
  INDEX ix_notification_field_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_notification_field_ai`;
CREATE TRIGGER `tr_notification_field_ai` AFTER INSERT
ON `notification_field` FOR EACH ROW
INSERT INTO  `notification_field_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_notification_field_au`;
CREATE TRIGGER `tr_notification_field_au` AFTER UPDATE
ON `notification_field` FOR EACH ROW 
INSERT INTO  `notification_field_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`notification_id`,
`field`,
`content`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`notification_id`,
OLD.`field`,
OLD.`content`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_notification_field_ad`;
CREATE TRIGGER `tr_notification_field_ad` AFTER DELETE
ON `notification_field` FOR EACH ROW 
INSERT INTO  `notification_field_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`notification_id`,
`field`,
`content`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`notification_id`,
OLD.`field`,
OLD.`content`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for notification_template
CREATE TABLE  IF NOT EXISTS `notification_template_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`content` text,
`subject` varchar(255),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_notification_template (`id`,`version`),
  INDEX ix_notification_template_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_notification_template_ai`;
CREATE TRIGGER `tr_notification_template_ai` AFTER INSERT
ON `notification_template` FOR EACH ROW
INSERT INTO  `notification_template_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_notification_template_au`;
CREATE TRIGGER `tr_notification_template_au` AFTER UPDATE
ON `notification_template` FOR EACH ROW 
INSERT INTO  `notification_template_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`content`,
`subject`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`content`,
OLD.`subject`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_notification_template_ad`;
CREATE TRIGGER `tr_notification_template_ad` AFTER DELETE
ON `notification_template` FOR EACH ROW 
INSERT INTO  `notification_template_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`content`,
`subject`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`content`,
OLD.`subject`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for notification_template_action
CREATE TABLE  IF NOT EXISTS `notification_template_action_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`notification_template_id` int(10) unsigned,
`action_id` smallint(5) unsigned,
`label` varchar(100),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_notification_template_action (`id`,`version`),
  INDEX ix_notification_template_action_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_notification_template_action_ai`;
CREATE TRIGGER `tr_notification_template_action_ai` AFTER INSERT
ON `notification_template_action` FOR EACH ROW
INSERT INTO  `notification_template_action_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_notification_template_action_au`;
CREATE TRIGGER `tr_notification_template_action_au` AFTER UPDATE
ON `notification_template_action` FOR EACH ROW 
INSERT INTO  `notification_template_action_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`notification_template_id`,
`action_id`,
`label`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`notification_template_id`,
OLD.`action_id`,
OLD.`label`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_notification_template_action_ad`;
CREATE TRIGGER `tr_notification_template_action_ad` AFTER DELETE
ON `notification_template_action` FOR EACH ROW 
INSERT INTO  `notification_template_action_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`notification_template_id`,
`action_id`,
`label`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`notification_template_id`,
OLD.`action_id`,
OLD.`label`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for odometer_reading
CREATE TABLE  IF NOT EXISTS `odometer_reading_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` bigint(20) unsigned,
`recorded_on` datetime(6),
`value` int(11),
`unit` varchar(2),
`result_type` varchar(10),
`is_deleted` tinyint(4),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_odometer_reading (`id`,`version`),
  INDEX ix_odometer_reading_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_odometer_reading_ai`;
CREATE TRIGGER `tr_odometer_reading_ai` AFTER INSERT
ON `odometer_reading` FOR EACH ROW
INSERT INTO  `odometer_reading_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_odometer_reading_au`;
CREATE TRIGGER `tr_odometer_reading_au` AFTER UPDATE
ON `odometer_reading` FOR EACH ROW 
INSERT INTO  `odometer_reading_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`recorded_on`,
`value`,
`unit`,
`result_type`,
`is_deleted`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`recorded_on`,
OLD.`value`,
OLD.`unit`,
OLD.`result_type`,
OLD.`is_deleted`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_odometer_reading_ad`;
CREATE TRIGGER `tr_odometer_reading_ad` AFTER DELETE
ON `odometer_reading` FOR EACH ROW 
INSERT INTO  `odometer_reading_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`recorded_on`,
`value`,
`unit`,
`result_type`,
`is_deleted`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`recorded_on`,
OLD.`value`,
OLD.`unit`,
OLD.`result_type`,
OLD.`is_deleted`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for organisation
CREATE TABLE  IF NOT EXISTS `organisation_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`name` varchar(60),
`registered_company_number` varchar(20),
`vat_registration_number` varchar(20),
`trading_name` varchar(60),
`company_type_id` smallint(5) unsigned,
`organisation_type_id` smallint(5) unsigned,
`transition_status_id` smallint(5) unsigned,
`transition_scheduled_on` date,
`sites_confirmed_ready_on` datetime(6),
`transition_processed_on` datetime(6),
`first_payment_setup_on` datetime(6),
`first_slots_purchased_on` datetime(6),
`mot1_total_running_balance` decimal(12,2),
`mot1_total_slots_converted` int(11),
`mot1_total_remainder_balance` decimal(12,2),
`mot1_total_vts_slots_merged` int(11),
`mot1_total_slots_merged` int(11),
`mot1_slots_migrated_on` datetime(6),
`mot1_details_updated_on` datetime(6),
`slots_balance` int(10),
`slots_warning` int(10) unsigned,
`slots_purchased` int(10) unsigned,
`slots_overdraft` int(10) unsigned,
`data_may_be_disclosed` tinyint(4),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_organisation (`id`,`version`),
  INDEX ix_organisation_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_organisation_ai`;
CREATE TRIGGER `tr_organisation_ai` AFTER INSERT
ON `organisation` FOR EACH ROW
INSERT INTO  `organisation_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_organisation_au`;
CREATE TRIGGER `tr_organisation_au` AFTER UPDATE
ON `organisation` FOR EACH ROW 
INSERT INTO  `organisation_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`registered_company_number`,
`vat_registration_number`,
`trading_name`,
`company_type_id`,
`organisation_type_id`,
`transition_status_id`,
`transition_scheduled_on`,
`sites_confirmed_ready_on`,
`transition_processed_on`,
`first_payment_setup_on`,
`first_slots_purchased_on`,
`mot1_total_running_balance`,
`mot1_total_slots_converted`,
`mot1_total_remainder_balance`,
`mot1_total_vts_slots_merged`,
`mot1_total_slots_merged`,
`mot1_slots_migrated_on`,
`mot1_details_updated_on`,
`slots_balance`,
`slots_warning`,
`slots_purchased`,
`slots_overdraft`,
`data_may_be_disclosed`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`registered_company_number`,
OLD.`vat_registration_number`,
OLD.`trading_name`,
OLD.`company_type_id`,
OLD.`organisation_type_id`,
OLD.`transition_status_id`,
OLD.`transition_scheduled_on`,
OLD.`sites_confirmed_ready_on`,
OLD.`transition_processed_on`,
OLD.`first_payment_setup_on`,
OLD.`first_slots_purchased_on`,
OLD.`mot1_total_running_balance`,
OLD.`mot1_total_slots_converted`,
OLD.`mot1_total_remainder_balance`,
OLD.`mot1_total_vts_slots_merged`,
OLD.`mot1_total_slots_merged`,
OLD.`mot1_slots_migrated_on`,
OLD.`mot1_details_updated_on`,
OLD.`slots_balance`,
OLD.`slots_warning`,
OLD.`slots_purchased`,
OLD.`slots_overdraft`,
OLD.`data_may_be_disclosed`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_organisation_ad`;
CREATE TRIGGER `tr_organisation_ad` AFTER DELETE
ON `organisation` FOR EACH ROW 
INSERT INTO  `organisation_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`registered_company_number`,
`vat_registration_number`,
`trading_name`,
`company_type_id`,
`organisation_type_id`,
`transition_status_id`,
`transition_scheduled_on`,
`sites_confirmed_ready_on`,
`transition_processed_on`,
`first_payment_setup_on`,
`first_slots_purchased_on`,
`mot1_total_running_balance`,
`mot1_total_slots_converted`,
`mot1_total_remainder_balance`,
`mot1_total_vts_slots_merged`,
`mot1_total_slots_merged`,
`mot1_slots_migrated_on`,
`mot1_details_updated_on`,
`slots_balance`,
`slots_warning`,
`slots_purchased`,
`slots_overdraft`,
`data_may_be_disclosed`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`registered_company_number`,
OLD.`vat_registration_number`,
OLD.`trading_name`,
OLD.`company_type_id`,
OLD.`organisation_type_id`,
OLD.`transition_status_id`,
OLD.`transition_scheduled_on`,
OLD.`sites_confirmed_ready_on`,
OLD.`transition_processed_on`,
OLD.`first_payment_setup_on`,
OLD.`first_slots_purchased_on`,
OLD.`mot1_total_running_balance`,
OLD.`mot1_total_slots_converted`,
OLD.`mot1_total_remainder_balance`,
OLD.`mot1_total_vts_slots_merged`,
OLD.`mot1_total_slots_merged`,
OLD.`mot1_slots_migrated_on`,
OLD.`mot1_details_updated_on`,
OLD.`slots_balance`,
OLD.`slots_warning`,
OLD.`slots_purchased`,
OLD.`slots_overdraft`,
OLD.`data_may_be_disclosed`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for organisation_assembly_role_map
CREATE TABLE  IF NOT EXISTS `organisation_assembly_role_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`organisation_id` int(10) unsigned,
`assembly_id` int(10) unsigned,
`assembly_role_id` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_organisation_assembly_role_map (`id`,`version`),
  INDEX ix_organisation_assembly_role_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_organisation_assembly_role_map_ai`;
CREATE TRIGGER `tr_organisation_assembly_role_map_ai` AFTER INSERT
ON `organisation_assembly_role_map` FOR EACH ROW
INSERT INTO  `organisation_assembly_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_organisation_assembly_role_map_au`;
CREATE TRIGGER `tr_organisation_assembly_role_map_au` AFTER UPDATE
ON `organisation_assembly_role_map` FOR EACH ROW 
INSERT INTO  `organisation_assembly_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`organisation_id`,
`assembly_id`,
`assembly_role_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`organisation_id`,
OLD.`assembly_id`,
OLD.`assembly_role_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_organisation_assembly_role_map_ad`;
CREATE TRIGGER `tr_organisation_assembly_role_map_ad` AFTER DELETE
ON `organisation_assembly_role_map` FOR EACH ROW 
INSERT INTO  `organisation_assembly_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`organisation_id`,
`assembly_id`,
`assembly_role_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`organisation_id`,
OLD.`assembly_id`,
OLD.`assembly_role_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for organisation_business_role
CREATE TABLE  IF NOT EXISTS `organisation_business_role_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`description` varchar(250),
`code` varchar(5),
`organisation_type_id` smallint(5) unsigned,
`role_id` int(10) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_organisation_business_role (`id`,`version`),
  INDEX ix_organisation_business_role_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_organisation_business_role_ai`;
CREATE TRIGGER `tr_organisation_business_role_ai` AFTER INSERT
ON `organisation_business_role` FOR EACH ROW
INSERT INTO  `organisation_business_role_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_organisation_business_role_au`;
CREATE TRIGGER `tr_organisation_business_role_au` AFTER UPDATE
ON `organisation_business_role` FOR EACH ROW 
INSERT INTO  `organisation_business_role_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`description`,
`code`,
`organisation_type_id`,
`role_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`description`,
OLD.`code`,
OLD.`organisation_type_id`,
OLD.`role_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_organisation_business_role_ad`;
CREATE TRIGGER `tr_organisation_business_role_ad` AFTER DELETE
ON `organisation_business_role` FOR EACH ROW 
INSERT INTO  `organisation_business_role_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`description`,
`code`,
`organisation_type_id`,
`role_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`description`,
OLD.`code`,
OLD.`organisation_type_id`,
OLD.`role_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for organisation_business_role_map
CREATE TABLE  IF NOT EXISTS `organisation_business_role_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`organisation_id` int(10) unsigned,
`business_role_id` smallint(5) unsigned,
`person_id` int(10) unsigned,
`status_id` smallint(5) unsigned,
`status_changed_on` datetime(6),
`valid_from` datetime(6),
`expiry_date` datetime(6),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_organisation_business_role_map (`id`,`version`),
  INDEX ix_organisation_business_role_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_organisation_business_role_map_ai`;
CREATE TRIGGER `tr_organisation_business_role_map_ai` AFTER INSERT
ON `organisation_business_role_map` FOR EACH ROW
INSERT INTO  `organisation_business_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_organisation_business_role_map_au`;
CREATE TRIGGER `tr_organisation_business_role_map_au` AFTER UPDATE
ON `organisation_business_role_map` FOR EACH ROW 
INSERT INTO  `organisation_business_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`organisation_id`,
`business_role_id`,
`person_id`,
`status_id`,
`status_changed_on`,
`valid_from`,
`expiry_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`organisation_id`,
OLD.`business_role_id`,
OLD.`person_id`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`valid_from`,
OLD.`expiry_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_organisation_business_role_map_ad`;
CREATE TRIGGER `tr_organisation_business_role_map_ad` AFTER DELETE
ON `organisation_business_role_map` FOR EACH ROW 
INSERT INTO  `organisation_business_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`organisation_id`,
`business_role_id`,
`person_id`,
`status_id`,
`status_changed_on`,
`valid_from`,
`expiry_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`organisation_id`,
OLD.`business_role_id`,
OLD.`person_id`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`valid_from`,
OLD.`expiry_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for organisation_contact_detail_map
CREATE TABLE  IF NOT EXISTS `organisation_contact_detail_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`organisation_id` int(10) unsigned,
`contact_detail_id` int(10) unsigned,
`organisation_contact_type_id` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_organisation_contact_detail_map (`id`,`version`),
  INDEX ix_organisation_contact_detail_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_organisation_contact_detail_map_ai`;
CREATE TRIGGER `tr_organisation_contact_detail_map_ai` AFTER INSERT
ON `organisation_contact_detail_map` FOR EACH ROW
INSERT INTO  `organisation_contact_detail_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_organisation_contact_detail_map_au`;
CREATE TRIGGER `tr_organisation_contact_detail_map_au` AFTER UPDATE
ON `organisation_contact_detail_map` FOR EACH ROW 
INSERT INTO  `organisation_contact_detail_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`organisation_id`,
`contact_detail_id`,
`organisation_contact_type_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`organisation_id`,
OLD.`contact_detail_id`,
OLD.`organisation_contact_type_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_organisation_contact_detail_map_ad`;
CREATE TRIGGER `tr_organisation_contact_detail_map_ad` AFTER DELETE
ON `organisation_contact_detail_map` FOR EACH ROW 
INSERT INTO  `organisation_contact_detail_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`organisation_id`,
`contact_detail_id`,
`organisation_contact_type_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`organisation_id`,
OLD.`contact_detail_id`,
OLD.`organisation_contact_type_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for organisation_contact_type
CREATE TABLE  IF NOT EXISTS `organisation_contact_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_organisation_contact_type (`id`,`version`),
  INDEX ix_organisation_contact_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_organisation_contact_type_ai`;
CREATE TRIGGER `tr_organisation_contact_type_ai` AFTER INSERT
ON `organisation_contact_type` FOR EACH ROW
INSERT INTO  `organisation_contact_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_organisation_contact_type_au`;
CREATE TRIGGER `tr_organisation_contact_type_au` AFTER UPDATE
ON `organisation_contact_type` FOR EACH ROW 
INSERT INTO  `organisation_contact_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_organisation_contact_type_ad`;
CREATE TRIGGER `tr_organisation_contact_type_ad` AFTER DELETE
ON `organisation_contact_type` FOR EACH ROW 
INSERT INTO  `organisation_contact_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for organisation_site_map
CREATE TABLE  IF NOT EXISTS `organisation_site_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`organisation_id` int(10) unsigned,
`site_id` int(10) unsigned,
`trading_name` varchar(60),
`status_id` smallint(5) unsigned,
`status_changed_on` datetime(6),
`start_date` datetime(6),
`end_date` datetime(6),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_organisation_site_map (`id`,`version`),
  INDEX ix_organisation_site_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_organisation_site_map_ai`;
CREATE TRIGGER `tr_organisation_site_map_ai` AFTER INSERT
ON `organisation_site_map` FOR EACH ROW
INSERT INTO  `organisation_site_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_organisation_site_map_au`;
CREATE TRIGGER `tr_organisation_site_map_au` AFTER UPDATE
ON `organisation_site_map` FOR EACH ROW 
INSERT INTO  `organisation_site_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`organisation_id`,
`site_id`,
`trading_name`,
`status_id`,
`status_changed_on`,
`start_date`,
`end_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`organisation_id`,
OLD.`site_id`,
OLD.`trading_name`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`start_date`,
OLD.`end_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_organisation_site_map_ad`;
CREATE TRIGGER `tr_organisation_site_map_ad` AFTER DELETE
ON `organisation_site_map` FOR EACH ROW 
INSERT INTO  `organisation_site_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`organisation_id`,
`site_id`,
`trading_name`,
`status_id`,
`status_changed_on`,
`start_date`,
`end_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`organisation_id`,
OLD.`site_id`,
OLD.`trading_name`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`start_date`,
OLD.`end_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for organisation_site_status
CREATE TABLE  IF NOT EXISTS `organisation_site_status_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_organisation_site_status (`id`,`version`),
  INDEX ix_organisation_site_status_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_organisation_site_status_ai`;
CREATE TRIGGER `tr_organisation_site_status_ai` AFTER INSERT
ON `organisation_site_status` FOR EACH ROW
INSERT INTO  `organisation_site_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_organisation_site_status_au`;
CREATE TRIGGER `tr_organisation_site_status_au` AFTER UPDATE
ON `organisation_site_status` FOR EACH ROW 
INSERT INTO  `organisation_site_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_organisation_site_status_ad`;
CREATE TRIGGER `tr_organisation_site_status_ad` AFTER DELETE
ON `organisation_site_status` FOR EACH ROW 
INSERT INTO  `organisation_site_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for organisation_type
CREATE TABLE  IF NOT EXISTS `organisation_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_organisation_type (`id`,`version`),
  INDEX ix_organisation_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_organisation_type_ai`;
CREATE TRIGGER `tr_organisation_type_ai` AFTER INSERT
ON `organisation_type` FOR EACH ROW
INSERT INTO  `organisation_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_organisation_type_au`;
CREATE TRIGGER `tr_organisation_type_au` AFTER UPDATE
ON `organisation_type` FOR EACH ROW 
INSERT INTO  `organisation_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_organisation_type_ad`;
CREATE TRIGGER `tr_organisation_type_ad` AFTER DELETE
ON `organisation_type` FOR EACH ROW 
INSERT INTO  `organisation_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for payment
CREATE TABLE  IF NOT EXISTS `payment_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`amount` decimal(10,2),
`receipt_reference` varchar(55),
`payment_details` text,
`status_id` smallint(5) unsigned,
`type` smallint(5) unsigned,
`created` datetime(6),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_payment (`id`,`version`),
  INDEX ix_payment_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_payment_ai`;
CREATE TRIGGER `tr_payment_ai` AFTER INSERT
ON `payment` FOR EACH ROW
INSERT INTO  `payment_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_payment_au`;
CREATE TRIGGER `tr_payment_au` AFTER UPDATE
ON `payment` FOR EACH ROW 
INSERT INTO  `payment_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`amount`,
`receipt_reference`,
`payment_details`,
`status_id`,
`type`,
`created`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`amount`,
OLD.`receipt_reference`,
OLD.`payment_details`,
OLD.`status_id`,
OLD.`type`,
OLD.`created`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_payment_ad`;
CREATE TRIGGER `tr_payment_ad` AFTER DELETE
ON `payment` FOR EACH ROW 
INSERT INTO  `payment_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`amount`,
`receipt_reference`,
`payment_details`,
`status_id`,
`type`,
`created`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`amount`,
OLD.`receipt_reference`,
OLD.`payment_details`,
OLD.`status_id`,
OLD.`type`,
OLD.`created`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for payment_status
CREATE TABLE  IF NOT EXISTS `payment_status_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_payment_status (`id`,`version`),
  INDEX ix_payment_status_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_payment_status_ai`;
CREATE TRIGGER `tr_payment_status_ai` AFTER INSERT
ON `payment_status` FOR EACH ROW
INSERT INTO  `payment_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_payment_status_au`;
CREATE TRIGGER `tr_payment_status_au` AFTER UPDATE
ON `payment_status` FOR EACH ROW 
INSERT INTO  `payment_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_payment_status_ad`;
CREATE TRIGGER `tr_payment_status_ad` AFTER DELETE
ON `payment_status` FOR EACH ROW 
INSERT INTO  `payment_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for payment_type
CREATE TABLE  IF NOT EXISTS `payment_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`type_name` varchar(75),
`active` tinyint(4),
`display_order` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_payment_type (`id`,`version`),
  INDEX ix_payment_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_payment_type_ai`;
CREATE TRIGGER `tr_payment_type_ai` AFTER INSERT
ON `payment_type` FOR EACH ROW
INSERT INTO  `payment_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_payment_type_au`;
CREATE TRIGGER `tr_payment_type_au` AFTER UPDATE
ON `payment_type` FOR EACH ROW 
INSERT INTO  `payment_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`type_name`,
`active`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`type_name`,
OLD.`active`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_payment_type_ad`;
CREATE TRIGGER `tr_payment_type_ad` AFTER DELETE
ON `payment_type` FOR EACH ROW 
INSERT INTO  `payment_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`type_name`,
`active`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`type_name`,
OLD.`active`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for permission
CREATE TABLE  IF NOT EXISTS `permission_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`name` varchar(50),
`code` varchar(40),
`is_restricted` tinyint(4),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_permission (`id`,`version`),
  INDEX ix_permission_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_permission_ai`;
CREATE TRIGGER `tr_permission_ai` AFTER INSERT
ON `permission` FOR EACH ROW
INSERT INTO  `permission_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_permission_au`;
CREATE TRIGGER `tr_permission_au` AFTER UPDATE
ON `permission` FOR EACH ROW 
INSERT INTO  `permission_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`is_restricted`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`is_restricted`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_permission_ad`;
CREATE TRIGGER `tr_permission_ad` AFTER DELETE
ON `permission` FOR EACH ROW 
INSERT INTO  `permission_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`is_restricted`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`is_restricted`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for person
CREATE TABLE  IF NOT EXISTS `person_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`username` varchar(50),
`pin` varchar(60),
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


-- Create history table and update trigger for person_contact_detail_map
CREATE TABLE  IF NOT EXISTS `person_contact_detail_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`contact_type_id` smallint(5) unsigned,
`person_id` int(10) unsigned,
`contact_id` int(10) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_person_contact_detail_map (`id`,`version`),
  INDEX ix_person_contact_detail_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_person_contact_detail_map_ai`;
CREATE TRIGGER `tr_person_contact_detail_map_ai` AFTER INSERT
ON `person_contact_detail_map` FOR EACH ROW
INSERT INTO  `person_contact_detail_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_person_contact_detail_map_au`;
CREATE TRIGGER `tr_person_contact_detail_map_au` AFTER UPDATE
ON `person_contact_detail_map` FOR EACH ROW 
INSERT INTO  `person_contact_detail_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`contact_type_id`,
`person_id`,
`contact_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`contact_type_id`,
OLD.`person_id`,
OLD.`contact_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_person_contact_detail_map_ad`;
CREATE TRIGGER `tr_person_contact_detail_map_ad` AFTER DELETE
ON `person_contact_detail_map` FOR EACH ROW 
INSERT INTO  `person_contact_detail_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`contact_type_id`,
`person_id`,
`contact_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`contact_type_id`,
OLD.`person_id`,
OLD.`contact_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for person_contact_type
CREATE TABLE  IF NOT EXISTS `person_contact_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_person_contact_type (`id`,`version`),
  INDEX ix_person_contact_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_person_contact_type_ai`;
CREATE TRIGGER `tr_person_contact_type_ai` AFTER INSERT
ON `person_contact_type` FOR EACH ROW
INSERT INTO  `person_contact_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_person_contact_type_au`;
CREATE TRIGGER `tr_person_contact_type_au` AFTER UPDATE
ON `person_contact_type` FOR EACH ROW 
INSERT INTO  `person_contact_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_person_contact_type_ad`;
CREATE TRIGGER `tr_person_contact_type_ad` AFTER DELETE
ON `person_contact_type` FOR EACH ROW 
INSERT INTO  `person_contact_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for person_identifying_token_map
CREATE TABLE  IF NOT EXISTS `person_identifying_token_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`person_id` int(10) unsigned,
`identifying_token_id` int(10) unsigned,
`start_date` datetime(6),
`end_date` datetime(6),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_person_identifying_token_map (`id`,`version`),
  INDEX ix_person_identifying_token_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_person_identifying_token_map_ai`;
CREATE TRIGGER `tr_person_identifying_token_map_ai` AFTER INSERT
ON `person_identifying_token_map` FOR EACH ROW
INSERT INTO  `person_identifying_token_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_person_identifying_token_map_au`;
CREATE TRIGGER `tr_person_identifying_token_map_au` AFTER UPDATE
ON `person_identifying_token_map` FOR EACH ROW 
INSERT INTO  `person_identifying_token_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`person_id`,
`identifying_token_id`,
`start_date`,
`end_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`person_id`,
OLD.`identifying_token_id`,
OLD.`start_date`,
OLD.`end_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_person_identifying_token_map_ad`;
CREATE TRIGGER `tr_person_identifying_token_map_ad` AFTER DELETE
ON `person_identifying_token_map` FOR EACH ROW 
INSERT INTO  `person_identifying_token_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`person_id`,
`identifying_token_id`,
`start_date`,
`end_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`person_id`,
OLD.`identifying_token_id`,
OLD.`start_date`,
OLD.`end_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for person_security_question_answer
CREATE TABLE  IF NOT EXISTS `person_security_question_answer_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`person_security_question_map_id` int(10) unsigned,
`is_answered_correctly` tinyint(4),
`is_service_desk` tinyint(4),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_person_security_question_answer (`id`,`version`),
  INDEX ix_person_security_question_answer_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_person_security_question_answer_ai`;
CREATE TRIGGER `tr_person_security_question_answer_ai` AFTER INSERT
ON `person_security_question_answer` FOR EACH ROW
INSERT INTO  `person_security_question_answer_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_person_security_question_answer_au`;
CREATE TRIGGER `tr_person_security_question_answer_au` AFTER UPDATE
ON `person_security_question_answer` FOR EACH ROW 
INSERT INTO  `person_security_question_answer_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`person_security_question_map_id`,
`is_answered_correctly`,
`is_service_desk`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`person_security_question_map_id`,
OLD.`is_answered_correctly`,
OLD.`is_service_desk`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_person_security_question_answer_ad`;
CREATE TRIGGER `tr_person_security_question_answer_ad` AFTER DELETE
ON `person_security_question_answer` FOR EACH ROW 
INSERT INTO  `person_security_question_answer_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`person_security_question_map_id`,
`is_answered_correctly`,
`is_service_desk`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`person_security_question_map_id`,
OLD.`is_answered_correctly`,
OLD.`is_service_desk`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for person_security_question_map
CREATE TABLE  IF NOT EXISTS `person_security_question_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`person_id` int(10) unsigned,
`security_question_id` int(10) unsigned,
`answer` varchar(80),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_person_security_question_map (`id`,`version`),
  INDEX ix_person_security_question_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_person_security_question_map_ai`;
CREATE TRIGGER `tr_person_security_question_map_ai` AFTER INSERT
ON `person_security_question_map` FOR EACH ROW
INSERT INTO  `person_security_question_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_person_security_question_map_au`;
CREATE TRIGGER `tr_person_security_question_map_au` AFTER UPDATE
ON `person_security_question_map` FOR EACH ROW 
INSERT INTO  `person_security_question_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`person_id`,
`security_question_id`,
`answer`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`person_id`,
OLD.`security_question_id`,
OLD.`answer`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_person_security_question_map_ad`;
CREATE TRIGGER `tr_person_security_question_map_ad` AFTER DELETE
ON `person_security_question_map` FOR EACH ROW 
INSERT INTO  `person_security_question_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`person_id`,
`security_question_id`,
`answer`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`person_id`,
OLD.`security_question_id`,
OLD.`answer`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for person_system_role
CREATE TABLE  IF NOT EXISTS `person_system_role_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`full_name` varchar(250),
`short_name` varchar(50),
`role_id` int(10) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_person_system_role (`id`,`version`),
  INDEX ix_person_system_role_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_person_system_role_ai`;
CREATE TRIGGER `tr_person_system_role_ai` AFTER INSERT
ON `person_system_role` FOR EACH ROW
INSERT INTO  `person_system_role_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_person_system_role_au`;
CREATE TRIGGER `tr_person_system_role_au` AFTER UPDATE
ON `person_system_role` FOR EACH ROW 
INSERT INTO  `person_system_role_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`full_name`,
`short_name`,
`role_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`full_name`,
OLD.`short_name`,
OLD.`role_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_person_system_role_ad`;
CREATE TRIGGER `tr_person_system_role_ad` AFTER DELETE
ON `person_system_role` FOR EACH ROW 
INSERT INTO  `person_system_role_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`full_name`,
`short_name`,
`role_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`full_name`,
OLD.`short_name`,
OLD.`role_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for person_system_role_map
CREATE TABLE  IF NOT EXISTS `person_system_role_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`person_id` int(10) unsigned,
`person_system_role_id` smallint(5) unsigned,
`status_id` smallint(5) unsigned,
`status_changed_on` datetime(6),
`valid_from` datetime(6),
`expiry_date` datetime(6),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_person_system_role_map (`id`,`version`),
  INDEX ix_person_system_role_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_person_system_role_map_ai`;
CREATE TRIGGER `tr_person_system_role_map_ai` AFTER INSERT
ON `person_system_role_map` FOR EACH ROW
INSERT INTO  `person_system_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_person_system_role_map_au`;
CREATE TRIGGER `tr_person_system_role_map_au` AFTER UPDATE
ON `person_system_role_map` FOR EACH ROW 
INSERT INTO  `person_system_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`person_id`,
`person_system_role_id`,
`status_id`,
`status_changed_on`,
`valid_from`,
`expiry_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`person_id`,
OLD.`person_system_role_id`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`valid_from`,
OLD.`expiry_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_person_system_role_map_ad`;
CREATE TRIGGER `tr_person_system_role_map_ad` AFTER DELETE
ON `person_system_role_map` FOR EACH ROW 
INSERT INTO  `person_system_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`person_id`,
`person_system_role_id`,
`status_id`,
`status_changed_on`,
`valid_from`,
`expiry_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`person_id`,
OLD.`person_system_role_id`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`valid_from`,
OLD.`expiry_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for phone
CREATE TABLE  IF NOT EXISTS `phone_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`contact_detail_id` int(10) unsigned,
`number` varchar(24),
`phone_contact_type_id` smallint(5) unsigned,
`is_primary` tinyint(4) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_phone (`id`,`version`),
  INDEX ix_phone_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_phone_ai`;
CREATE TRIGGER `tr_phone_ai` AFTER INSERT
ON `phone` FOR EACH ROW
INSERT INTO  `phone_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_phone_au`;
CREATE TRIGGER `tr_phone_au` AFTER UPDATE
ON `phone` FOR EACH ROW 
INSERT INTO  `phone_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`contact_detail_id`,
`number`,
`phone_contact_type_id`,
`is_primary`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`contact_detail_id`,
OLD.`number`,
OLD.`phone_contact_type_id`,
OLD.`is_primary`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_phone_ad`;
CREATE TRIGGER `tr_phone_ad` AFTER DELETE
ON `phone` FOR EACH ROW 
INSERT INTO  `phone_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`contact_detail_id`,
`number`,
`phone_contact_type_id`,
`is_primary`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`contact_detail_id`,
OLD.`number`,
OLD.`phone_contact_type_id`,
OLD.`is_primary`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for phone_contact_type
CREATE TABLE  IF NOT EXISTS `phone_contact_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_phone_contact_type (`id`,`version`),
  INDEX ix_phone_contact_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_phone_contact_type_ai`;
CREATE TRIGGER `tr_phone_contact_type_ai` AFTER INSERT
ON `phone_contact_type` FOR EACH ROW
INSERT INTO  `phone_contact_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_phone_contact_type_au`;
CREATE TRIGGER `tr_phone_contact_type_au` AFTER UPDATE
ON `phone_contact_type` FOR EACH ROW 
INSERT INTO  `phone_contact_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_phone_contact_type_ad`;
CREATE TRIGGER `tr_phone_contact_type_ad` AFTER DELETE
ON `phone_contact_type` FOR EACH ROW 
INSERT INTO  `phone_contact_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for qualification
CREATE TABLE  IF NOT EXISTS `qualification_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`name` varchar(100),
`description` varchar(255),
`qualification_type_id` smallint(5) unsigned,
`awarded_by_organisation_id` int(10) unsigned,
`country_lookup_id` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_qualification (`id`,`version`),
  INDEX ix_qualification_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_qualification_ai`;
CREATE TRIGGER `tr_qualification_ai` AFTER INSERT
ON `qualification` FOR EACH ROW
INSERT INTO  `qualification_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_qualification_au`;
CREATE TRIGGER `tr_qualification_au` AFTER UPDATE
ON `qualification` FOR EACH ROW 
INSERT INTO  `qualification_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`description`,
`qualification_type_id`,
`awarded_by_organisation_id`,
`country_lookup_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`description`,
OLD.`qualification_type_id`,
OLD.`awarded_by_organisation_id`,
OLD.`country_lookup_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_qualification_ad`;
CREATE TRIGGER `tr_qualification_ad` AFTER DELETE
ON `qualification` FOR EACH ROW 
INSERT INTO  `qualification_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`description`,
`qualification_type_id`,
`awarded_by_organisation_id`,
`country_lookup_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`description`,
OLD.`qualification_type_id`,
OLD.`awarded_by_organisation_id`,
OLD.`country_lookup_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for qualification_award
CREATE TABLE  IF NOT EXISTS `qualification_award_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`person_id` int(10) unsigned,
`qualification_id` int(10) unsigned,
`certificate_number` varchar(50),
`country_lookup_id` smallint(5) unsigned,
`awarded_on` datetime(6),
`verified_by` int(10) unsigned,
`verified_on` datetime(6),
`expiry_date` datetime(6),
`status_id` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_qualification_award (`id`,`version`),
  INDEX ix_qualification_award_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_qualification_award_ai`;
CREATE TRIGGER `tr_qualification_award_ai` AFTER INSERT
ON `qualification_award` FOR EACH ROW
INSERT INTO  `qualification_award_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_qualification_award_au`;
CREATE TRIGGER `tr_qualification_award_au` AFTER UPDATE
ON `qualification_award` FOR EACH ROW 
INSERT INTO  `qualification_award_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`person_id`,
`qualification_id`,
`certificate_number`,
`country_lookup_id`,
`awarded_on`,
`verified_by`,
`verified_on`,
`expiry_date`,
`status_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`person_id`,
OLD.`qualification_id`,
OLD.`certificate_number`,
OLD.`country_lookup_id`,
OLD.`awarded_on`,
OLD.`verified_by`,
OLD.`verified_on`,
OLD.`expiry_date`,
OLD.`status_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_qualification_award_ad`;
CREATE TRIGGER `tr_qualification_award_ad` AFTER DELETE
ON `qualification_award` FOR EACH ROW 
INSERT INTO  `qualification_award_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`person_id`,
`qualification_id`,
`certificate_number`,
`country_lookup_id`,
`awarded_on`,
`verified_by`,
`verified_on`,
`expiry_date`,
`status_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`person_id`,
OLD.`qualification_id`,
OLD.`certificate_number`,
OLD.`country_lookup_id`,
OLD.`awarded_on`,
OLD.`verified_by`,
OLD.`verified_on`,
OLD.`expiry_date`,
OLD.`status_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for qualification_type
CREATE TABLE  IF NOT EXISTS `qualification_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_qualification_type (`id`,`version`),
  INDEX ix_qualification_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_qualification_type_ai`;
CREATE TRIGGER `tr_qualification_type_ai` AFTER INSERT
ON `qualification_type` FOR EACH ROW
INSERT INTO  `qualification_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_qualification_type_au`;
CREATE TRIGGER `tr_qualification_type_au` AFTER UPDATE
ON `qualification_type` FOR EACH ROW 
INSERT INTO  `qualification_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_qualification_type_ad`;
CREATE TRIGGER `tr_qualification_type_ad` AFTER DELETE
ON `qualification_type` FOR EACH ROW 
INSERT INTO  `qualification_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for reason_for_rejection
CREATE TABLE  IF NOT EXISTS `reason_for_rejection_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`test_item_category_id` int(10) unsigned,
`test_item_selector_name` varchar(100),
`test_item_selector_name_cy` varchar(100),
`inspection_manual_reference` varchar(10),
`minor_item` tinyint(4),
`location_marker` tinyint(4),
`qt_marker` tinyint(4),
`note` tinyint(4),
`manual` varchar(1),
`spec_proc` tinyint(4),
`is_advisory` tinyint(3) unsigned,
`is_prs_fail` tinyint(3) unsigned,
`section_test_item_selector_id` int(10) unsigned,
`can_be_dangerous` tinyint(4),
`date_first_used` datetime,
`audience` varchar(1),
`end_date` date,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_reason_for_rejection (`id`,`version`),
  INDEX ix_reason_for_rejection_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_reason_for_rejection_ai`;
CREATE TRIGGER `tr_reason_for_rejection_ai` AFTER INSERT
ON `reason_for_rejection` FOR EACH ROW
INSERT INTO  `reason_for_rejection_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_reason_for_rejection_au`;
CREATE TRIGGER `tr_reason_for_rejection_au` AFTER UPDATE
ON `reason_for_rejection` FOR EACH ROW 
INSERT INTO  `reason_for_rejection_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`test_item_category_id`,
`test_item_selector_name`,
`test_item_selector_name_cy`,
`inspection_manual_reference`,
`minor_item`,
`location_marker`,
`qt_marker`,
`note`,
`manual`,
`spec_proc`,
`is_advisory`,
`is_prs_fail`,
`section_test_item_selector_id`,
`can_be_dangerous`,
`date_first_used`,
`audience`,
`end_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`test_item_category_id`,
OLD.`test_item_selector_name`,
OLD.`test_item_selector_name_cy`,
OLD.`inspection_manual_reference`,
OLD.`minor_item`,
OLD.`location_marker`,
OLD.`qt_marker`,
OLD.`note`,
OLD.`manual`,
OLD.`spec_proc`,
OLD.`is_advisory`,
OLD.`is_prs_fail`,
OLD.`section_test_item_selector_id`,
OLD.`can_be_dangerous`,
OLD.`date_first_used`,
OLD.`audience`,
OLD.`end_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_reason_for_rejection_ad`;
CREATE TRIGGER `tr_reason_for_rejection_ad` AFTER DELETE
ON `reason_for_rejection` FOR EACH ROW 
INSERT INTO  `reason_for_rejection_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`test_item_category_id`,
`test_item_selector_name`,
`test_item_selector_name_cy`,
`inspection_manual_reference`,
`minor_item`,
`location_marker`,
`qt_marker`,
`note`,
`manual`,
`spec_proc`,
`is_advisory`,
`is_prs_fail`,
`section_test_item_selector_id`,
`can_be_dangerous`,
`date_first_used`,
`audience`,
`end_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`test_item_category_id`,
OLD.`test_item_selector_name`,
OLD.`test_item_selector_name_cy`,
OLD.`inspection_manual_reference`,
OLD.`minor_item`,
OLD.`location_marker`,
OLD.`qt_marker`,
OLD.`note`,
OLD.`manual`,
OLD.`spec_proc`,
OLD.`is_advisory`,
OLD.`is_prs_fail`,
OLD.`section_test_item_selector_id`,
OLD.`can_be_dangerous`,
OLD.`date_first_used`,
OLD.`audience`,
OLD.`end_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for reason_for_rejection_type
CREATE TABLE  IF NOT EXISTS `reason_for_rejection_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`name` varchar(50),
`code` varchar(5),
`description` varchar(250),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_reason_for_rejection_type (`id`,`version`),
  INDEX ix_reason_for_rejection_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_reason_for_rejection_type_ai`;
CREATE TRIGGER `tr_reason_for_rejection_type_ai` AFTER INSERT
ON `reason_for_rejection_type` FOR EACH ROW
INSERT INTO  `reason_for_rejection_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_reason_for_rejection_type_au`;
CREATE TRIGGER `tr_reason_for_rejection_type_au` AFTER UPDATE
ON `reason_for_rejection_type` FOR EACH ROW 
INSERT INTO  `reason_for_rejection_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`description`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`description`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_reason_for_rejection_type_ad`;
CREATE TRIGGER `tr_reason_for_rejection_type_ad` AFTER DELETE
ON `reason_for_rejection_type` FOR EACH ROW 
INSERT INTO  `reason_for_rejection_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`description`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`description`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for replacement_certificate_draft
CREATE TABLE  IF NOT EXISTS `replacement_certificate_draft_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`mot_test_id` bigint(20) unsigned,
`mot_test_version` int(10) unsigned,
`odometer_reading_id` bigint(20) unsigned,
`vrm` varchar(20),
`empty_vrm_reason_id` smallint(5) unsigned,
`vin` varchar(30),
`empty_vin_reason_id` smallint(5) unsigned,
`vehicle_testing_station_id` int(10) unsigned,
`make_id` int(10) unsigned,
`make_name` varchar(50),
`model_id` int(10) unsigned,
`model_name` varchar(50),
`primary_colour_id` smallint(5) unsigned,
`secondary_colour_id` smallint(5) unsigned,
`country_of_registration_id` smallint(5) unsigned,
`expiry_date` datetime,
`different_tester_reason_id` smallint(5) unsigned,
`replacement_reason` text,
`is_vin_registration_changed` tinyint(3) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
`is_deleted` tinyint(4),
  PRIMARY KEY (`hist_id`),
  INDEX uq_replacement_certificate_draft (`id`,`version`),
  INDEX ix_replacement_certificate_draft_mot1_legacy_id (`mot1_legacy_id`));

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
`is_vin_registration_changed`,
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
OLD.`is_vin_registration_changed`,
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
`is_vin_registration_changed`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`,
`is_deleted`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
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
OLD.`is_vin_registration_changed`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`,
OLD.`is_deleted`);


-- Create history table and update trigger for rfr_business_rule_map
CREATE TABLE  IF NOT EXISTS `rfr_business_rule_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`rfr_id` int(10) unsigned,
`business_rule_id` int(10) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_rfr_business_rule_map (`id`,`version`),
  INDEX ix_rfr_business_rule_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_rfr_business_rule_map_ai`;
CREATE TRIGGER `tr_rfr_business_rule_map_ai` AFTER INSERT
ON `rfr_business_rule_map` FOR EACH ROW
INSERT INTO  `rfr_business_rule_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_rfr_business_rule_map_au`;
CREATE TRIGGER `tr_rfr_business_rule_map_au` AFTER UPDATE
ON `rfr_business_rule_map` FOR EACH ROW 
INSERT INTO  `rfr_business_rule_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`rfr_id`,
`business_rule_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`rfr_id`,
OLD.`business_rule_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_rfr_business_rule_map_ad`;
CREATE TRIGGER `tr_rfr_business_rule_map_ad` AFTER DELETE
ON `rfr_business_rule_map` FOR EACH ROW 
INSERT INTO  `rfr_business_rule_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`rfr_id`,
`business_rule_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`rfr_id`,
OLD.`business_rule_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for rfr_language_content_map
CREATE TABLE  IF NOT EXISTS `rfr_language_content_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`rfr_id` int(10) unsigned,
`language_type_id` smallint(5) unsigned,
`name` varchar(500),
`inspection_manual_description` varchar(500),
`advisory_text` varchar(250),
`test_item_selector_name` varchar(100),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_rfr_language_content_map (`id`,`version`),
  INDEX ix_rfr_language_content_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_rfr_language_content_map_ai`;
CREATE TRIGGER `tr_rfr_language_content_map_ai` AFTER INSERT
ON `rfr_language_content_map` FOR EACH ROW
INSERT INTO  `rfr_language_content_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_rfr_language_content_map_au`;
CREATE TRIGGER `tr_rfr_language_content_map_au` AFTER UPDATE
ON `rfr_language_content_map` FOR EACH ROW 
INSERT INTO  `rfr_language_content_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`rfr_id`,
`language_type_id`,
`name`,
`inspection_manual_description`,
`advisory_text`,
`test_item_selector_name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`rfr_id`,
OLD.`language_type_id`,
OLD.`name`,
OLD.`inspection_manual_description`,
OLD.`advisory_text`,
OLD.`test_item_selector_name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_rfr_language_content_map_ad`;
CREATE TRIGGER `tr_rfr_language_content_map_ad` AFTER DELETE
ON `rfr_language_content_map` FOR EACH ROW 
INSERT INTO  `rfr_language_content_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`rfr_id`,
`language_type_id`,
`name`,
`inspection_manual_description`,
`advisory_text`,
`test_item_selector_name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`rfr_id`,
OLD.`language_type_id`,
OLD.`name`,
OLD.`inspection_manual_description`,
OLD.`advisory_text`,
OLD.`test_item_selector_name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for rfr_vehicle_class_map
CREATE TABLE  IF NOT EXISTS `rfr_vehicle_class_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`rfr_id` int(10) unsigned,
`vehicle_class_id` smallint(5) unsigned,
`business_rule_id` int(10) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_rfr_vehicle_class_map (`id`,`version`),
  INDEX ix_rfr_vehicle_class_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_rfr_vehicle_class_map_ai`;
CREATE TRIGGER `tr_rfr_vehicle_class_map_ai` AFTER INSERT
ON `rfr_vehicle_class_map` FOR EACH ROW
INSERT INTO  `rfr_vehicle_class_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_rfr_vehicle_class_map_au`;
CREATE TRIGGER `tr_rfr_vehicle_class_map_au` AFTER UPDATE
ON `rfr_vehicle_class_map` FOR EACH ROW 
INSERT INTO  `rfr_vehicle_class_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`rfr_id`,
`vehicle_class_id`,
`business_rule_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`rfr_id`,
OLD.`vehicle_class_id`,
OLD.`business_rule_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_rfr_vehicle_class_map_ad`;
CREATE TRIGGER `tr_rfr_vehicle_class_map_ad` AFTER DELETE
ON `rfr_vehicle_class_map` FOR EACH ROW 
INSERT INTO  `rfr_vehicle_class_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`rfr_id`,
`vehicle_class_id`,
`business_rule_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`rfr_id`,
OLD.`vehicle_class_id`,
OLD.`business_rule_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for role
CREATE TABLE  IF NOT EXISTS `role_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`name` varchar(50),
`code` varchar(40),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_role (`id`,`version`),
  INDEX ix_role_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_role_ai`;
CREATE TRIGGER `tr_role_ai` AFTER INSERT
ON `role` FOR EACH ROW
INSERT INTO  `role_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_role_au`;
CREATE TRIGGER `tr_role_au` AFTER UPDATE
ON `role` FOR EACH ROW 
INSERT INTO  `role_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_role_ad`;
CREATE TRIGGER `tr_role_ad` AFTER DELETE
ON `role` FOR EACH ROW 
INSERT INTO  `role_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for role_permission_map
CREATE TABLE  IF NOT EXISTS `role_permission_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`role_id` int(10) unsigned,
`permission_id` int(10) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_role_permission_map (`id`,`version`),
  INDEX ix_role_permission_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_role_permission_map_ai`;
CREATE TRIGGER `tr_role_permission_map_ai` AFTER INSERT
ON `role_permission_map` FOR EACH ROW
INSERT INTO  `role_permission_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_role_permission_map_au`;
CREATE TRIGGER `tr_role_permission_map_au` AFTER UPDATE
ON `role_permission_map` FOR EACH ROW 
INSERT INTO  `role_permission_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`role_id`,
`permission_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`role_id`,
OLD.`permission_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_role_permission_map_ad`;
CREATE TRIGGER `tr_role_permission_map_ad` AFTER DELETE
ON `role_permission_map` FOR EACH ROW 
INSERT INTO  `role_permission_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`role_id`,
`permission_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`role_id`,
OLD.`permission_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for security_question
CREATE TABLE  IF NOT EXISTS `security_question_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`question_text` varchar(80),
`question_group` tinyint(4),
`display_order` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_security_question (`id`,`version`),
  INDEX ix_security_question_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_security_question_ai`;
CREATE TRIGGER `tr_security_question_ai` AFTER INSERT
ON `security_question` FOR EACH ROW
INSERT INTO  `security_question_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_security_question_au`;
CREATE TRIGGER `tr_security_question_au` AFTER UPDATE
ON `security_question` FOR EACH ROW 
INSERT INTO  `security_question_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`question_text`,
`question_group`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`question_text`,
OLD.`question_group`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_security_question_ad`;
CREATE TRIGGER `tr_security_question_ad` AFTER DELETE
ON `security_question` FOR EACH ROW 
INSERT INTO  `security_question_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`question_text`,
`question_group`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`question_text`,
OLD.`question_group`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for site
CREATE TABLE  IF NOT EXISTS `site_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`organisation_id` int(10) unsigned,
`name` varchar(100),
`site_number` varchar(45),
`default_brake_test_class_1_and_2_id` smallint(5) unsigned,
`default_service_brake_test_class_3_and_above_id` smallint(5) unsigned,
`default_parking_brake_test_class_3_and_above_id` smallint(5) unsigned,
`last_site_assessment_id` int(10) unsigned,
`dual_language` tinyint(3) unsigned,
`scottish_bank_holiday` tinyint(3) unsigned,
`latitude` decimal(8,5),
`longitude` decimal(8,5),
`type_id` smallint(5) unsigned,
`transition_status_id` smallint(5) unsigned,
`non_working_day_country_lookup_id` smallint(5) unsigned,
`first_login_by` int(10) unsigned,
`first_login_on` datetime(6),
`first_test_carried_out_by` int(10) unsigned,
`first_test_carried_out_number` int(10) unsigned,
`first_test_carried_out_on` datetime(6),
`first_live_test_carried_out_by` int(10) unsigned,
`first_live_test_carried_out_number` int(10) unsigned,
`first_live_test_carried_out_on` datetime(6),
`mot1_details_updated_on` datetime(6),
`mot1_vts_device_status_id` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_site (`id`,`version`),
  INDEX ix_site_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_site_ai`;
CREATE TRIGGER `tr_site_ai` AFTER INSERT
ON `site` FOR EACH ROW
INSERT INTO  `site_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_site_au`;
CREATE TRIGGER `tr_site_au` AFTER UPDATE
ON `site` FOR EACH ROW 
INSERT INTO  `site_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`organisation_id`,
`name`,
`site_number`,
`default_brake_test_class_1_and_2_id`,
`default_service_brake_test_class_3_and_above_id`,
`default_parking_brake_test_class_3_and_above_id`,
`last_site_assessment_id`,
`dual_language`,
`scottish_bank_holiday`,
`latitude`,
`longitude`,
`type_id`,
`transition_status_id`,
`non_working_day_country_lookup_id`,
`first_login_by`,
`first_login_on`,
`first_test_carried_out_by`,
`first_test_carried_out_number`,
`first_test_carried_out_on`,
`first_live_test_carried_out_by`,
`first_live_test_carried_out_number`,
`first_live_test_carried_out_on`,
`mot1_details_updated_on`,
`mot1_vts_device_status_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`organisation_id`,
OLD.`name`,
OLD.`site_number`,
OLD.`default_brake_test_class_1_and_2_id`,
OLD.`default_service_brake_test_class_3_and_above_id`,
OLD.`default_parking_brake_test_class_3_and_above_id`,
OLD.`last_site_assessment_id`,
OLD.`dual_language`,
OLD.`scottish_bank_holiday`,
OLD.`latitude`,
OLD.`longitude`,
OLD.`type_id`,
OLD.`transition_status_id`,
OLD.`non_working_day_country_lookup_id`,
OLD.`first_login_by`,
OLD.`first_login_on`,
OLD.`first_test_carried_out_by`,
OLD.`first_test_carried_out_number`,
OLD.`first_test_carried_out_on`,
OLD.`first_live_test_carried_out_by`,
OLD.`first_live_test_carried_out_number`,
OLD.`first_live_test_carried_out_on`,
OLD.`mot1_details_updated_on`,
OLD.`mot1_vts_device_status_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_site_ad`;
CREATE TRIGGER `tr_site_ad` AFTER DELETE
ON `site` FOR EACH ROW 
INSERT INTO  `site_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`organisation_id`,
`name`,
`site_number`,
`default_brake_test_class_1_and_2_id`,
`default_service_brake_test_class_3_and_above_id`,
`default_parking_brake_test_class_3_and_above_id`,
`last_site_assessment_id`,
`dual_language`,
`scottish_bank_holiday`,
`latitude`,
`longitude`,
`type_id`,
`transition_status_id`,
`non_working_day_country_lookup_id`,
`first_login_by`,
`first_login_on`,
`first_test_carried_out_by`,
`first_test_carried_out_number`,
`first_test_carried_out_on`,
`first_live_test_carried_out_by`,
`first_live_test_carried_out_number`,
`first_live_test_carried_out_on`,
`mot1_details_updated_on`,
`mot1_vts_device_status_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`organisation_id`,
OLD.`name`,
OLD.`site_number`,
OLD.`default_brake_test_class_1_and_2_id`,
OLD.`default_service_brake_test_class_3_and_above_id`,
OLD.`default_parking_brake_test_class_3_and_above_id`,
OLD.`last_site_assessment_id`,
OLD.`dual_language`,
OLD.`scottish_bank_holiday`,
OLD.`latitude`,
OLD.`longitude`,
OLD.`type_id`,
OLD.`transition_status_id`,
OLD.`non_working_day_country_lookup_id`,
OLD.`first_login_by`,
OLD.`first_login_on`,
OLD.`first_test_carried_out_by`,
OLD.`first_test_carried_out_number`,
OLD.`first_test_carried_out_on`,
OLD.`first_live_test_carried_out_by`,
OLD.`first_live_test_carried_out_number`,
OLD.`first_live_test_carried_out_on`,
OLD.`mot1_details_updated_on`,
OLD.`mot1_vts_device_status_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for site_assembly_role_map
CREATE TABLE  IF NOT EXISTS `site_assembly_role_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`site_id` int(10) unsigned,
`assembly_id` int(10) unsigned,
`assembly_role_id` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_site_assembly_role_map (`id`,`version`),
  INDEX ix_site_assembly_role_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_site_assembly_role_map_ai`;
CREATE TRIGGER `tr_site_assembly_role_map_ai` AFTER INSERT
ON `site_assembly_role_map` FOR EACH ROW
INSERT INTO  `site_assembly_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_site_assembly_role_map_au`;
CREATE TRIGGER `tr_site_assembly_role_map_au` AFTER UPDATE
ON `site_assembly_role_map` FOR EACH ROW 
INSERT INTO  `site_assembly_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_id`,
`assembly_id`,
`assembly_role_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`site_id`,
OLD.`assembly_id`,
OLD.`assembly_role_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_site_assembly_role_map_ad`;
CREATE TRIGGER `tr_site_assembly_role_map_ad` AFTER DELETE
ON `site_assembly_role_map` FOR EACH ROW 
INSERT INTO  `site_assembly_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_id`,
`assembly_id`,
`assembly_role_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`site_id`,
OLD.`assembly_id`,
OLD.`assembly_role_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for site_business_role
CREATE TABLE  IF NOT EXISTS `site_business_role_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`role_id` int(10) unsigned,
`code` varchar(40),
`name` varchar(50),
`description` varchar(250),
`organisation_type_id` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_site_business_role (`id`,`version`),
  INDEX ix_site_business_role_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_site_business_role_ai`;
CREATE TRIGGER `tr_site_business_role_ai` AFTER INSERT
ON `site_business_role` FOR EACH ROW
INSERT INTO  `site_business_role_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_site_business_role_au`;
CREATE TRIGGER `tr_site_business_role_au` AFTER UPDATE
ON `site_business_role` FOR EACH ROW 
INSERT INTO  `site_business_role_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`role_id`,
`code`,
`name`,
`description`,
`organisation_type_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`role_id`,
OLD.`code`,
OLD.`name`,
OLD.`description`,
OLD.`organisation_type_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_site_business_role_ad`;
CREATE TRIGGER `tr_site_business_role_ad` AFTER DELETE
ON `site_business_role` FOR EACH ROW 
INSERT INTO  `site_business_role_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`role_id`,
`code`,
`name`,
`description`,
`organisation_type_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`role_id`,
OLD.`code`,
OLD.`name`,
OLD.`description`,
OLD.`organisation_type_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for site_business_role_map
CREATE TABLE  IF NOT EXISTS `site_business_role_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`site_id` int(10) unsigned,
`person_id` int(10) unsigned,
`site_business_role_id` smallint(5) unsigned,
`status_id` smallint(5) unsigned,
`status_changed_on` datetime(6),
`valid_from` datetime(6),
`expiry_date` datetime(6),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_site_business_role_map (`id`,`version`),
  INDEX ix_site_business_role_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_site_business_role_map_ai`;
CREATE TRIGGER `tr_site_business_role_map_ai` AFTER INSERT
ON `site_business_role_map` FOR EACH ROW
INSERT INTO  `site_business_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_site_business_role_map_au`;
CREATE TRIGGER `tr_site_business_role_map_au` AFTER UPDATE
ON `site_business_role_map` FOR EACH ROW 
INSERT INTO  `site_business_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_id`,
`person_id`,
`site_business_role_id`,
`status_id`,
`status_changed_on`,
`valid_from`,
`expiry_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`site_id`,
OLD.`person_id`,
OLD.`site_business_role_id`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`valid_from`,
OLD.`expiry_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_site_business_role_map_ad`;
CREATE TRIGGER `tr_site_business_role_map_ad` AFTER DELETE
ON `site_business_role_map` FOR EACH ROW 
INSERT INTO  `site_business_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_id`,
`person_id`,
`site_business_role_id`,
`status_id`,
`status_changed_on`,
`valid_from`,
`expiry_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`site_id`,
OLD.`person_id`,
OLD.`site_business_role_id`,
OLD.`status_id`,
OLD.`status_changed_on`,
OLD.`valid_from`,
OLD.`expiry_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for site_comment_map
CREATE TABLE  IF NOT EXISTS `site_comment_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`site_id` int(10) unsigned,
`comment_id` bigint(20) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_site_comment_map (`id`,`version`),
  INDEX ix_site_comment_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_site_comment_map_ai`;
CREATE TRIGGER `tr_site_comment_map_ai` AFTER INSERT
ON `site_comment_map` FOR EACH ROW
INSERT INTO  `site_comment_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_site_comment_map_au`;
CREATE TRIGGER `tr_site_comment_map_au` AFTER UPDATE
ON `site_comment_map` FOR EACH ROW 
INSERT INTO  `site_comment_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_id`,
`comment_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`site_id`,
OLD.`comment_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_site_comment_map_ad`;
CREATE TRIGGER `tr_site_comment_map_ad` AFTER DELETE
ON `site_comment_map` FOR EACH ROW 
INSERT INTO  `site_comment_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_id`,
`comment_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`site_id`,
OLD.`comment_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for site_condition_approval
CREATE TABLE  IF NOT EXISTS `site_condition_approval_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`site_id` int(10) unsigned,
`interviewed_name` varchar(100),
`interviewed_grade` varchar(100),
`visit_date` date,
`fuel_id` smallint(5) unsigned,
`atl_mode` tinyint(4),
`optl_mode` tinyint(4),
`comment_id` bigint(20) unsigned,
`ve_name` varchar(100),
`ve_grade` varchar(100),
`approval_date` date,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_site_condition_approval (`id`,`version`),
  INDEX ix_site_condition_approval_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_site_condition_approval_ai`;
CREATE TRIGGER `tr_site_condition_approval_ai` AFTER INSERT
ON `site_condition_approval` FOR EACH ROW
INSERT INTO  `site_condition_approval_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_site_condition_approval_au`;
CREATE TRIGGER `tr_site_condition_approval_au` AFTER UPDATE
ON `site_condition_approval` FOR EACH ROW 
INSERT INTO  `site_condition_approval_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_id`,
`interviewed_name`,
`interviewed_grade`,
`visit_date`,
`fuel_id`,
`atl_mode`,
`optl_mode`,
`comment_id`,
`ve_name`,
`ve_grade`,
`approval_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`site_id`,
OLD.`interviewed_name`,
OLD.`interviewed_grade`,
OLD.`visit_date`,
OLD.`fuel_id`,
OLD.`atl_mode`,
OLD.`optl_mode`,
OLD.`comment_id`,
OLD.`ve_name`,
OLD.`ve_grade`,
OLD.`approval_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_site_condition_approval_ad`;
CREATE TRIGGER `tr_site_condition_approval_ad` AFTER DELETE
ON `site_condition_approval` FOR EACH ROW 
INSERT INTO  `site_condition_approval_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_id`,
`interviewed_name`,
`interviewed_grade`,
`visit_date`,
`fuel_id`,
`atl_mode`,
`optl_mode`,
`comment_id`,
`ve_name`,
`ve_grade`,
`approval_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`site_id`,
OLD.`interviewed_name`,
OLD.`interviewed_grade`,
OLD.`visit_date`,
OLD.`fuel_id`,
OLD.`atl_mode`,
OLD.`optl_mode`,
OLD.`comment_id`,
OLD.`ve_name`,
OLD.`ve_grade`,
OLD.`approval_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for site_contact_detail_map
CREATE TABLE  IF NOT EXISTS `site_contact_detail_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`site_contact_type_id` smallint(5) unsigned,
`site_id` int(10) unsigned,
`contact_detail_id` int(10) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_site_contact_detail_map (`id`,`version`),
  INDEX ix_site_contact_detail_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_site_contact_detail_map_ai`;
CREATE TRIGGER `tr_site_contact_detail_map_ai` AFTER INSERT
ON `site_contact_detail_map` FOR EACH ROW
INSERT INTO  `site_contact_detail_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_site_contact_detail_map_au`;
CREATE TRIGGER `tr_site_contact_detail_map_au` AFTER UPDATE
ON `site_contact_detail_map` FOR EACH ROW 
INSERT INTO  `site_contact_detail_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_contact_type_id`,
`site_id`,
`contact_detail_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`site_contact_type_id`,
OLD.`site_id`,
OLD.`contact_detail_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_site_contact_detail_map_ad`;
CREATE TRIGGER `tr_site_contact_detail_map_ad` AFTER DELETE
ON `site_contact_detail_map` FOR EACH ROW 
INSERT INTO  `site_contact_detail_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_contact_type_id`,
`site_id`,
`contact_detail_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`site_contact_type_id`,
OLD.`site_id`,
OLD.`contact_detail_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for site_contact_type
CREATE TABLE  IF NOT EXISTS `site_contact_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_site_contact_type (`id`,`version`),
  INDEX ix_site_contact_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_site_contact_type_ai`;
CREATE TRIGGER `tr_site_contact_type_ai` AFTER INSERT
ON `site_contact_type` FOR EACH ROW
INSERT INTO  `site_contact_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_site_contact_type_au`;
CREATE TRIGGER `tr_site_contact_type_au` AFTER UPDATE
ON `site_contact_type` FOR EACH ROW 
INSERT INTO  `site_contact_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_site_contact_type_ad`;
CREATE TRIGGER `tr_site_contact_type_ad` AFTER DELETE
ON `site_contact_type` FOR EACH ROW 
INSERT INTO  `site_contact_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for site_emergency_log_map
CREATE TABLE  IF NOT EXISTS `site_emergency_log_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(11),
`site_id` int(10) unsigned,
`emergency_log_id` int(10) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_site_emergency_log_map (`id`,`version`),
  INDEX ix_site_emergency_log_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_site_emergency_log_map_ai`;
CREATE TRIGGER `tr_site_emergency_log_map_ai` AFTER INSERT
ON `site_emergency_log_map` FOR EACH ROW
INSERT INTO  `site_emergency_log_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_site_emergency_log_map_au`;
CREATE TRIGGER `tr_site_emergency_log_map_au` AFTER UPDATE
ON `site_emergency_log_map` FOR EACH ROW 
INSERT INTO  `site_emergency_log_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_id`,
`emergency_log_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`site_id`,
OLD.`emergency_log_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_site_emergency_log_map_ad`;
CREATE TRIGGER `tr_site_emergency_log_map_ad` AFTER DELETE
ON `site_emergency_log_map` FOR EACH ROW 
INSERT INTO  `site_emergency_log_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_id`,
`emergency_log_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`site_id`,
OLD.`emergency_log_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for site_facility
CREATE TABLE  IF NOT EXISTS `site_facility_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`site_id` int(10) unsigned,
`facility_type_id` int(10) unsigned,
`name` varchar(50),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_site_facility (`id`,`version`),
  INDEX ix_site_facility_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_site_facility_ai`;
CREATE TRIGGER `tr_site_facility_ai` AFTER INSERT
ON `site_facility` FOR EACH ROW
INSERT INTO  `site_facility_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_site_facility_au`;
CREATE TRIGGER `tr_site_facility_au` AFTER UPDATE
ON `site_facility` FOR EACH ROW 
INSERT INTO  `site_facility_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_id`,
`facility_type_id`,
`name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`site_id`,
OLD.`facility_type_id`,
OLD.`name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_site_facility_ad`;
CREATE TRIGGER `tr_site_facility_ad` AFTER DELETE
ON `site_facility` FOR EACH ROW 
INSERT INTO  `site_facility_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_id`,
`facility_type_id`,
`name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`site_id`,
OLD.`facility_type_id`,
OLD.`name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for site_identifying_token_map
CREATE TABLE  IF NOT EXISTS `site_identifying_token_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`site_id` int(10) unsigned,
`identifying_token_id` int(10) unsigned,
`is_assigned_to_person` tinyint(4),
`start_date` datetime(6),
`end_date` datetime(6),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_site_identifying_token_map (`id`,`version`),
  INDEX ix_site_identifying_token_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_site_identifying_token_map_ai`;
CREATE TRIGGER `tr_site_identifying_token_map_ai` AFTER INSERT
ON `site_identifying_token_map` FOR EACH ROW
INSERT INTO  `site_identifying_token_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_site_identifying_token_map_au`;
CREATE TRIGGER `tr_site_identifying_token_map_au` AFTER UPDATE
ON `site_identifying_token_map` FOR EACH ROW 
INSERT INTO  `site_identifying_token_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_id`,
`identifying_token_id`,
`is_assigned_to_person`,
`start_date`,
`end_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`site_id`,
OLD.`identifying_token_id`,
OLD.`is_assigned_to_person`,
OLD.`start_date`,
OLD.`end_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_site_identifying_token_map_ad`;
CREATE TRIGGER `tr_site_identifying_token_map_ad` AFTER DELETE
ON `site_identifying_token_map` FOR EACH ROW 
INSERT INTO  `site_identifying_token_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_id`,
`identifying_token_id`,
`is_assigned_to_person`,
`start_date`,
`end_date`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`site_id`,
OLD.`identifying_token_id`,
OLD.`is_assigned_to_person`,
OLD.`start_date`,
OLD.`end_date`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for site_testing_daily_schedule
CREATE TABLE  IF NOT EXISTS `site_testing_daily_schedule_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`site_id` int(10) unsigned,
`open_time` time,
`close_time` time,
`weekday` tinyint(3) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_site_testing_daily_schedule (`id`,`version`),
  INDEX ix_site_testing_daily_schedule_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_site_testing_daily_schedule_ai`;
CREATE TRIGGER `tr_site_testing_daily_schedule_ai` AFTER INSERT
ON `site_testing_daily_schedule` FOR EACH ROW
INSERT INTO  `site_testing_daily_schedule_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_site_testing_daily_schedule_au`;
CREATE TRIGGER `tr_site_testing_daily_schedule_au` AFTER UPDATE
ON `site_testing_daily_schedule` FOR EACH ROW 
INSERT INTO  `site_testing_daily_schedule_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_id`,
`open_time`,
`close_time`,
`weekday`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`site_id`,
OLD.`open_time`,
OLD.`close_time`,
OLD.`weekday`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_site_testing_daily_schedule_ad`;
CREATE TRIGGER `tr_site_testing_daily_schedule_ad` AFTER DELETE
ON `site_testing_daily_schedule` FOR EACH ROW 
INSERT INTO  `site_testing_daily_schedule_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_id`,
`open_time`,
`close_time`,
`weekday`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`site_id`,
OLD.`open_time`,
OLD.`close_time`,
OLD.`weekday`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for site_type
CREATE TABLE  IF NOT EXISTS `site_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`code` varchar(5),
`name` varchar(50),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_site_type (`id`,`version`),
  INDEX ix_site_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_site_type_ai`;
CREATE TRIGGER `tr_site_type_ai` AFTER INSERT
ON `site_type` FOR EACH ROW
INSERT INTO  `site_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_site_type_au`;
CREATE TRIGGER `tr_site_type_au` AFTER UPDATE
ON `site_type` FOR EACH ROW 
INSERT INTO  `site_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_site_type_ad`;
CREATE TRIGGER `tr_site_type_ad` AFTER DELETE
ON `site_type` FOR EACH ROW 
INSERT INTO  `site_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for special_notice
CREATE TABLE  IF NOT EXISTS `special_notice_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`username` varchar(100),
`person_id` int(10) unsigned,
`special_notice_content_id` int(10) unsigned,
`is_acknowledged` tinyint(4) unsigned,
`is_deleted` tinyint(4) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_special_notice (`id`,`version`),
  INDEX ix_special_notice_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_special_notice_ai`;
CREATE TRIGGER `tr_special_notice_ai` AFTER INSERT
ON `special_notice` FOR EACH ROW
INSERT INTO  `special_notice_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_special_notice_au`;
CREATE TRIGGER `tr_special_notice_au` AFTER UPDATE
ON `special_notice` FOR EACH ROW 
INSERT INTO  `special_notice_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`username`,
`person_id`,
`special_notice_content_id`,
`is_acknowledged`,
`is_deleted`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`username`,
OLD.`person_id`,
OLD.`special_notice_content_id`,
OLD.`is_acknowledged`,
OLD.`is_deleted`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_special_notice_ad`;
CREATE TRIGGER `tr_special_notice_ad` AFTER DELETE
ON `special_notice` FOR EACH ROW 
INSERT INTO  `special_notice_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`username`,
`person_id`,
`special_notice_content_id`,
`is_acknowledged`,
`is_deleted`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`username`,
OLD.`person_id`,
OLD.`special_notice_content_id`,
OLD.`is_acknowledged`,
OLD.`is_deleted`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for special_notice_audience
CREATE TABLE  IF NOT EXISTS `special_notice_audience_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`special_notice_content_id` int(10) unsigned,
`special_notice_audience_type_id` smallint(5) unsigned,
`vehicle_class_id` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_special_notice_audience (`id`,`version`),
  INDEX ix_special_notice_audience_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_special_notice_audience_ai`;
CREATE TRIGGER `tr_special_notice_audience_ai` AFTER INSERT
ON `special_notice_audience` FOR EACH ROW
INSERT INTO  `special_notice_audience_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_special_notice_audience_au`;
CREATE TRIGGER `tr_special_notice_audience_au` AFTER UPDATE
ON `special_notice_audience` FOR EACH ROW 
INSERT INTO  `special_notice_audience_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`special_notice_content_id`,
`special_notice_audience_type_id`,
`vehicle_class_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`special_notice_content_id`,
OLD.`special_notice_audience_type_id`,
OLD.`vehicle_class_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_special_notice_audience_ad`;
CREATE TRIGGER `tr_special_notice_audience_ad` AFTER DELETE
ON `special_notice_audience` FOR EACH ROW 
INSERT INTO  `special_notice_audience_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`special_notice_content_id`,
`special_notice_audience_type_id`,
`vehicle_class_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`special_notice_content_id`,
OLD.`special_notice_audience_type_id`,
OLD.`vehicle_class_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for special_notice_audience_type
CREATE TABLE  IF NOT EXISTS `special_notice_audience_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`code` varchar(5),
`name` varchar(50),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_special_notice_audience_type (`id`,`version`),
  INDEX ix_special_notice_audience_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_special_notice_audience_type_ai`;
CREATE TRIGGER `tr_special_notice_audience_type_ai` AFTER INSERT
ON `special_notice_audience_type` FOR EACH ROW
INSERT INTO  `special_notice_audience_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_special_notice_audience_type_au`;
CREATE TRIGGER `tr_special_notice_audience_type_au` AFTER UPDATE
ON `special_notice_audience_type` FOR EACH ROW 
INSERT INTO  `special_notice_audience_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_special_notice_audience_type_ad`;
CREATE TRIGGER `tr_special_notice_audience_type_ad` AFTER DELETE
ON `special_notice_audience_type` FOR EACH ROW 
INSERT INTO  `special_notice_audience_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for special_notice_content
CREATE TABLE  IF NOT EXISTS `special_notice_content_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`title` varchar(100),
`issue_number` int(10) unsigned,
`issue_year` int(10) unsigned,
`issue_date` datetime(6),
`expiry_date` datetime(6),
`internal_publish_date` datetime(6),
`external_publish_date` datetime(6),
`notice_text` text,
`acknowledge_within` smallint(5) unsigned,
`is_published` tinyint(4) unsigned,
`is_deleted` tinyint(4) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_special_notice_content (`id`,`version`),
  INDEX ix_special_notice_content_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_special_notice_content_ai`;
CREATE TRIGGER `tr_special_notice_content_ai` AFTER INSERT
ON `special_notice_content` FOR EACH ROW
INSERT INTO  `special_notice_content_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_special_notice_content_au`;
CREATE TRIGGER `tr_special_notice_content_au` AFTER UPDATE
ON `special_notice_content` FOR EACH ROW 
INSERT INTO  `special_notice_content_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`title`,
`issue_number`,
`issue_year`,
`issue_date`,
`expiry_date`,
`internal_publish_date`,
`external_publish_date`,
`notice_text`,
`acknowledge_within`,
`is_published`,
`is_deleted`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`title`,
OLD.`issue_number`,
OLD.`issue_year`,
OLD.`issue_date`,
OLD.`expiry_date`,
OLD.`internal_publish_date`,
OLD.`external_publish_date`,
OLD.`notice_text`,
OLD.`acknowledge_within`,
OLD.`is_published`,
OLD.`is_deleted`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_special_notice_content_ad`;
CREATE TRIGGER `tr_special_notice_content_ad` AFTER DELETE
ON `special_notice_content` FOR EACH ROW 
INSERT INTO  `special_notice_content_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`title`,
`issue_number`,
`issue_year`,
`issue_date`,
`expiry_date`,
`internal_publish_date`,
`external_publish_date`,
`notice_text`,
`acknowledge_within`,
`is_published`,
`is_deleted`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`title`,
OLD.`issue_number`,
OLD.`issue_year`,
OLD.`issue_date`,
OLD.`expiry_date`,
OLD.`internal_publish_date`,
OLD.`external_publish_date`,
OLD.`notice_text`,
OLD.`acknowledge_within`,
OLD.`is_published`,
OLD.`is_deleted`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for special_notice_content_role_map
CREATE TABLE  IF NOT EXISTS `special_notice_content_role_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`special_notice_content_id` int(10) unsigned,
`role_id` int(10) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_special_notice_content_role_map (`id`,`version`),
  INDEX ix_special_notice_content_role_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_special_notice_content_role_map_ai`;
CREATE TRIGGER `tr_special_notice_content_role_map_ai` AFTER INSERT
ON `special_notice_content_role_map` FOR EACH ROW
INSERT INTO  `special_notice_content_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_special_notice_content_role_map_au`;
CREATE TRIGGER `tr_special_notice_content_role_map_au` AFTER UPDATE
ON `special_notice_content_role_map` FOR EACH ROW 
INSERT INTO  `special_notice_content_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`special_notice_content_id`,
`role_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`special_notice_content_id`,
OLD.`role_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_special_notice_content_role_map_ad`;
CREATE TRIGGER `tr_special_notice_content_role_map_ad` AFTER DELETE
ON `special_notice_content_role_map` FOR EACH ROW 
INSERT INTO  `special_notice_content_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`special_notice_content_id`,
`role_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`special_notice_content_id`,
OLD.`role_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for test_item_category
CREATE TABLE  IF NOT EXISTS `test_item_category_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`parent_test_item_category_id` int(10) unsigned,
`section_test_item_category_id` int(10) unsigned,
`business_rule_id` int(10) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_test_item_category (`id`,`version`),
  INDEX ix_test_item_category_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_test_item_category_ai`;
CREATE TRIGGER `tr_test_item_category_ai` AFTER INSERT
ON `test_item_category` FOR EACH ROW
INSERT INTO  `test_item_category_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_test_item_category_au`;
CREATE TRIGGER `tr_test_item_category_au` AFTER UPDATE
ON `test_item_category` FOR EACH ROW 
INSERT INTO  `test_item_category_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`parent_test_item_category_id`,
`section_test_item_category_id`,
`business_rule_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`parent_test_item_category_id`,
OLD.`section_test_item_category_id`,
OLD.`business_rule_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_test_item_category_ad`;
CREATE TRIGGER `tr_test_item_category_ad` AFTER DELETE
ON `test_item_category` FOR EACH ROW 
INSERT INTO  `test_item_category_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`parent_test_item_category_id`,
`section_test_item_category_id`,
`business_rule_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`parent_test_item_category_id`,
OLD.`section_test_item_category_id`,
OLD.`business_rule_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for test_item_category_vehicle_class_map
CREATE TABLE  IF NOT EXISTS `test_item_category_vehicle_class_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`test_item_category_id` int(10) unsigned,
`vehicle_class_id` smallint(5) unsigned,
`business_rule_id` int(10) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_test_item_category_vehicle_class_map (`id`,`version`),
  INDEX ix_test_item_category_vehicle_class_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_test_item_category_vehicle_class_map_ai`;
CREATE TRIGGER `tr_test_item_category_vehicle_class_map_ai` AFTER INSERT
ON `test_item_category_vehicle_class_map` FOR EACH ROW
INSERT INTO  `test_item_category_vehicle_class_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_test_item_category_vehicle_class_map_au`;
CREATE TRIGGER `tr_test_item_category_vehicle_class_map_au` AFTER UPDATE
ON `test_item_category_vehicle_class_map` FOR EACH ROW 
INSERT INTO  `test_item_category_vehicle_class_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`test_item_category_id`,
`vehicle_class_id`,
`business_rule_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`test_item_category_id`,
OLD.`vehicle_class_id`,
OLD.`business_rule_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_test_item_category_vehicle_class_map_ad`;
CREATE TRIGGER `tr_test_item_category_vehicle_class_map_ad` AFTER DELETE
ON `test_item_category_vehicle_class_map` FOR EACH ROW 
INSERT INTO  `test_item_category_vehicle_class_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`test_item_category_id`,
`vehicle_class_id`,
`business_rule_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`test_item_category_id`,
OLD.`vehicle_class_id`,
OLD.`business_rule_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for test_slot_transaction
CREATE TABLE  IF NOT EXISTS `test_slot_transaction_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`slots` int(10) unsigned,
`real_slots` int(10) unsigned,
`slots_after` int(10) unsigned,
`status_id` smallint(5) unsigned,
`payment_id` int(10) unsigned,
`state` varchar(43),
`sales_reference` varchar(55),
`organisation_id` int(10) unsigned,
`completed_on` datetime(6),
`created` datetime(6),
`created_by_username` varchar(100),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_test_slot_transaction (`id`,`version`),
  INDEX ix_test_slot_transaction_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_test_slot_transaction_ai`;
CREATE TRIGGER `tr_test_slot_transaction_ai` AFTER INSERT
ON `test_slot_transaction` FOR EACH ROW
INSERT INTO  `test_slot_transaction_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_test_slot_transaction_au`;
CREATE TRIGGER `tr_test_slot_transaction_au` AFTER UPDATE
ON `test_slot_transaction` FOR EACH ROW 
INSERT INTO  `test_slot_transaction_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`slots`,
`real_slots`,
`slots_after`,
`status_id`,
`payment_id`,
`state`,
`sales_reference`,
`organisation_id`,
`completed_on`,
`created`,
`created_by_username`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`slots`,
OLD.`real_slots`,
OLD.`slots_after`,
OLD.`status_id`,
OLD.`payment_id`,
OLD.`state`,
OLD.`sales_reference`,
OLD.`organisation_id`,
OLD.`completed_on`,
OLD.`created`,
OLD.`created_by_username`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_test_slot_transaction_ad`;
CREATE TRIGGER `tr_test_slot_transaction_ad` AFTER DELETE
ON `test_slot_transaction` FOR EACH ROW 
INSERT INTO  `test_slot_transaction_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`slots`,
`real_slots`,
`slots_after`,
`status_id`,
`payment_id`,
`state`,
`sales_reference`,
`organisation_id`,
`completed_on`,
`created`,
`created_by_username`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`slots`,
OLD.`real_slots`,
OLD.`slots_after`,
OLD.`status_id`,
OLD.`payment_id`,
OLD.`state`,
OLD.`sales_reference`,
OLD.`organisation_id`,
OLD.`completed_on`,
OLD.`created`,
OLD.`created_by_username`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for test_slot_transaction_amendment
CREATE TABLE  IF NOT EXISTS `test_slot_transaction_amendment_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`organisation_id` int(10) unsigned,
`test_slot_transaction_id` int(10) unsigned,
`type_id` smallint(5) unsigned,
`reason_id` smallint(5) unsigned,
`slots` smallint(5),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
`mot1_legacy_id` varchar(80),
  PRIMARY KEY (`hist_id`),
  INDEX uq_test_slot_transaction_amendment (`id`,`version`),
  INDEX ix_test_slot_transaction_amendment_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_test_slot_transaction_amendment_ai`;
CREATE TRIGGER `tr_test_slot_transaction_amendment_ai` AFTER INSERT
ON `test_slot_transaction_amendment` FOR EACH ROW
INSERT INTO  `test_slot_transaction_amendment_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_test_slot_transaction_amendment_au`;
CREATE TRIGGER `tr_test_slot_transaction_amendment_au` AFTER UPDATE
ON `test_slot_transaction_amendment` FOR EACH ROW 
INSERT INTO  `test_slot_transaction_amendment_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`organisation_id`,
`test_slot_transaction_id`,
`type_id`,
`reason_id`,
`slots`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`,
`mot1_legacy_id`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`organisation_id`,
OLD.`test_slot_transaction_id`,
OLD.`type_id`,
OLD.`reason_id`,
OLD.`slots`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`,
OLD.`mot1_legacy_id`);

DROP TRIGGER IF EXISTS `tr_test_slot_transaction_amendment_ad`;
CREATE TRIGGER `tr_test_slot_transaction_amendment_ad` AFTER DELETE
ON `test_slot_transaction_amendment` FOR EACH ROW 
INSERT INTO  `test_slot_transaction_amendment_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`organisation_id`,
`test_slot_transaction_id`,
`type_id`,
`reason_id`,
`slots`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`,
`mot1_legacy_id`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`organisation_id`,
OLD.`test_slot_transaction_id`,
OLD.`type_id`,
OLD.`reason_id`,
OLD.`slots`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`,
OLD.`mot1_legacy_id`);


-- Create history table and update trigger for test_slot_transaction_amendment_reason
CREATE TABLE  IF NOT EXISTS `test_slot_transaction_amendment_reason_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`code` varchar(5),
`description` varchar(75),
`display_order` smallint(5) unsigned,
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
`mot1_legacy_id` varchar(80),
  PRIMARY KEY (`hist_id`),
  INDEX uq_test_slot_transaction_amendment_reason (`id`,`version`),
  INDEX ix_test_slot_transaction_amendment_reason_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_test_slot_transaction_amendment_reason_ai`;
CREATE TRIGGER `tr_test_slot_transaction_amendment_reason_ai` AFTER INSERT
ON `test_slot_transaction_amendment_reason` FOR EACH ROW
INSERT INTO  `test_slot_transaction_amendment_reason_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_test_slot_transaction_amendment_reason_au`;
CREATE TRIGGER `tr_test_slot_transaction_amendment_reason_au` AFTER UPDATE
ON `test_slot_transaction_amendment_reason` FOR EACH ROW 
INSERT INTO  `test_slot_transaction_amendment_reason_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`description`,
`display_order`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`,
`mot1_legacy_id`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`code`,
OLD.`description`,
OLD.`display_order`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`,
OLD.`mot1_legacy_id`);

DROP TRIGGER IF EXISTS `tr_test_slot_transaction_amendment_reason_ad`;
CREATE TRIGGER `tr_test_slot_transaction_amendment_reason_ad` AFTER DELETE
ON `test_slot_transaction_amendment_reason` FOR EACH ROW 
INSERT INTO  `test_slot_transaction_amendment_reason_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`description`,
`display_order`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`,
`mot1_legacy_id`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`code`,
OLD.`description`,
OLD.`display_order`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`,
OLD.`mot1_legacy_id`);


-- Create history table and update trigger for test_slot_transaction_amendment_type
CREATE TABLE  IF NOT EXISTS `test_slot_transaction_amendment_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`code` varchar(5),
`title` varchar(75),
`is_active` tinyint(1),
`display_order` smallint(5) unsigned,
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
`mot1_legacy_id` varchar(80),
  PRIMARY KEY (`hist_id`),
  INDEX uq_test_slot_transaction_amendment_type (`id`,`version`),
  INDEX ix_test_slot_transaction_amendment_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_test_slot_transaction_amendment_type_ai`;
CREATE TRIGGER `tr_test_slot_transaction_amendment_type_ai` AFTER INSERT
ON `test_slot_transaction_amendment_type` FOR EACH ROW
INSERT INTO  `test_slot_transaction_amendment_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_test_slot_transaction_amendment_type_au`;
CREATE TRIGGER `tr_test_slot_transaction_amendment_type_au` AFTER UPDATE
ON `test_slot_transaction_amendment_type` FOR EACH ROW 
INSERT INTO  `test_slot_transaction_amendment_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`title`,
`is_active`,
`display_order`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`,
`mot1_legacy_id`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`code`,
OLD.`title`,
OLD.`is_active`,
OLD.`display_order`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`,
OLD.`mot1_legacy_id`);

DROP TRIGGER IF EXISTS `tr_test_slot_transaction_amendment_type_ad`;
CREATE TRIGGER `tr_test_slot_transaction_amendment_type_ad` AFTER DELETE
ON `test_slot_transaction_amendment_type` FOR EACH ROW 
INSERT INTO  `test_slot_transaction_amendment_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`title`,
`is_active`,
`display_order`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`,
`mot1_legacy_id`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`code`,
OLD.`title`,
OLD.`is_active`,
OLD.`display_order`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`,
OLD.`mot1_legacy_id`);


-- Create history table and update trigger for test_slot_transaction_status
CREATE TABLE  IF NOT EXISTS `test_slot_transaction_status_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_test_slot_transaction_status (`id`,`version`),
  INDEX ix_test_slot_transaction_status_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_test_slot_transaction_status_ai`;
CREATE TRIGGER `tr_test_slot_transaction_status_ai` AFTER INSERT
ON `test_slot_transaction_status` FOR EACH ROW
INSERT INTO  `test_slot_transaction_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_test_slot_transaction_status_au`;
CREATE TRIGGER `tr_test_slot_transaction_status_au` AFTER UPDATE
ON `test_slot_transaction_status` FOR EACH ROW 
INSERT INTO  `test_slot_transaction_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_test_slot_transaction_status_ad`;
CREATE TRIGGER `tr_test_slot_transaction_status_ad` AFTER DELETE
ON `test_slot_transaction_status` FOR EACH ROW 
INSERT INTO  `test_slot_transaction_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for title
CREATE TABLE  IF NOT EXISTS `title_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_title (`id`,`version`),
  INDEX ix_title_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_title_ai`;
CREATE TRIGGER `tr_title_ai` AFTER INSERT
ON `title` FOR EACH ROW
INSERT INTO  `title_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_title_au`;
CREATE TRIGGER `tr_title_au` AFTER UPDATE
ON `title` FOR EACH ROW 
INSERT INTO  `title_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_title_ad`;
CREATE TRIGGER `tr_title_ad` AFTER DELETE
ON `title` FOR EACH ROW 
INSERT INTO  `title_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for ti_category_language_content_map
CREATE TABLE  IF NOT EXISTS `ti_category_language_content_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`test_item_category_id` int(10) unsigned,
`language_lookup_id` smallint(5) unsigned,
`name` varchar(100),
`description` varchar(100),
`display_order` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_ti_category_language_content_map (`id`,`version`),
  INDEX ix_ti_category_language_content_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_ti_category_language_content_map_ai`;
CREATE TRIGGER `tr_ti_category_language_content_map_ai` AFTER INSERT
ON `ti_category_language_content_map` FOR EACH ROW
INSERT INTO  `ti_category_language_content_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_ti_category_language_content_map_au`;
CREATE TRIGGER `tr_ti_category_language_content_map_au` AFTER UPDATE
ON `ti_category_language_content_map` FOR EACH ROW 
INSERT INTO  `ti_category_language_content_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`test_item_category_id`,
`language_lookup_id`,
`name`,
`description`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`test_item_category_id`,
OLD.`language_lookup_id`,
OLD.`name`,
OLD.`description`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_ti_category_language_content_map_ad`;
CREATE TRIGGER `tr_ti_category_language_content_map_ad` AFTER DELETE
ON `ti_category_language_content_map` FOR EACH ROW 
INSERT INTO  `ti_category_language_content_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`test_item_category_id`,
`language_lookup_id`,
`name`,
`description`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`test_item_category_id`,
OLD.`language_lookup_id`,
OLD.`name`,
OLD.`description`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for token_lookup
CREATE TABLE  IF NOT EXISTS `token_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_token_lookup (`id`,`version`),
  INDEX ix_token_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_token_lookup_ai`;
CREATE TRIGGER `tr_token_lookup_ai` AFTER INSERT
ON `token_lookup` FOR EACH ROW
INSERT INTO  `token_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_token_lookup_au`;
CREATE TRIGGER `tr_token_lookup_au` AFTER UPDATE
ON `token_lookup` FOR EACH ROW 
INSERT INTO  `token_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_token_lookup_ad`;
CREATE TRIGGER `tr_token_lookup_ad` AFTER DELETE
ON `token_lookup` FOR EACH ROW 
INSERT INTO  `token_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for transition_status
CREATE TABLE  IF NOT EXISTS `transition_status_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(30),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_transition_status (`id`,`version`),
  INDEX ix_transition_status_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_transition_status_ai`;
CREATE TRIGGER `tr_transition_status_ai` AFTER INSERT
ON `transition_status` FOR EACH ROW
INSERT INTO  `transition_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_transition_status_au`;
CREATE TRIGGER `tr_transition_status_au` AFTER UPDATE
ON `transition_status` FOR EACH ROW 
INSERT INTO  `transition_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_transition_status_ad`;
CREATE TRIGGER `tr_transition_status_ad` AFTER DELETE
ON `transition_status` FOR EACH ROW 
INSERT INTO  `transition_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for transmission_type
CREATE TABLE  IF NOT EXISTS `transmission_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`display_order` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_transmission_type (`id`,`version`),
  INDEX ix_transmission_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_transmission_type_ai`;
CREATE TRIGGER `tr_transmission_type_ai` AFTER INSERT
ON `transmission_type` FOR EACH ROW
INSERT INTO  `transmission_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_transmission_type_au`;
CREATE TRIGGER `tr_transmission_type_au` AFTER UPDATE
ON `transmission_type` FOR EACH ROW 
INSERT INTO  `transmission_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_transmission_type_ad`;
CREATE TRIGGER `tr_transmission_type_ad` AFTER DELETE
ON `transmission_type` FOR EACH ROW 
INSERT INTO  `transmission_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for url_type
CREATE TABLE  IF NOT EXISTS `url_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`max_count` smallint(6) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_url_type (`id`,`version`),
  INDEX ix_url_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_url_type_ai`;
CREATE TRIGGER `tr_url_type_ai` AFTER INSERT
ON `url_type` FOR EACH ROW
INSERT INTO  `url_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_url_type_au`;
CREATE TRIGGER `tr_url_type_au` AFTER UPDATE
ON `url_type` FOR EACH ROW 
INSERT INTO  `url_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`max_count`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`max_count`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_url_type_ad`;
CREATE TRIGGER `tr_url_type_ad` AFTER DELETE
ON `url_type` FOR EACH ROW 
INSERT INTO  `url_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`max_count`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`max_count`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for vehicle
CREATE TABLE  IF NOT EXISTS `vehicle_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`registration` varchar(20),
`registration_collapsed` varchar(20),
`empty_vrm_reason_id` smallint(5) unsigned,
`vin` varchar(30),
`vin_reversed` varchar(30),
`vin_collapsed` varchar(30),
`vin_collapsed_reversed` varchar(30),
`empty_vin_reason_id` smallint(5) unsigned,
`vehicle_class_id` smallint(5) unsigned,
`make_id` int(10) unsigned,
`model_id` int(10) unsigned,
`model_detail_id` int(10) unsigned,
`body_type_id` smallint(5) unsigned,
`year` year(4),
`make_name` varchar(50),
`model_name` varchar(50),
`model_detail_name` varchar(50),
`manufacture_date` date,
`first_registration_date` date,
`first_used_date` date,
`primary_colour_id` smallint(5) unsigned,
`secondary_colour_id` smallint(5) unsigned,
`fuel_type_id` smallint(5) unsigned,
`wheelplan_type_id` smallint(5) unsigned,
`seating_capacity` smallint(5) unsigned,
`no_of_seat_belts` smallint(5) unsigned,
`seat_belts_last_checked` date,
`weight` int(10) unsigned,
`weight_source_id` smallint(5) unsigned,
`country_of_registration_id` smallint(5) unsigned,
`cylinder_capacity` int(10) unsigned,
`transmission_type_id` smallint(5) unsigned,
`sva_emission_std` varchar(10),
`engine_number` varchar(30),
`chassis_number` varchar(30),
`is_new_at_first_reg` tinyint(4) unsigned,
`eu_classification` varchar(2),
`mass_in_service_weight` int(9) unsigned,
`dvla_vehicle_id` int(11),
`is_damaged` tinyint(4),
`is_destroyed` tinyint(4),
`is_incognito` tinyint(4) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_vehicle (`id`,`version`),
  INDEX ix_vehicle_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_vehicle_ai`;
CREATE TRIGGER `tr_vehicle_ai` AFTER INSERT
ON `vehicle` FOR EACH ROW
INSERT INTO  `vehicle_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_vehicle_au`;
CREATE TRIGGER `tr_vehicle_au` AFTER UPDATE
ON `vehicle` FOR EACH ROW 
INSERT INTO  `vehicle_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`registration`,
`registration_collapsed`,
`empty_vrm_reason_id`,
`vin`,
`vin_reversed`,
`vin_collapsed`,
`vin_collapsed_reversed`,
`empty_vin_reason_id`,
`vehicle_class_id`,
`make_id`,
`model_id`,
`model_detail_id`,
`body_type_id`,
`year`,
`make_name`,
`model_name`,
`model_detail_name`,
`manufacture_date`,
`first_registration_date`,
`first_used_date`,
`primary_colour_id`,
`secondary_colour_id`,
`fuel_type_id`,
`wheelplan_type_id`,
`seating_capacity`,
`no_of_seat_belts`,
`seat_belts_last_checked`,
`weight`,
`weight_source_id`,
`country_of_registration_id`,
`cylinder_capacity`,
`transmission_type_id`,
`sva_emission_std`,
`engine_number`,
`chassis_number`,
`is_new_at_first_reg`,
`eu_classification`,
`mass_in_service_weight`,
`dvla_vehicle_id`,
`is_damaged`,
`is_destroyed`,
`is_incognito`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`registration`,
OLD.`registration_collapsed`,
OLD.`empty_vrm_reason_id`,
OLD.`vin`,
OLD.`vin_reversed`,
OLD.`vin_collapsed`,
OLD.`vin_collapsed_reversed`,
OLD.`empty_vin_reason_id`,
OLD.`vehicle_class_id`,
OLD.`make_id`,
OLD.`model_id`,
OLD.`model_detail_id`,
OLD.`body_type_id`,
OLD.`year`,
OLD.`make_name`,
OLD.`model_name`,
OLD.`model_detail_name`,
OLD.`manufacture_date`,
OLD.`first_registration_date`,
OLD.`first_used_date`,
OLD.`primary_colour_id`,
OLD.`secondary_colour_id`,
OLD.`fuel_type_id`,
OLD.`wheelplan_type_id`,
OLD.`seating_capacity`,
OLD.`no_of_seat_belts`,
OLD.`seat_belts_last_checked`,
OLD.`weight`,
OLD.`weight_source_id`,
OLD.`country_of_registration_id`,
OLD.`cylinder_capacity`,
OLD.`transmission_type_id`,
OLD.`sva_emission_std`,
OLD.`engine_number`,
OLD.`chassis_number`,
OLD.`is_new_at_first_reg`,
OLD.`eu_classification`,
OLD.`mass_in_service_weight`,
OLD.`dvla_vehicle_id`,
OLD.`is_damaged`,
OLD.`is_destroyed`,
OLD.`is_incognito`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_vehicle_ad`;
CREATE TRIGGER `tr_vehicle_ad` AFTER DELETE
ON `vehicle` FOR EACH ROW 
INSERT INTO  `vehicle_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`registration`,
`registration_collapsed`,
`empty_vrm_reason_id`,
`vin`,
`vin_reversed`,
`vin_collapsed`,
`vin_collapsed_reversed`,
`empty_vin_reason_id`,
`vehicle_class_id`,
`make_id`,
`model_id`,
`model_detail_id`,
`body_type_id`,
`year`,
`make_name`,
`model_name`,
`model_detail_name`,
`manufacture_date`,
`first_registration_date`,
`first_used_date`,
`primary_colour_id`,
`secondary_colour_id`,
`fuel_type_id`,
`wheelplan_type_id`,
`seating_capacity`,
`no_of_seat_belts`,
`seat_belts_last_checked`,
`weight`,
`weight_source_id`,
`country_of_registration_id`,
`cylinder_capacity`,
`transmission_type_id`,
`sva_emission_std`,
`engine_number`,
`chassis_number`,
`is_new_at_first_reg`,
`eu_classification`,
`mass_in_service_weight`,
`dvla_vehicle_id`,
`is_damaged`,
`is_destroyed`,
`is_incognito`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`registration`,
OLD.`registration_collapsed`,
OLD.`empty_vrm_reason_id`,
OLD.`vin`,
OLD.`vin_reversed`,
OLD.`vin_collapsed`,
OLD.`vin_collapsed_reversed`,
OLD.`empty_vin_reason_id`,
OLD.`vehicle_class_id`,
OLD.`make_id`,
OLD.`model_id`,
OLD.`model_detail_id`,
OLD.`body_type_id`,
OLD.`year`,
OLD.`make_name`,
OLD.`model_name`,
OLD.`model_detail_name`,
OLD.`manufacture_date`,
OLD.`first_registration_date`,
OLD.`first_used_date`,
OLD.`primary_colour_id`,
OLD.`secondary_colour_id`,
OLD.`fuel_type_id`,
OLD.`wheelplan_type_id`,
OLD.`seating_capacity`,
OLD.`no_of_seat_belts`,
OLD.`seat_belts_last_checked`,
OLD.`weight`,
OLD.`weight_source_id`,
OLD.`country_of_registration_id`,
OLD.`cylinder_capacity`,
OLD.`transmission_type_id`,
OLD.`sva_emission_std`,
OLD.`engine_number`,
OLD.`chassis_number`,
OLD.`is_new_at_first_reg`,
OLD.`eu_classification`,
OLD.`mass_in_service_weight`,
OLD.`dvla_vehicle_id`,
OLD.`is_damaged`,
OLD.`is_destroyed`,
OLD.`is_incognito`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for vehicle_class
CREATE TABLE  IF NOT EXISTS `vehicle_class_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`vehicle_class_group_id` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_vehicle_class (`id`,`version`),
  INDEX ix_vehicle_class_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_vehicle_class_ai`;
CREATE TRIGGER `tr_vehicle_class_ai` AFTER INSERT
ON `vehicle_class` FOR EACH ROW
INSERT INTO  `vehicle_class_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_vehicle_class_au`;
CREATE TRIGGER `tr_vehicle_class_au` AFTER UPDATE
ON `vehicle_class` FOR EACH ROW 
INSERT INTO  `vehicle_class_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`vehicle_class_group_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`vehicle_class_group_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_vehicle_class_ad`;
CREATE TRIGGER `tr_vehicle_class_ad` AFTER DELETE
ON `vehicle_class` FOR EACH ROW 
INSERT INTO  `vehicle_class_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`vehicle_class_group_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`vehicle_class_group_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for vehicle_class_group
CREATE TABLE  IF NOT EXISTS `vehicle_class_group_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`name` varchar(50),
`code` varchar(5),
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_vehicle_class_group (`id`,`version`),
  INDEX ix_vehicle_class_group_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_vehicle_class_group_ai`;
CREATE TRIGGER `tr_vehicle_class_group_ai` AFTER INSERT
ON `vehicle_class_group` FOR EACH ROW
INSERT INTO  `vehicle_class_group_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_vehicle_class_group_au`;
CREATE TRIGGER `tr_vehicle_class_group_au` AFTER UPDATE
ON `vehicle_class_group` FOR EACH ROW 
INSERT INTO  `vehicle_class_group_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_vehicle_class_group_ad`;
CREATE TRIGGER `tr_vehicle_class_group_ad` AFTER DELETE
ON `vehicle_class_group` FOR EACH ROW 
INSERT INTO  `vehicle_class_group_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`name`,
OLD.`code`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for vehicle_v5c
CREATE TABLE  IF NOT EXISTS `vehicle_v5c_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(11),
`vehicle_id` int(10) unsigned,
`v5c_ref` varchar(11),
`first_seen` date,
`last_seen` date,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_vehicle_v5c (`id`,`version`),
  INDEX ix_vehicle_v5c_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_vehicle_v5c_ai`;
CREATE TRIGGER `tr_vehicle_v5c_ai` AFTER INSERT
ON `vehicle_v5c` FOR EACH ROW
INSERT INTO  `vehicle_v5c_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_vehicle_v5c_au`;
CREATE TRIGGER `tr_vehicle_v5c_au` AFTER UPDATE
ON `vehicle_v5c` FOR EACH ROW 
INSERT INTO  `vehicle_v5c_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`vehicle_id`,
`v5c_ref`,
`first_seen`,
`last_seen`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`vehicle_id`,
OLD.`v5c_ref`,
OLD.`first_seen`,
OLD.`last_seen`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_vehicle_v5c_ad`;
CREATE TRIGGER `tr_vehicle_v5c_ad` AFTER DELETE
ON `vehicle_v5c` FOR EACH ROW 
INSERT INTO  `vehicle_v5c_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`vehicle_id`,
`v5c_ref`,
`first_seen`,
`last_seen`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`vehicle_id`,
OLD.`v5c_ref`,
OLD.`first_seen`,
OLD.`last_seen`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for visit
CREATE TABLE  IF NOT EXISTS `visit_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` int(10) unsigned,
`site_id` int(10) unsigned,
`visit_date` date,
`visit_reason_id` smallint(5) unsigned,
`visit_outcome_id` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_visit (`id`,`version`),
  INDEX ix_visit_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_visit_ai`;
CREATE TRIGGER `tr_visit_ai` AFTER INSERT
ON `visit` FOR EACH ROW
INSERT INTO  `visit_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_visit_au`;
CREATE TRIGGER `tr_visit_au` AFTER UPDATE
ON `visit` FOR EACH ROW 
INSERT INTO  `visit_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_id`,
`visit_date`,
`visit_reason_id`,
`visit_outcome_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`site_id`,
OLD.`visit_date`,
OLD.`visit_reason_id`,
OLD.`visit_outcome_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_visit_ad`;
CREATE TRIGGER `tr_visit_ad` AFTER DELETE
ON `visit` FOR EACH ROW 
INSERT INTO  `visit_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`site_id`,
`visit_date`,
`visit_reason_id`,
`visit_outcome_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`site_id`,
OLD.`visit_date`,
OLD.`visit_reason_id`,
OLD.`visit_outcome_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for visit_reason_lookup
CREATE TABLE  IF NOT EXISTS `visit_reason_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`reason` varchar(80),
`display_order` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_visit_reason_lookup (`id`,`version`),
  INDEX ix_visit_reason_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_visit_reason_lookup_ai`;
CREATE TRIGGER `tr_visit_reason_lookup_ai` AFTER INSERT
ON `visit_reason_lookup` FOR EACH ROW
INSERT INTO  `visit_reason_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_visit_reason_lookup_au`;
CREATE TRIGGER `tr_visit_reason_lookup_au` AFTER UPDATE
ON `visit_reason_lookup` FOR EACH ROW 
INSERT INTO  `visit_reason_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`reason`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`reason`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_visit_reason_lookup_ad`;
CREATE TRIGGER `tr_visit_reason_lookup_ad` AFTER DELETE
ON `visit_reason_lookup` FOR EACH ROW 
INSERT INTO  `visit_reason_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`reason`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`reason`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for weight_source_lookup
CREATE TABLE  IF NOT EXISTS `weight_source_lookup_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`code` varchar(5),
`name` varchar(50),
`description` varchar(250),
`display_order` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_weight_source_lookup (`id`,`version`),
  INDEX ix_weight_source_lookup_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_weight_source_lookup_ai`;
CREATE TRIGGER `tr_weight_source_lookup_ai` AFTER INSERT
ON `weight_source_lookup` FOR EACH ROW
INSERT INTO  `weight_source_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_weight_source_lookup_au`;
CREATE TRIGGER `tr_weight_source_lookup_au` AFTER UPDATE
ON `weight_source_lookup` FOR EACH ROW 
INSERT INTO  `weight_source_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`description`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`description`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_weight_source_lookup_ad`;
CREATE TRIGGER `tr_weight_source_lookup_ad` AFTER DELETE
ON `weight_source_lookup` FOR EACH ROW 
INSERT INTO  `weight_source_lookup_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`description`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`description`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


-- Create history table and update trigger for wheelplan_type
CREATE TABLE  IF NOT EXISTS `wheelplan_type_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(5) unsigned,
`code` varchar(5),
`name` varchar(50),
`description` varchar(255),
`display_order` smallint(5) unsigned,
`mot1_legacy_id` varchar(80),
`created_by` int(10) unsigned,
`created_on` datetime(6),
`last_updated_by` int(10) unsigned,
`last_updated_on` datetime(6),
`version` int(10) unsigned,
`batch_number` int(10) unsigned,
  PRIMARY KEY (`hist_id`),
  INDEX uq_wheelplan_type (`id`,`version`),
  INDEX ix_wheelplan_type_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_wheelplan_type_ai`;
CREATE TRIGGER `tr_wheelplan_type_ai` AFTER INSERT
ON `wheelplan_type` FOR EACH ROW
INSERT INTO  `wheelplan_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_wheelplan_type_au`;
CREATE TRIGGER `tr_wheelplan_type_au` AFTER UPDATE
ON `wheelplan_type` FOR EACH ROW 
INSERT INTO  `wheelplan_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`description`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`description`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_wheelplan_type_ad`;
CREATE TRIGGER `tr_wheelplan_type_ad` AFTER DELETE
ON `wheelplan_type` FOR EACH ROW 
INSERT INTO  `wheelplan_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`code`,
`name`,
`description`,
`display_order`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`) 
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`code`,
OLD.`name`,
OLD.`description`,
OLD.`display_order`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


