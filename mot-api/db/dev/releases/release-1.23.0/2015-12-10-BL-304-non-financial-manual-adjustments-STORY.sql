SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');


INSERT INTO `payment_type` (`type_name`, `code`, `active`, `display_order`, `is_adjustable`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`)
VALUES("Manual Adjustment", "MANUAL-ADJUSTMENT", 1, 7, 1, @created_by, CURRENT_TIMESTAMP(6), @created_by, CURRENT_TIMESTAMP(6), 1);

set @ammendmentTypeId = (SELECT `id` FROM `test_slot_transaction_amendment_type` WHERE `code` = 'T700');

SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');
INSERT INTO `test_slot_transaction_amendment_reason`
(`code`, `description`, `amendment_type_id`, `display_order`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number`)
VALUES
("MOT01", "Correction of Account (System error)", @ammendmentTypeId, 1, @created_by, CURRENT_TIMESTAMP(6), @created_by, CURRENT_TIMESTAMP(6), 1, 0),
("MOT02", "Refund - Transition Balance", @ammendmentTypeId, 1, @created_by, CURRENT_TIMESTAMP(6), @created_by, CURRENT_TIMESTAMP(6), 1, 0),
("MOT03", "Refund System Error", @ammendmentTypeId, 1, @created_by, CURRENT_TIMESTAMP(6), @created_by, CURRENT_TIMESTAMP(6), 1, 0),
("MOT04", "Other", @ammendmentTypeId, 1, @created_by, CURRENT_TIMESTAMP(6), @created_by, CURRENT_TIMESTAMP(6), 1, 0),
("MOT05", "Temporary Slot Adjustment (Finance)", @ammendmentTypeId, 1, @created_by, CURRENT_TIMESTAMP(6), @created_by, CURRENT_TIMESTAMP(6), 1, 0),
("MOT06", "Permanent Slot Adjustment (Finance)", @ammendmentTypeId, 1, @created_by, CURRENT_TIMESTAMP(6), @created_by, CURRENT_TIMESTAMP(6), 1, 0);


