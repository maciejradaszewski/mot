DROP TRIGGER IF EXISTS `tr_payment_ai`;

CREATE TRIGGER `tr_payment_ai` AFTER INSERT
ON `payment` FOR EACH ROW
INSERT INTO  `payment_hist` (`id`,
`amount`,
`receipt_reference`,
`unique_identifier`,
`payment_details`,
`status_id`,
`type`,
`created`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`)
VALUES (OLD.`id`,
OLD.`amount`,
OLD.`receipt_reference`,
OLD.`unique_identifier`,
OLD.`payment_details`,
OLD.`status_id`,
OLD.`type`,
OLD.`created`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_payment_au`;

CREATE TRIGGER `tr_payment_au` AFTER UPDATE
ON `payment` FOR EACH ROW
INSERT INTO  `payment_hist` (`id`,
`amount`,
`receipt_reference`,
`unique_identifier`,
`payment_details`,
`status_id`,
`type`,
`created`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`)
VALUES (OLD.`id`,
OLD.`amount`,
OLD.`receipt_reference`,
OLD.`unique_identifier`,
OLD.`payment_details`,
OLD.`status_id`,
OLD.`type`,
OLD.`created`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_payment_ad`;

CREATE TRIGGER `tr_payment_ad` AFTER DELETE
ON `payment` FOR EACH ROW
INSERT INTO  `payment_hist` (`id`,
`amount`,
`receipt_reference`,
`unique_identifier`,
`payment_details`,
`status_id`,
`type`,
`created`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`)
VALUES (OLD.`id`,
OLD.`amount`,
OLD.`receipt_reference`,
OLD.`unique_identifier`,
OLD.`payment_details`,
OLD.`status_id`,
OLD.`type`,
OLD.`created`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`);