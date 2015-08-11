-- Adding recently added flags to the role table to its history table

ALTER TABLE `role_hist`
ADD COLUMN `is_internal` TINYINT(4) UNSIGNED NULL DEFAULT 0 AFTER `code`,
ADD COLUMN `is_trade` TINYINT(4) UNSIGNED NULL DEFAULT 0 AFTER `is_internal`;


DROP TRIGGER IF EXISTS `tr_role_au`;
CREATE TRIGGER `tr_role_au` AFTER UPDATE
ON `role` FOR EACH ROW
INSERT INTO  `role_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`name`,
`code`,
`is_internal`,
`is_trade`,
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
OLD.`is_internal`,
OLD.`is_trade`,
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
`is_internal`,
`is_trade`,
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
OLD.`is_internal`,
OLD.`is_trade`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);


