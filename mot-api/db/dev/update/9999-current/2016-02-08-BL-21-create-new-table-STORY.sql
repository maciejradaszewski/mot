# BL-21
# Creating new table


CREATE TABLE `authorised_examiner_principal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `auth_for_ae_id` int(10) unsigned NOT NULL,
  `contact_detail_id` int(10) unsigned NOT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `middle_name` varchar(45) DEFAULT NULL,
  `family_name` varchar(45) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `original_person_id` int(10) unsigned DEFAULT NULL COMMENT 'This relates to person row that contained the original data',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `ix_aep_auth_for_ae_id` (`auth_for_ae_id`),
  KEY `ix_aep_contact_detail_id` (`contact_detail_id`),
  KEY `ix_aep_person_created_by` (`created_by`),
  KEY `ix_aep_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_aep_auth_for_ae_id_auth_for_ae_id` FOREIGN KEY (`auth_for_ae_id`) REFERENCES `auth_for_ae` (`id`),
  CONSTRAINT `fk_aep_contact_detail_id_contact_detail_id` FOREIGN KEY (`contact_detail_id`) REFERENCES `contact_detail` (`id`),
  CONSTRAINT `fk_aep_created_by_person_id` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_aep_last_updated_by_person_id` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE `authorised_examiner_principal_hist` (
  `hist_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hist_timestamp` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `hist_transaction_type` char(1) NOT NULL DEFAULT 'U',
  `id` int(10) unsigned DEFAULT NULL,
  `auth_for_ae_id` int(10) unsigned DEFAULT NULL,
  `contact_detail_id` int(10) unsigned DEFAULT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `middle_name` varchar(45) DEFAULT NULL,
  `family_name` varchar(45) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `original_person_id` int(10) unsigned DEFAULT NULL COMMENT 'This relates to person row that contained the original data',
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_on` datetime(6) DEFAULT NULL,
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL,
  `version` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`hist_id`),
  KEY `uq_aep` (`id`,`version`)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- triggers
SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');



DROP TRIGGER IF EXISTS `tr_authorised_examiner_principal_bi`;
DELIMITER $$
CREATE TRIGGER `tr_authorised_examiner_principal_bi` BEFORE INSERT
ON `authorised_examiner_principal` FOR EACH ROW  BEGIN
SET
NEW.`version` = 1,
NEW.`created_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`created_by`),
NEW.`last_updated_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`last_updated_by`);
END;
$$
DELIMITER ;



DROP TRIGGER IF EXISTS `tr_authorised_examiner_principal_ai`;
DELIMITER $$
CREATE TRIGGER `tr_authorised_examiner_principal_ai` AFTER INSERT
ON `authorised_examiner_principal`
FOR EACH ROW BEGIN
INSERT INTO  `authorised_examiner_principal_hist` (
`hist_transaction_type`,
`id`,
`auth_for_ae_id`,
`contact_detail_id`,
`first_name`,
`middle_name`,
`family_name`,
`date_of_birth`,
`original_person_id`,
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
 NEW.`auth_for_ae_id`,
 NEW.`contact_detail_id`,
 NEW.`first_name`,
 NEW.`middle_name`,
 NEW.`family_name`,
 NEW.`date_of_birth`,
 NEW.`original_person_id`,
 NEW.`created_by`,
 NEW.`created_on`,
 NEW.`last_updated_by`,
 NEW.`last_updated_on`,
 NEW.`version`
 );
END;
$$
DELIMITER ;



DROP TRIGGER IF EXISTS `tr_authorised_examiner_principal_bu`;
DELIMITER $$
CREATE TRIGGER `tr_authorised_examiner_principal_bu` BEFORE UPDATE
ON `authorised_examiner_principal` FOR EACH ROW  BEGIN
SET
NEW.`version` = OLD.`version` + 1,
NEW.`last_updated_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`last_updated_by`);
END;
$$
DELIMITER ;



DROP TRIGGER IF EXISTS `tr_authorised_examiner_principal_au`;
DELIMITER $$
CREATE TRIGGER `tr_authorised_examiner_principal_au` AFTER UPDATE
ON `authorised_examiner_principal`
FOR EACH ROW
INSERT INTO `authorised_examiner_principal_hist`
(
`hist_transaction_type`,
`id`,
`auth_for_ae_id`,
`contact_detail_id`,
`first_name`,
`middle_name`,
`family_name`,
`date_of_birth`,
`original_person_id`,
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
 NEW.`auth_for_ae_id`,
 NEW.`contact_detail_id`,
 NEW.`first_name`,
 NEW.`middle_name`,
 NEW.`family_name`,
 NEW.`date_of_birth`,
 NEW.`original_person_id`,
 NEW.`created_by`,
 NEW.`created_on`,
 NEW.`last_updated_by`,
 NEW.`last_updated_on`,
 NEW.`version`
);
$$
DELIMITER ;



DROP TRIGGER IF EXISTS `tr_authorised_examiner_principal_ad`;
DELIMITER $$
CREATE TRIGGER `tr_authorised_examiner_principal_ad` AFTER DELETE
ON `authorised_examiner_principal`
FOR EACH ROW
INSERT INTO `authorised_examiner_principal_hist`
(
`hist_transaction_type`,
`id`,
`auth_for_ae_id`,
`contact_detail_id`,
`first_name`,
`middle_name`,
`family_name`,
`date_of_birth`,
`original_person_id`,
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
OLD.`auth_for_ae_id`,
OLD.`contact_detail_id`,
OLD.`first_name`,
OLD.`middle_name`,
OLD.`family_name`,
OLD.`date_of_birth`,
OLD.`original_person_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`
);
$$
DELIMITER ;
