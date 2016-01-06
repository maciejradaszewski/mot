ALTER TABlE `payment_type_hist`
ADD COLUMN `code` VARCHAR(40) AFTER `type_name`;
ALTER TABlE `payment_type`
ADD COLUMN `code` VARCHAR(40) AFTER `type_name`;
DROP TRIGGER IF EXISTS tr_payment_type_au;
DROP TRIGGER IF EXISTS tr_payment_type_ad;
CREATE TRIGGER tr_payment_type_au
  AFTER UPDATE ON `payment_type`
  FOR EACH ROW
  INSERT INTO  `payment_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
    `type_name`,
    `code`,
    `active`,
    `display_order`,
    `mot1_legacy_id`,
    `created_by`,
    `created_on`,
    `last_updated_by`,
    `last_updated_on`,
    `version`,
    `batch_number`)
    VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
    OLD.`type_name`,
    OLD.`code`,
    OLD.`active`,
    OLD.`display_order`,
    OLD.`mot1_legacy_id`,
    OLD.`created_by`,
    OLD.`created_on`,
    OLD.`last_updated_by`,
    OLD.`last_updated_on`,
    OLD.`version`,
    OLD.`batch_number`);;
CREATE TRIGGER tr_payment_type_ad
  AFTER DELETE ON `payment_type`
  FOR EACH ROW
    INSERT INTO  `payment_type_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
    `type_name`,
    `code`,
    `active`,
    `display_order`,
    `mot1_legacy_id`,
    `created_by`,
    `created_on`,
    `last_updated_by`,
    `last_updated_on`,
    `version`,
    `batch_number`)
    VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
    OLD.`type_name`,
    OLD.`code`,
    OLD.`active`,
    OLD.`display_order`,
    OLD.`mot1_legacy_id`,
    OLD.`created_by`,
    OLD.`created_on`,
    OLD.`last_updated_by`,
    OLD.`last_updated_on`,
    OLD.`version`,
    OLD.`batch_number`);;
SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');
UPDATE `payment_type`
SET `code`='CARD', `version` = `version` + 1,
 `last_updated_by` = @app_user_id
WHERE `type_name`='Card';
UPDATE `payment_type`
SET `code`='DIRECT-DEBIT', `version` = `version` + 1,
 `last_updated_by` = @app_user_id
WHERE `type_name`='Direct Debit';
UPDATE `payment_type`
SET `code`='CASH', `version` = `version` + 1,
 `last_updated_by` = @app_user_id
WHERE `type_name`='Cash';
UPDATE `payment_type`
SET `code`='CHEQUE', `version` = `version` + 1,
 `last_updated_by` = @app_user_id
WHERE `type_name`='Cheque';
UPDATE `payment_type`
SET `code`='POSTAL-ORDER', `version` = `version` + 1,
 `last_updated_by` = @app_user_id
WHERE `type_name`='Postal Order';
UPDATE `payment_type`
SET `code`='TRANSITION', `version` = `version` + 1,
 `last_updated_by` = @app_user_id
WHERE `type_name`='Transition';


