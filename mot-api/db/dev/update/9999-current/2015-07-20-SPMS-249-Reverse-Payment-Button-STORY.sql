SET @last_updated_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data' LIMIT 1);

ALTER TABLE `payment_type` ADD column `is_adjustable` TINYINT unsigned NOT NULL DEFAULT 0  COMMENT 'Determines if this payment type can be adjusted' AFTER `display_order`;

UPDATE `payment_type`
SET
    `is_adjustable` = 1,
    `version` = `version` + 1,
    `last_updated_by` = @last_updated_by,
    `last_updated_on` = NOW()
WHERE `type_name` = 'Cheque';

UPDATE `payment_type`
SET
    `is_adjustable` = 0,
    `version` = `version` + 1,
    `last_updated_by` = @last_updated_by,
    `last_updated_on` = NOW()
WHERE `type_name` <> 'Cheque';