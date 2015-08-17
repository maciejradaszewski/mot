ALTER TABLE `test_slot_transaction_amendment` ADD COLUMN `previous_receipt_reference` VARCHAR(55) DEFAULT NULL
AFTER `slots`, COMMENT 'Payment receipt reference before adjustment';