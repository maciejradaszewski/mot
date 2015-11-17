SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');

CREATE TABLE `licence_country_lookup` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `code` VARCHAR(5) NOT NULL,
  `created_by` INT(10) UNSIGNED DEFAULT NULL,
  `created_on` DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_modified_by` INT(10) UNSIGNED DEFAULT NULL,
  `last_modified_on` DATETIME(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` INT(10) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_modified_by` (`last_modified_by`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='Lookup for a licence''s issuing country';

-- Create history table for mot.licence_country_lookup
CREATE TABLE `licence_country_lookup_hist` (
  `hist_id` bigint unsigned not null auto_increment,
  `id` bigint(20) unsigned,
  `name` varchar(50),
  `code` varchar(5),
  `created_by` int(10) unsigned not null,
  `created_on` timestamp(6) null default null,
  `last_modified_by` int(10) unsigned not null,
  `last_modified_on` timestamp(6) null default null,
  `version` int(10) unsigned,
  `expired_by` int unsigned,
  `expired_on` timestamp(6) null default null,
  PRIMARY KEY (`hist_id`),
  UNIQUE INDEX ix_licence_country_lookup_hist_id_version (`id`,`version`)) ENGINE=InnoDB;

-- Create before triggers for licence_country_lookup
DROP TRIGGER IF EXISTS `tr_licence_country_lookup_bi`;

DELIMITER $$
CREATE TRIGGER `tr_licence_country_lookup_bi` BEFORE INSERT
ON `licence_country_lookup` FOR EACH ROW
  BEGIN
    SET NEW.`version` = 1, NEW.`created_by` = @app_user_id, NEW.`last_modified_by` = @app_user_id;
  END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_licence_country_lookup_bu`;

DELIMITER $$
CREATE TRIGGER `tr_licence_country_lookup_bu` BEFORE UPDATE
ON `licence_country_lookup` FOR EACH ROW
  BEGIN
    SET NEW.`version` = OLD.`version` + 1, NEW.`last_modified_by` = @app_user_id;
  END;
$$
DELIMITER ;

-- Create after triggers for licence_country_lookup
DROP TRIGGER IF EXISTS `tr_licence_country_lookup_ai`;

DELIMITER $$
CREATE TRIGGER `tr_licence_country_lookup_ai` AFTER INSERT
ON `licence_country_lookup` FOR EACH ROW

  BEGIN
    INSERT INTO `licence_country_lookup_hist`
    (`id`,
     `name`,
     `code`,
     `created_by`,
     `created_on`,
     `last_modified_by`,
     `last_modified_on`,
     `version`)
    VALUES (NEW.`id`,
            NEW.`name`,
            NEW.`code`,
            NEW.`created_by`,
            NEW.`created_on`,
            NEW.`last_modified_by`,
            NEW.`last_modified_on`,
            NEW.`version`);
  END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_licence_country_lookup_au`;

DELIMITER $$
CREATE TRIGGER `tr_licence_country_lookup_au` AFTER UPDATE
ON `licence_country_lookup` FOR EACH ROW

  BEGIN
    UPDATE `licence_country_lookup_hist`
    SET `expired_by` = @app_user_id,
      `expired_on` = current_timestamp(6)
    WHERE `id` = OLD.`id` and `expired_on` is null;

    INSERT INTO `licence_country_lookup_hist`
    (`id`,
     `name`,
     `code`,
     `created_by`,
     `created_on`,
     `last_modified_by`,
     `last_modified_on`,
     `version`)
    VALUES (NEW.`id`,
            NEW.`name`,
            NEW.`code`,
            NEW.`created_by`,
            NEW.`created_on`,
            NEW.`last_modified_by`,
            NEW.`last_modified_on`,
            NEW.`version`);
  END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_licence_country_lookup_ad`;

DELIMITER $$
CREATE TRIGGER `tr_licence_country_lookup_ad` AFTER DELETE
ON `licence_country_lookup` FOR EACH ROW

  BEGIN
    UPDATE `licence_country_lookup_hist`
    SET `expired_by` = @app_user_id,
      `expired_on` = current_timestamp(6)
    WHERE `id` = OLD.`id` and `expired_on` is null;
  END;
$$
DELIMITER ;

-- Alter mot.licence table with new foreign key, replacing fk_licence_country_lookup_id
ALTER TABLE `licence`
  ADD COLUMN `licence_country_id` BIGINT UNSIGNED AFTER `licence_type_id`,
  ADD CONSTRAINT `fk_licence_licence_country_id`
    FOREIGN KEY (`licence_country_id`)
    REFERENCES `licence_country_lookup` (`id`),
  DROP FOREIGN KEY `fk_licence_country_lookup_id`,
  DROP COLUMN `country_lookup_id`;

-- Alter history table for mot.licence
ALTER TABLE `licence_hist`
ADD COLUMN `licence_country_id` bigint(20) unsigned AFTER `licence_type_id`,
DROP COLUMN `country_lookup_id`;


DROP TRIGGER IF EXISTS `tr_licence_au`;

DELIMITER $$
CREATE TRIGGER `tr_licence_au` AFTER UPDATE
ON `licence` FOR EACH ROW

  BEGIN
    INSERT INTO  `licence_hist` (
      `hist_transaction_type`,
      `hist_batch_number`,
      `id`,
      `licence_number`,
      `licence_type_id`,
      `licence_country_id`,
      `valid_from`,
      `expiry_date`,
      `mot1_legacy_id`,
      `created_by`,
      `created_on`,
      `last_updated_by`,
      `last_updated_on`,
      `version`,
      `batch_number`
    )
    VALUES (
      'U',
      COALESCE(@BATCH_NUMBER,0),
      OLD.`id`,
      OLD.`licence_number`,
      OLD.`licence_type_id`,
      OLD.`licence_country_id`,
      OLD.`valid_from`,
      OLD.`expiry_date`,
      OLD.`mot1_legacy_id`,
      OLD.`created_by`,
      OLD.`created_on`,
      OLD.`last_updated_by`,
      OLD.`last_updated_on`,
      OLD.`version`,
      OLD.`batch_number`
    );
  END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_licence_ad`;

DELIMITER $$
CREATE TRIGGER `tr_licence_ad` AFTER DELETE
ON `licence` FOR EACH ROW

  BEGIN
    INSERT INTO  `licence_hist` (
      `hist_transaction_type`,
      `hist_batch_number`,
      `id`,
      `licence_number`,
      `licence_type_id`,
      `licence_country_id`,
      `valid_from`,
      `expiry_date`,
      `mot1_legacy_id`,
      `created_by`,
      `created_on`,
      `last_updated_by`,
      `last_updated_on`,
      `version`,
      `batch_number`
    )
    VALUES (
      'D',
      COALESCE(@BATCH_NUMBER,0),
      OLD.`id`,
      OLD.`licence_number`,
      OLD.`licence_type_id`,
      OLD.`licence_country_id`,
      OLD.`valid_from`,
      OLD.`expiry_date`,
      OLD.`mot1_legacy_id`,
      OLD.`created_by`,
      OLD.`created_on`,
      OLD.`last_updated_by`,
      OLD.`last_updated_on`,
      OLD.`version`,
      OLD.`batch_number`
    );
  END;
$$
DELIMITER ;

INSERT INTO `licence_country_lookup`(`name`,`code`) VALUES
  ('Great Britain (England, Scotland and Wales)', 'GB'),
  ('Northern Ireland', 'NI'),
  ('Non-United Kingdom', 'NU');

UPDATE `licence` SET `licence_country_id` = (SELECT `id` FROM `licence_country_lookup` WHERE `code` = 'GB');
