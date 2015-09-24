ALTER TABLE payment_status_hist ADD `cpms_code` SMALLINT UNSIGNED AFTER `code`, COMMENT 'CPMS code for given status type';

DROP TRIGGER IF EXISTS `tr_payment_status_au`;
CREATE TRIGGER `tr_payment_status_au` AFTER UPDATE
ON `payment_status` FOR EACH ROW
  INSERT INTO `payment_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
                                     `name`,
                                     `code`,
                                     `cpms_code`,
                                     `mot1_legacy_id`,
                                     `created_by`,
                                     `created_on`,
                                     `last_updated_by`,
                                     `last_updated_on`,
                                     `version`,
                                     `batch_number`)
  VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
          OLD.`name`,
          OLD.`code`,
          OLD.`cpms_code`,
          OLD.`mot1_legacy_id`,
          OLD.`created_by`,
          OLD.`created_on`,
          OLD.`last_updated_by`,
          OLD.`last_updated_on`,
          OLD.`version`,
          OLD.`batch_number`);

DROP TRIGGER IF EXISTS `tr_payment_status_ad`;
CREATE TRIGGER `tr_payment_status_ad` AFTER DELETE
ON `payment_status` FOR EACH ROW
  INSERT INTO `payment_status_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
                                     `name`,
                                     `code`,
                                     `cpms_code`,
                                     `mot1_legacy_id`,
                                     `created_by`,
                                     `created_on`,
                                     `last_updated_by`,
                                     `last_updated_on`,
                                     `version`,
                                     `batch_number`)
  VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
          OLD.`name`,
          OLD.`code`,
          OLD.`cpms_code`,
          OLD.`mot1_legacy_id`,
          OLD.`created_by`,
          OLD.`created_on`,
          OLD.`last_updated_by`,
          OLD.`last_updated_on`,
          OLD.`version`,
          OLD.`batch_number`);
