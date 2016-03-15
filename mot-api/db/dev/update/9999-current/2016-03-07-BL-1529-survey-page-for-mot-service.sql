CREATE TABLE IF NOT EXISTS `survey_result` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `satisfaction_rating` INTEGER DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_on` DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` INT(10) UNSIGNED DEFAULT NULL,
  `last_updated_on` DATETIME(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` INT(10) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `survey_result_hist` (
  `hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
  `id` BIGINT UNSIGNED DEFAULT NULL,
  `satisfaction_rating` INTEGER DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_on` DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` INT(10) UNSIGNED DEFAULT NULL,
  `last_updated_on` DATETIME(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` INT(10) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`hist_id`)
);

-- triggers
SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');



DROP TRIGGER IF EXISTS `tr_survey_result_bi`;
DELIMITER $$
CREATE TRIGGER `tr_survey_result_bi` BEFORE INSERT
ON `survey_result` FOR EACH ROW  BEGIN
  SET
  NEW.`version` = 1,
  NEW.`created_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`created_by`),
  NEW.`last_updated_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`last_updated_by`);
END;
$$
DELIMITER ;



DROP TRIGGER IF EXISTS `tr_survey_result_ai`;
DELIMITER $$
CREATE TRIGGER `tr_survey_result_ai` AFTER INSERT
ON `survey_result`
FOR EACH ROW BEGIN
  INSERT INTO  `survey_result_hist` (
    `hist_transaction_type`,
    `id`,
    `satisfaction_rating`,
    `created_by`,
    `created_on`,
    `last_updated_by`,
    `last_updated_on`,
    `version`
  )
  VALUES
    (
      'I',
      NEW.`id`,
      NEW.`satisfaction_rating`,
      NEW.`created_by`,
      NEW.`created_on`,
      NEW.`last_updated_by`,
      NEW.`last_updated_on`,
      NEW.`version`
    );
END;
$$
DELIMITER ;



DROP TRIGGER IF EXISTS `tr_survey_result_bu`;
DELIMITER $$
CREATE TRIGGER `tr_survey_result_bu` BEFORE UPDATE
ON `survey_result` FOR EACH ROW  BEGIN
  SET
  NEW.`version` = OLD.`version` + 1,
  NEW.`last_updated_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`last_updated_by`);
END;
$$
DELIMITER ;



DROP TRIGGER IF EXISTS `tr_survey_result_au`;
DELIMITER $$
CREATE TRIGGER `tr_survey_result_au` AFTER UPDATE
ON `survey_result`
FOR EACH ROW
  INSERT INTO `survey_result_hist`
  (
    `hist_transaction_type`,
    `id`,
    `satisfaction_rating`,
    `created_by`,
    `created_on`,
    `last_updated_by`,
    `last_updated_on`,
    `version`
  )
  VALUES
    (
      'U',
      NEW.`id`,
      NEW.`satisfaction_rating`,
      NEW.`created_by`,
      NEW.`created_on`,
      NEW.`last_updated_by`,
      NEW.`last_updated_on`,
      NEW.`version`
    );
$$
DELIMITER ;



DROP TRIGGER IF EXISTS `tr_survey_result_ad`;
DELIMITER $$
CREATE TRIGGER `tr_survey_result_ad` AFTER DELETE
ON `survey_result`
FOR EACH ROW
  INSERT INTO `survey_result_hist`
  (
    `hist_transaction_type`,
    `id`,
    `satisfaction_rating`,
    `created_by`,
    `created_on`,
    `last_updated_by`,
    `last_updated_on`,
    `version`
  )
  VALUES
    (
      'D',
      OLD.`id`,
      OLD.`satisfaction_rating`,
      OLD.`created_by`,
      OLD.`created_on`,
      OLD.`last_updated_by`,
      OLD.`last_updated_on`,
      OLD.`version`
    );
$$
DELIMITER ;
