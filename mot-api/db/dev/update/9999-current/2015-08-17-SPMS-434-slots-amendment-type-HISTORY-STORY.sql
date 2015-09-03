/*

ALTER TABlE `test_slot_transaction_amendment_reason_hist`
ADD COLUMN `amendment_type_id` SMALLINT UNSIGNED NOT NULL DEFAULT '1' AFTER `description`;


DROP TRIGGER IF EXISTS `tr_test_slot_transaction_amendment_reason_au`;
CREATE TRIGGER `tr_test_slot_transaction_amendment_reason_au` AFTER UPDATE
ON `test_slot_transaction_amendment_reason` FOR EACH ROW
  INSERT INTO  `test_slot_transaction_amendment_reason_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
                                                              `code`,
                                                              `description`,
                                                              `amendment_type_id`,
                                                              `display_order`,
                                                              `created_by`,
                                                              `created_on`,
                                                              `last_updated_by`,
                                                              `last_updated_on`,
                                                              `version`,
                                                              `batch_number`,
                                                              `mot1_legacy_id`)
  VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
          OLD.`code`,
          OLD.`description`,
          OLD.`amendment_type_id`,
          OLD.`display_order`,
          OLD.`created_by`,
          OLD.`created_on`,
          OLD.`last_updated_by`,
          OLD.`last_updated_on`,
          OLD.`version`,
          OLD.`batch_number`,
          OLD.`mot1_legacy_id`);

DROP TRIGGER IF EXISTS `tr_test_slot_transaction_amendment_reason_ad`;
CREATE TRIGGER `tr_test_slot_transaction_amendment_reason_ad` AFTER DELETE
ON `test_slot_transaction_amendment_reason` FOR EACH ROW
  INSERT INTO  `test_slot_transaction_amendment_reason_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
                                                              `code`,
                                                              `description`,
                                                              `amendment_type_id`,
                                                              `display_order`,
                                                              `created_by`,
                                                              `created_on`,
                                                              `last_updated_by`,
                                                              `last_updated_on`,
                                                              `version`,
                                                              `batch_number`,
                                                              `mot1_legacy_id`)
  VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
          OLD.`code`,
          OLD.`description`,
          OLD.`amendment_type_id`,
          OLD.`display_order`,
          OLD.`created_by`,
          OLD.`created_on`,
          OLD.`last_updated_by`,
          OLD.`last_updated_on`,
          OLD.`version`,
          OLD.`batch_number`,
          OLD.`mot1_legacy_id`);


*/