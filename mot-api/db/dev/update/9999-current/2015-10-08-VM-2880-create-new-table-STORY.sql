# VM-2880
# Creating new table

SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');

CREATE TABLE `password_detail`
(
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `person_id` int unsigned NOT NULL,
  `password_expiry_notification_sent_date` datetime(6) NOT NULL,
  `created_by` int unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `ix_password_detail_person_id` (`person_id`),
  KEY `ix_password_detail_created_by` (`created_by`),
  KEY `ix_password_detail_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_password_detail_person_id_person_id` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_password_detail_created_by_person_id` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_password_detail_last_updated_by_person_id` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Password details';



CREATE TABLE `password_detail_hist`
(
    `hist_id` bigint unsigned not null auto_increment,
    `hist_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `hist_transaction_type` char(1) NOT NULL DEFAULT 'U',
    `id` int unsigned DEFAULT NULL,
    `person_id` int unsigned DEFAULT NULL,
    `password_expiry_notification_sent_date` datetime DEFAULT NULL,
    `created_by` int unsigned DEFAULT NULL,
    `created_on` datetime(6) DEFAULT NULL,
    `last_updated_by` int  unsigned DEFAULT NULL,
    `last_updated_on` datetime(6) DEFAULT NULL,
    `version` int unsigned DEFAULT NULL,
    PRIMARY KEY (`hist_id`),
    KEY `uq_password_detail` (`id`,`version`)
) ENGINE=InnoDB;



-- Create triggers

DROP TRIGGER IF EXISTS `tr_password_detail_bi`;
DELIMITER $$
CREATE TRIGGER `tr_password_detail_bi` BEFORE INSERT
ON `password_detail` FOR EACH ROW  BEGIN
SET
NEW.`version` = 1,
NEW.`created_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`created_by`),
NEW.`last_updated_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`last_updated_by`);
END;
$$
DELIMITER ;



DROP TRIGGER IF EXISTS `tr_password_detail_ai`;
DELIMITER $$
CREATE TRIGGER `tr_password_detail_ai` AFTER INSERT
ON `password_detail`
FOR EACH ROW BEGIN
INSERT INTO  `password_detail_hist` (
`hist_transaction_type`,
`id`,
`person_id`,
`password_expiry_notification_sent_date`,
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
 NEW.`person_id`,
 NEW.`password_expiry_notification_sent_date`,
 NEW.`created_by`,
 NEW.`created_on`,
 NEW.`last_updated_by`,
 NEW.`last_updated_on`,
 NEW.`version`
 );
END;
$$
DELIMITER ;



DROP TRIGGER IF EXISTS `tr_password_detail_bu`;
DELIMITER $$
CREATE TRIGGER `tr_password_detail_bu` BEFORE UPDATE
ON `password_detail` FOR EACH ROW  BEGIN
SET
NEW.`version` = OLD.`version` + 1,
NEW.`last_updated_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`last_updated_by`);
END;
$$
DELIMITER ;



DROP TRIGGER IF EXISTS `tr_password_detail_au`;
DELIMITER $$
CREATE TRIGGER `tr_password_detail_au` AFTER UPDATE
ON `password_detail`
FOR EACH ROW
INSERT INTO `password_detail_hist`
(
`hist_transaction_type`,
`id`,
`person_id`,
`password_expiry_notification_sent_date`,
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
NEW.`person_id`,
NEW.`password_expiry_notification_sent_date`,
NEW.`created_by`,
NEW.`created_on`,
NEW.`last_updated_by`,
NEW.`last_updated_on`,
NEW.`version`
);
$$
DELIMITER ;



DROP TRIGGER IF EXISTS `tr_password_detail_ad`;
DELIMITER $$
CREATE TRIGGER `tr_password_detail_ad` AFTER DELETE
ON `password_detail`
FOR EACH ROW
INSERT INTO `password_detail_hist`
(
`hist_transaction_type`,
`id`,
`person_id`,
`password_expiry_notification_sent_date`,
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
OLD.`person_id`,
OLD.`password_expiry_notification_sent_date`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`
);
$$
DELIMITER ;
