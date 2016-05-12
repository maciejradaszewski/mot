CREATE TABLE IF NOT EXISTS `mot_test_survey_result` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `mot_test_id` BIGINT UNSIGNED NOT NULL,
  `satisfaction_rating` TINYINT DEFAULT NULL,
  `created_by` INT UNSIGNED NOT NULL,
  `created_on` DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` INT unsigned DEFAULT NULL,
  `last_updated_on` DATETIME(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` INT unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_mot_test_id_mot_test_id`
    FOREIGN KEY (`mot_test_id`)
    REFERENCES `mot_test` (`id`)
);

CREATE TABLE IF NOT EXISTS `mot_test_survey_result_hist` (
  `hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `hist_transaction_type` CHAR NOT NULL DEFAULT 'U',
  `hist_batch_number` INT unsigned NOT NULL DEFAULT '0',
  `id` BIGINT UNSIGNED NOT NULL,
  `mot_test_id` BIGINT UNSIGNED NOT NULL,
  `satisfaction_rating` TINYINT DEFAULT NULL,
  `created_by` INT unsigned NOT NULL,
  `created_on` DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` INT unsigned DEFAULT NULL,
  `last_updated_on` DATETIME(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` INT unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`hist_id`)
);

-- triggers
SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');



DROP TRIGGER IF EXISTS `tr_mot_test_survey_result_map_bi`;
DELIMITER $$
CREATE TRIGGER `tr_mot_test_survey_result_map_bi` BEFORE INSERT
ON `mot_test_survey_result` FOR EACH ROW  BEGIN
  SET
  NEW.`version` = 1,
  NEW.`created_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`created_by`),
  NEW.`last_updated_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`last_updated_by`);
END;
$$
DELIMITER ;



DROP TRIGGER IF EXISTS `tr_mot_test_survey_result_map_ai`;
DELIMITER $$
CREATE TRIGGER `tr_mot_test_survey_result_map_ai` AFTER INSERT
ON `mot_test_survey_result`
FOR EACH ROW BEGIN
  INSERT INTO  `mot_test_survey_result_hist` (
    `hist_transaction_type`,
    `id`,
    `mot_test_id`,
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
      NEW.`mot_test_id`,
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



DROP TRIGGER IF EXISTS `tr_mot_test_survey_result_map_bu`;
DELIMITER $$
CREATE TRIGGER `tr_mot_test_survey_result_map_bu` BEFORE UPDATE
ON `mot_test_survey_result` FOR EACH ROW  BEGIN
  SET
  NEW.`version` = OLD.`version` + 1,
  NEW.`last_updated_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`last_updated_by`);
END;
$$
DELIMITER ;



DROP TRIGGER IF EXISTS `tr_mot_test_survey_result_map_au`;
DELIMITER $$
CREATE TRIGGER `tr_mot_test_survey_result_map_au` AFTER UPDATE
ON `mot_test_survey_result`
FOR EACH ROW
  INSERT INTO `mot_test_survey_result_hist`
  (
    `hist_transaction_type`,
    `id`,
    `mot_test_id`,
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
      NEW.`mot_test_id`,
      NEW.`satisfaction_rating`,
      NEW.`created_by`,
      NEW.`created_on`,
      NEW.`last_updated_by`,
      NEW.`last_updated_on`,
      NEW.`version`
    );
$$
DELIMITER ;



DROP TRIGGER IF EXISTS `tr_mot_test_survey_result_map_ad`;
DELIMITER $$
CREATE TRIGGER `tr_mot_test_survey_result_map_ad` AFTER DELETE
ON `mot_test_survey_result`
FOR EACH ROW
  INSERT INTO `mot_test_survey_result_hist`
  (
    `hist_transaction_type`,
    `id`,
    `mot_test_id`,
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
      OLD.`mot_test_id`,
      OLD.`satisfaction_rating`,
      OLD.`created_by`,
      OLD.`created_on`,
      OLD.`last_updated_by`,
      OLD.`last_updated_on`,
      OLD.`version`
    );
$$
DELIMITER ;
