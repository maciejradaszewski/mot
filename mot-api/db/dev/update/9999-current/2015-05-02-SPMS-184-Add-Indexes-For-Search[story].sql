
-- liquibase formatted sql

-- changeset peleodiase:20150416125800

ALTER TABLE `payment` ADD INDEX `ix_payment_receipt_reference_id` (`receipt_reference`, `id`);
ALTER TABLE `test_slot_transaction` ADD INDEX `ix_test_slot_transaction_sales_reference_payment_id` (`sales_reference`, `payment_id`);