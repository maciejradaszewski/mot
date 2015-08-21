DROP TRIGGER IF EXISTS `tr_mot_test_bi`;
DROP TRIGGER IF EXISTS `tr_mot_test_custom_bi`;

DELIMITER $$

CREATE TRIGGER `tr_mot_test_custom_bi` BEFORE INSERT ON `mot_test`
FOR EACH ROW
  BEGIN
    DECLARE ACTIVE_STATUS_ID integer DEFAULT 4;
    -- SELECT `id` INTO ACTIVE_STATUS_ID FROM `mot_test_status` WHERE `code`="A";
    IF NEW.`status_id` <> ACTIVE_STATUS_ID AND NEW.`completed_date` IS NULL THEN
      SIGNAL SQLSTATE '10001' SET MESSAGE_TEXT = 'completed_date needs to be set for non ACTIVE mot tests';
    END IF;
    SET NEW.`version` = 1, NEW.`created_by` = @app_user_id, NEW.`last_updated_by` = @app_user_id;
  END
$$

DROP TRIGGER IF EXISTS `tr_mot_test_bu`;
DROP TRIGGER IF EXISTS `tr_mot_test_custom_bu`;

CREATE TRIGGER `tr_mot_test_custom_bu` BEFORE UPDATE ON `mot_test`
FOR EACH ROW
BEGIN
  DECLARE ACTIVE_STATUS_ID integer DEFAULT 4;
  -- SELECT `id` INTO ACTIVE_STATUS_ID FROM `mot_test_status` WHERE `code`="A";
  IF NEW.`status_id` <> ACTIVE_STATUS_ID AND NEW.`completed_date` IS NULL THEN
    SIGNAL SQLSTATE '10001' SET MESSAGE_TEXT = 'completed_date needs to be set for non ACTIVE mot tests';
  END IF;
  SET NEW.`version` = OLD.`version` + 1, NEW.`last_updated_by` = @app_user_id;
END
$$

DELIMITER ;