/*
SET @last_updated_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data' LIMIT 1);

ALTER TABLE `direct_debit_hist` ADD column `unique_identifier` VARCHAR(8) DEFAULT NULL COMMENT 'Last 8 char of mandate_reference searched on' AFTER `mandate_reference`;
ALTER TABLE `payment_hist` ADD column `unique_identifier` VARCHAR(8) DEFAULT NULL COMMENT 'Last 8 char of receipt reference searched on' AFTER `receipt_reference`;
ALTER TABLE `test_slot_transaction_hist` ADD column `unique_identifier` VARCHAR(8) DEFAULT NULL  COMMENT 'Last 8 char of invoice reference searched on' AFTER `sales_reference`;

UPDATE `direct_debit_hist`
SET
    `unique_identifier` = RIGHT(`mandate_reference`,8),
     `version` = `version` + 1,
    `last_updated_by` = @last_updated_by,
    `last_updated_on` = NOW()
WHERE `mandate_reference` IS NOT NULL;

UPDATE `payment_hist`
SET
    `unique_identifier` = RIGHT(`receipt_reference`,8),
     `version` = `version` + 1,
    `last_updated_by` = @last_updated_by,
    `last_updated_on` = NOW()
WHERE `receipt_reference` IS NOT NULL;

UPDATE `test_slot_transaction_hist`
SET
    `unique_identifier` = RIGHT(`sales_reference`,8),
     `version` = `version` + 1,
    `last_updated_by` = @last_updated_by,
    `last_updated_on` = NOW()
WHERE `sales_reference` IS NOT NULL;
*/
