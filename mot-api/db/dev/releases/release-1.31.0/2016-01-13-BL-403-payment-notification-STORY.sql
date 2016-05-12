SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');

CREATE TABLE IF NOT EXISTS `cpms_notification_status` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,
  `code` VARCHAR(5) NOT NULL,
  `created_by` INT UNSIGNED NOT NULL,
  `created_on` DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` INT UNSIGNED DEFAULT NULL,
  `last_updated_on` DATETIME(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` INT UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ix_cpms_notification_status_code` (`code`),
  KEY `ix_cpms_notification_status_created_by_person_id` (`created_by`),
  KEY `ix_cpms_notification_status_last_updated_by_person_id` (`last_updated_by`),
  CONSTRAINT `ix_cpms_notification_status_created_by_person_id` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `ix_cpms_notification_status_last_updated_by_person_id` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
);

CREATE TABLE IF NOT EXISTS `cpms_notification_status_hist` (
  `hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
  `hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
  `id` BIGINT UNSIGNED DEFAULT NULL,
  `name` VARCHAR(20) NOT NULL,
  `code` VARCHAR(5) NOT NULL,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `created_on` DATETIME(6) DEFAULT NULL,
  `last_updated_by` INT UNSIGNED DEFAULT NULL,
  `last_updated_on` DATETIME(6) DEFAULT NULL,
  `expired_by` INT UNSIGNED DEFAULT NULL,
  `expired_on` DATETIME(6) DEFAULT NULL,
  `version` INT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`hist_id`),
  UNIQUE INDEX uk_cpms_notification_status_hist_id_version (`id`,`version`)
);

CREATE TABLE IF NOT EXISTS `cpms_notification_type` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,
  `code` VARCHAR(5) NOT NULL,
  `created_by` INT UNSIGNED NOT NULL,
  `created_on` DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` INT UNSIGNED DEFAULT NULL,
  `last_updated_on` DATETIME(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` INT UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ix_cpms_notification_type_code` (`code`),
  KEY `ix_cpms_notification_type_created_by_person_id` (`created_by`),
  KEY `ix_cpms_notification_type_last_updated_by_person_id` (`last_updated_by`),
  CONSTRAINT `ix_cpms_notification_type_created_by_person_id` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `ix_cpms_notification_type_last_updated_by_person_id` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
);

CREATE TABLE IF NOT EXISTS `cpms_notification_type_hist` (
  `hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
  `hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
  `id` BIGINT UNSIGNED DEFAULT NULL,
  `name` VARCHAR(20) NOT NULL,
  `code` VARCHAR(5) NOT NULL,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `created_on` DATETIME(6) DEFAULT NULL,
  `last_updated_by` INT UNSIGNED DEFAULT NULL,
  `last_updated_on` DATETIME(6) DEFAULT NULL,
  `expired_by` INT UNSIGNED DEFAULT NULL,
  `expired_on` DATETIME(6) DEFAULT NULL,
  `version` INT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`hist_id`),
  UNIQUE INDEX uk_cpms_notification_type_hist_id_version (`id`,`version`)
);

CREATE TABLE IF NOT EXISTS `cpms_notification_scope` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(30) NOT NULL,
  `code` VARCHAR(20) NOT NULL,
  `created_by` INT UNSIGNED NOT NULL,
  `created_on` DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` INT UNSIGNED DEFAULT NULL,
  `last_updated_on` DATETIME(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` INT UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ix_cpms_notification_scope_code` (`code`),
  KEY `ix_cpms_notification_scope_created_by_person_id` (`created_by`),
  KEY `ix_cpms_notification_scope_last_updated_by_person_id` (`last_updated_by`),
  CONSTRAINT `ix_cpms_notification_scope_created_by_person_id` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `ix_cpms_notification_scope_last_updated_by_person_id` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
);

CREATE TABLE IF NOT EXISTS `cpms_notification_scope_hist` (
  `hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
  `hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
  `id` BIGINT UNSIGNED DEFAULT NULL,
  `name` VARCHAR(30) NOT NULL,
  `code` VARCHAR(20) NOT NULL,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `created_on` DATETIME(6) DEFAULT NULL,
  `last_updated_by` INT UNSIGNED DEFAULT NULL,
  `last_updated_on` DATETIME(6) DEFAULT NULL,
  `expired_by` INT UNSIGNED DEFAULT NULL,
  `expired_on` DATETIME(6) DEFAULT NULL,
  `version` INT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`hist_id`),
  UNIQUE INDEX uk_cpms_notification_scope_hist_id_version (`id`,`version`)
);

CREATE TABLE IF NOT EXISTS `cpms_notification` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `notification_id` VARCHAR (36) NOT NULL COMMENT 'This ID is generated by CPMS, and is independent of any message ID generated by the underlying queueing software',
  `type_id` BIGINT UNSIGNED NOT NULL COMMENT 'Notification type is currently mandate or payment',
  `scope_id` BIGINT UNSIGNED NOT NULL COMMENT 'Payment Scopes is the CPMS term for the different types of payment that are supported by MOT et al',
  `status_id` BIGINT UNSIGNED NOT NULL COMMENT 'This is a status internal to us which stores whether the notification has been processed by us',
  `receipt_reference` VARCHAR (32) COMMENT 'The unique ID of this payment',
  `mandate_reference` VARCHAR (32) COMMENT 'If the payment scope is direct debit, this will contain the reference to the associated mandate',
  `received_count` INT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'The number of times this notification was sent by CPMS (includes the original message and its duplicates)',
  `raw_notification` TEXT NOT NULL COMMENT 'The raw JSON payload from CPMS',
  `created_by` INT UNSIGNED NOT NULL,
  `created_on` DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` INT UNSIGNED DEFAULT NULL,
  `last_updated_on` DATETIME(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` INT UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `notification_id` (`notification_id`),
  KEY `ix_cpms_notification_created_by_person_id` (`created_by`),
  KEY `ix_cpms_notification_last_updated_by_person_id` (`last_updated_by`),
  KEY `ix_cpms_notification_type_id_cpms_notification_type_id` (`type_id`),
  KEY `ix_cpms_notification_scope_id_cpms_notification_scope_id` (`scope_id`),
  KEY `ix_cpms_notification_status_id_cpms_notification_status_id` (`status_id`),
  CONSTRAINT `ix_cpms_notification_created_by_person_id` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `ix_cpms_notification_last_updated_by_person_id` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `ix_cpms_notification_type_id_cpms_notification_type_id` FOREIGN KEY (`type_id`) REFERENCES `cpms_notification_type` (`id`),
  CONSTRAINT `ix_cpms_notification_scope_id_cpms_notification_scope_id` FOREIGN KEY (`scope_id`) REFERENCES `cpms_notification_scope` (`id`),
  CONSTRAINT `ix_cpms_notification_status_id_cpms_notification_status_id` FOREIGN KEY (`status_id`) REFERENCES `cpms_notification_status` (`id`)
);

CREATE TABLE IF NOT EXISTS `cpms_notification_hist` (
  `hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `hist_transaction_type` CHAR(1) NOT NULL DEFAULT 'U',
  `hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
  `id` BIGINT UNSIGNED DEFAULT NULL,
  `notification_id` VARCHAR (36) DEFAULT NULL,
  `type_id` BIGINT UNSIGNED DEFAULT NULL,
  `scope_id` BIGINT UNSIGNED DEFAULT NULL,
  `status_id` BIGINT UNSIGNED DEFAULT NULL,
  `receipt_reference` VARCHAR (32) DEFAULT NULL,
  `mandate_reference` VARCHAR (32) DEFAULT NULL,
  `received_count` INT UNSIGNED DEFAULT NULL,
  `raw_notification` TEXT DEFAULT NULL,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `created_on` DATETIME(6) DEFAULT NULL,
  `last_updated_by` INT UNSIGNED DEFAULT NULL,
  `last_updated_on` DATETIME(6) DEFAULT NULL,
  `expired_by` INT UNSIGNED DEFAULT NULL,
  `expired_on` DATETIME(6) DEFAULT NULL,
  `version` INT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`hist_id`),
  UNIQUE INDEX uk_cpms_notification_hist_id_version (`id`,`version`)
);

-- Create before triggers for cpms_notification
DROP TRIGGER IF EXISTS `tr_cpms_notification_bi`;

DELIMITER $$
CREATE TRIGGER `tr_cpms_notification_bi` BEFORE INSERT
ON `cpms_notification` FOR EACH ROW
  BEGIN
    SET NEW.`version` = 1,
    NEW.`created_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`created_by`),
    NEW.`last_updated_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`last_updated_by`);
  END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_cpms_notification_bu`;

DELIMITER $$
CREATE TRIGGER `tr_cpms_notification_bu` BEFORE UPDATE
ON `cpms_notification` FOR EACH ROW
  BEGIN
    SET NEW.`version` = OLD.`version` + 1,
    NEW.`last_updated_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`last_updated_by`);
  END;
$$
DELIMITER ;

-- Create after triggers for cpms_notification
DROP TRIGGER IF EXISTS `tr_cpms_notification_ai`;

DELIMITER $$
CREATE TRIGGER `tr_cpms_notification_ai` AFTER INSERT
ON `cpms_notification` FOR EACH ROW
  BEGIN
    INSERT INTO `cpms_notification_hist`
    (`id`,
     `notification_id`,
     `type_id`,
     `scope_id`,
     `status_id`,
     `receipt_reference`,
     `mandate_reference`,
     `received_count`,
     `raw_notification`,
     `created_by`,
     `created_on`,
     `last_updated_by`,
     `last_updated_on`,
     `version`)
    VALUES (
      NEW.`id`,
      NEW.`notification_id`,
      NEW.`type_id`,
      NEW.`scope_id`,
      NEW.`status_id`,
      NEW.`receipt_reference`,
      NEW.`mandate_reference`,
      NEW.`received_count`,
      NEW.`raw_notification`,
      NEW.`created_by`,
      NEW.`created_on`,
      NEW.`last_updated_by`,
      NEW.`last_updated_on`,
      NEW.`version`
    );
  END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_cpms_notification_au`;

DELIMITER $$
CREATE TRIGGER `tr_cpms_notification_au` AFTER UPDATE
ON `cpms_notification` FOR EACH ROW
  BEGIN
    UPDATE `cpms_notification_hist`
    SET `expired_by` = @app_user_id,
      `expired_on` = current_timestamp(6)
    WHERE `id` = OLD.`id` and `expired_on` is null;

    INSERT INTO `cpms_notification_hist`
    (`id`,
     `notification_id`,
     `type_id`,
     `scope_id`,
     `status_id`,
     `receipt_reference`,
     `mandate_reference`,
     `received_count`,
     `raw_notification`,
     `created_by`,
     `created_on`,
     `last_updated_by`,
     `last_updated_on`,
     `version`)
    VALUES (
      NEW.`id`,
      NEW.`notification_id`,
      NEW.`type_id`,
      NEW.`scope_id`,
      NEW.`status_id`,
      NEW.`receipt_reference`,
      NEW.`mandate_reference`,
      NEW.`received_count`,
      NEW.`raw_notification`,
      NEW.`created_by`,
      NEW.`created_on`,
      NEW.`last_updated_by`,
      NEW.`last_updated_on`,
      NEW.`version`
    );
  END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_cpms_notification_ad`;

DELIMITER $$
CREATE TRIGGER `tr_cpms_notification_ad` AFTER DELETE
ON `cpms_notification` FOR EACH ROW

  BEGIN
    UPDATE `cpms_notification_hist`
    SET `expired_by` = @app_user_id,
      `expired_on` = current_timestamp(6)
    WHERE `id` = OLD.`id` and `expired_on` is null;
  END;
$$
DELIMITER ;

-- Create before triggers for cpms_notification_status
DROP TRIGGER IF EXISTS `tr_cpms_notification_status_bi`;

DELIMITER $$
CREATE TRIGGER `tr_cpms_notification_status_bi` BEFORE INSERT
ON `cpms_notification_status` FOR EACH ROW
  BEGIN
    SET NEW.`version` = 1,
    NEW.`created_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`created_by`),
    NEW.`last_updated_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`last_updated_by`);
  END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_cpms_notification_status_bu`;

DELIMITER $$
CREATE TRIGGER `tr_cpms_notification_status_bu` BEFORE UPDATE
ON `cpms_notification_status` FOR EACH ROW
  BEGIN
    SET NEW.`version` = OLD.`version` + 1,
    NEW.`last_updated_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`last_updated_by`);
  END;
$$
DELIMITER ;

-- Create after triggers for cpms_notification_status
DELIMITER $$
CREATE TRIGGER `tr_cpms_notification_status_ai` AFTER INSERT
ON `cpms_notification_status` FOR EACH ROW
  BEGIN
    INSERT INTO `cpms_notification_status_hist`
    (`id`,
     `name`,
     `code`,
     `created_by`,
     `created_on`,
     `last_updated_by`,
     `last_updated_on`,
     `version`)
    VALUES (
      NEW.`id`,
      NEW.`name`,
      NEW.`code`,
      NEW.`created_by`,
      NEW.`created_on`,
      NEW.`last_updated_by`,
      NEW.`last_updated_on`,
      NEW.`version`
    );
  END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_cpms_notification_status_au`;

DELIMITER $$
CREATE TRIGGER `tr_cpms_notification_status_au` AFTER UPDATE
ON `cpms_notification_status` FOR EACH ROW
  BEGIN
    UPDATE `cpms_notification_status_hist`
    SET `expired_by` = @app_user_id,
      `expired_on` = current_timestamp(6)
    WHERE `id` = OLD.`id` and `expired_on` is null;

    INSERT INTO `cpms_notification_status_hist`
    (`id`,
     `name`,
     `code`,
     `created_by`,
     `created_on`,
     `last_updated_by`,
     `last_updated_on`,
     `version`)
    VALUES (
      NEW.`id`,
      NEW.`name`,
      NEW.`code`,
      NEW.`created_by`,
      NEW.`created_on`,
      NEW.`last_updated_by`,
      NEW.`last_updated_on`,
      NEW.`version`
    );
  END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_cpms_notification_status_ad`;

DELIMITER $$
CREATE TRIGGER `tr_cpms_notification_status_ad` AFTER DELETE
ON `cpms_notification_status` FOR EACH ROW

  BEGIN
    UPDATE `cpms_notification_status_hist`
    SET `expired_by` = @app_user_id,
      `expired_on` = current_timestamp(6)
    WHERE `id` = OLD.`id` and `expired_on` is null;
  END;
$$
DELIMITER ;

-- Create before triggers for cpms_notification_type
DROP TRIGGER IF EXISTS `tr_cpms_notification_type_bi`;

DELIMITER $$
CREATE TRIGGER `tr_cpms_notification_type_bi` BEFORE INSERT
ON `cpms_notification_type` FOR EACH ROW
  BEGIN
    SET NEW.`version` = 1,
    NEW.`created_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`created_by`),
    NEW.`last_updated_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`last_updated_by`);
  END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_cpms_notification_type_bu`;

DELIMITER $$
CREATE TRIGGER `tr_cpms_notification_type_bu` BEFORE UPDATE
ON `cpms_notification_type` FOR EACH ROW
  BEGIN
    SET NEW.`version` = OLD.`version` + 1,
    NEW.`last_updated_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`last_updated_by`);
  END;
$$
DELIMITER ;

-- Create after triggers for cpms_notification_type
DELIMITER $$
CREATE TRIGGER `tr_cpms_notification_type_ai` AFTER INSERT
ON `cpms_notification_type` FOR EACH ROW
  BEGIN
    INSERT INTO `cpms_notification_type_hist`
    (`id`,
     `name`,
     `code`,
     `created_by`,
     `created_on`,
     `last_updated_by`,
     `last_updated_on`,
     `version`)
    VALUES (
      NEW.`id`,
      NEW.`name`,
      NEW.`code`,
      NEW.`created_by`,
      NEW.`created_on`,
      NEW.`last_updated_by`,
      NEW.`last_updated_on`,
      NEW.`version`
    );
  END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_cpms_notification_type_au`;

DELIMITER $$
CREATE TRIGGER `tr_cpms_notification_type_au` AFTER UPDATE
ON `cpms_notification_type` FOR EACH ROW
  BEGIN
    UPDATE `cpms_notification_type_hist`
    SET `expired_by` = @app_user_id,
      `expired_on` = current_timestamp(6)
    WHERE `id` = OLD.`id` and `expired_on` is null;

    INSERT INTO `cpms_notification_type_hist`
    (`id`,
     `name`,
     `code`,
     `created_by`,
     `created_on`,
     `last_updated_by`,
     `last_updated_on`,
     `version`)
    VALUES (
      NEW.`id`,
      NEW.`name`,
      NEW.`code`,
      NEW.`created_by`,
      NEW.`created_on`,
      NEW.`last_updated_by`,
      NEW.`last_updated_on`,
      NEW.`version`
    );
  END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_cpms_notification_type_ad`;

DELIMITER $$
CREATE TRIGGER `tr_cpms_notification_type_ad` AFTER DELETE
ON `cpms_notification_type` FOR EACH ROW

  BEGIN
    UPDATE `cpms_notification_type_hist`
    SET `expired_by` = @app_user_id,
      `expired_on` = current_timestamp(6)
    WHERE `id` = OLD.`id` and `expired_on` is null;
  END;
$$
DELIMITER ;


-- Create before triggers for cpms_notification_scope
DROP TRIGGER IF EXISTS `tr_cpms_notification_scope_bi`;

DELIMITER $$
CREATE TRIGGER `tr_cpms_notification_scope_bi` BEFORE INSERT
ON `cpms_notification_scope` FOR EACH ROW
  BEGIN
    SET NEW.`version` = 1,
    NEW.`created_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`created_by`),
    NEW.`last_updated_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`last_updated_by`);
  END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_cpms_notification_scope_bu`;

DELIMITER $$
CREATE TRIGGER `tr_cpms_notification_scope_bu` BEFORE UPDATE
ON `cpms_notification_scope` FOR EACH ROW
  BEGIN
    SET NEW.`version` = OLD.`version` + 1,
    NEW.`last_updated_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`last_updated_by`);
  END;
$$
DELIMITER ;

-- Create after triggers for cpms_notification_scope
DELIMITER $$
CREATE TRIGGER `tr_cpms_notification_scope_ai` AFTER INSERT
ON `cpms_notification_scope` FOR EACH ROW
  BEGIN
    INSERT INTO `cpms_notification_scope_hist`
    (`id`,
     `name`,
     `code`,
     `created_by`,
     `created_on`,
     `last_updated_by`,
     `last_updated_on`,
     `version`)
    VALUES (
      NEW.`id`,
      NEW.`name`,
      NEW.`code`,
      NEW.`created_by`,
      NEW.`created_on`,
      NEW.`last_updated_by`,
      NEW.`last_updated_on`,
      NEW.`version`
    );
  END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_cpms_notification_scope_au`;

DELIMITER $$
CREATE TRIGGER `tr_cpms_notification_scope_au` AFTER UPDATE
ON `cpms_notification_scope` FOR EACH ROW
  BEGIN
    UPDATE `cpms_notification_scope_hist`
    SET `expired_by` = @app_user_id,
      `expired_on` = current_timestamp(6)
    WHERE `id` = OLD.`id` and `expired_on` is null;

    INSERT INTO `cpms_notification_scope_hist`
    (`id`,
     `name`,
     `code`,
     `created_by`,
     `created_on`,
     `last_updated_by`,
     `last_updated_on`,
     `version`)
    VALUES (
      NEW.`id`,
      NEW.`name`,
      NEW.`code`,
      NEW.`created_by`,
      NEW.`created_on`,
      NEW.`last_updated_by`,
      NEW.`last_updated_on`,
      NEW.`version`
    );
  END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_cpms_notification_scope_ad`;

DELIMITER $$
CREATE TRIGGER `tr_cpms_notification_scope_ad` AFTER DELETE
ON `cpms_notification_scope` FOR EACH ROW

  BEGIN
    UPDATE `cpms_notification_scope_hist`
    SET `expired_by` = @app_user_id,
      `expired_on` = current_timestamp(6)
    WHERE `id` = OLD.`id` and `expired_on` is null;
  END;
$$
DELIMITER ;


-- insert data into cpms_notification_status
INSERT INTO cpms_notification_status
  (`name`, `code`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
  VALUES
  ('Received', 'R', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('Processed', 'P', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('Rejected', 'RJ', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6))
;

-- insert data into cpms_notification_type
INSERT INTO cpms_notification_type
  (`name`, `code`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
  VALUES
  ('Payment', 'P', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('Mandate', 'M', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6))
;

-- insert data into cpms_notification_scope
INSERT INTO cpms_notification_scope
  (`name`, `code`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
  VALUES
  ('Direct Debit', 'DIRECT_DEBIT', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('Card', 'CARD', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('CNP', 'CNP', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('CHIP PIN', 'CHIP_PIN', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('CASH', 'CASH', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('CHEQUE', 'CHEQUE', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('POSTAL_ORDER', 'POSTAL_ORDER', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('TRANSITION', 'TRANSITION', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('PRE_AUTH', 'PRE_AUTH', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('QUERY_TXN', 'QUERY_TXN', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('REFUND', 'REFUND', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('SETTLE_TXN', 'SETTLE_TXN', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('STORED_CARD', 'STORED_CARD', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('CHARGE_BACK', 'CHARGE_BACK', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('ADJUSTMENT', 'ADJUSTMENT', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('REPORT', 'REPORT', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('CHEQUE_RD', 'CHEQUE_RD', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('DIRECT DEBIT IC', 'DIRECT_DEBIT_IC', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6))
;

