DROP TRIGGER IF EXISTS `tr_test_slot_transaction_amendment_au`;

CREATE TRIGGER `tr_test_slot_transaction_amendment_au` AFTER UPDATE
ON `test_slot_transaction_amendment` FOR EACH ROW
INSERT INTO  `test_slot_transaction_amendment_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`organisation_id`,
`test_slot_transaction_id`,
`type_id`,
`reason_id`,
`slots`,
`previous_receipt_reference`,
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
OLD.`previous_receipt_reference`,
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
`previous_receipt_reference`,
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
OLD.`previous_receipt_reference`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`,
OLD.`mot1_legacy_id`);