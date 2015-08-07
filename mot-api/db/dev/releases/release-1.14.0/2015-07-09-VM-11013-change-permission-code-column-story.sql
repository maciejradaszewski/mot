-- Permission code column needs to be longer for the next change VM-11013-permissions-for-internal-roles.sql

-- Alter the code column to be bigger
ALTER TABLE `permission` CHANGE COLUMN `code` `code` VARCHAR(50);

ALTER TABLE `permission_hist` CHANGE `code` `code` VARCHAR(50);

-- Create a table that maps permissions to internal roles to determine who can manage what
CREATE TABLE `permission_to_assign_role_map` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_permission_to_assign_role_map` (`role_id`,`permission_id`),
  KEY `fk_assign_role_permission_map_person_created_by` (`created_by`),
  KEY `fk_assign_role_permission_map_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_assign_role_permission_map_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_assign_role_permission_map_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_assign_role_permission_map_permission_id` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`),
  CONSTRAINT `fk_assign_role_permission_map_role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create history table and update trigger for permission_to_assign_role_map
CREATE TABLE  IF NOT EXISTS `permission_to_assign_role_map_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`id` smallint(6) unsigned,
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
  INDEX uq_permission_to_assign_role_map (`id`,`version`),
  INDEX ix_permission_to_assign_role_map_mot1_legacy_id (`mot1_legacy_id`));

DROP TRIGGER IF EXISTS `tr_permission_to_assign_role_map_ai`;
CREATE TRIGGER `tr_permission_to_assign_role_map_ai` AFTER INSERT
ON `permission_to_assign_role_map` FOR EACH ROW
INSERT INTO  `permission_to_assign_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`);

DROP TRIGGER IF EXISTS `tr_permission_to_assign_role_map_au`;
CREATE TRIGGER `tr_permission_to_assign_role_map_au` AFTER UPDATE
ON `permission_to_assign_role_map` FOR EACH ROW
INSERT INTO  `permission_to_assign_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
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

DROP TRIGGER IF EXISTS `tr_permission_to_assign_role_map_ad`;
CREATE TRIGGER `tr_permission_to_assign_role_map_ad` AFTER DELETE
ON `permission_to_assign_role_map` FOR EACH ROW
INSERT INTO  `permission_to_assign_role_map_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
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

