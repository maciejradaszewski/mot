/*

DROP TRIGGER IF EXISTS `tr_payment_type_au`;

CREATE TRIGGER `tr_payment_type_au` AFTER UPDATE ON `payment_type` FOR EACH ROW
INSERT INTO  `payment_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`type_name`,
`active`,
`display_order`,
`is_adjustable`,
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
OLD.`is_adjustable`,
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
`is_adjustable`,
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
OLD.`is_adjustable`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);*/
