ALTER TABLE `test_slot_transaction_amendment_hist` ADD COLUMN `previous_receipt_reference` VARCHAR(55) DEFAULT NULL
AFTER `slots`, COMMENT 'Payment receipt reference before adjustment';
