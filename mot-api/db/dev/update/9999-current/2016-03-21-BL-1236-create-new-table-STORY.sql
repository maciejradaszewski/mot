# BL-1236
# Creating new table

DROP TABLE IF EXISTS `qualification_award`;
DROP TABLE IF EXISTS `qualification_award_hist`;

CREATE TABLE `qualification_award` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `person_id` int(10) unsigned NOT NULL,
  `site_id` int(10) unsigned DEFAULT NULL,
  `vehicle_class_group_id` int(10) unsigned NOT NULL,
  `certificate_number` varchar(50) DEFAULT NULL,
  `date_of_qualification` date DEFAULT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `ix_qualification_award_person_id` (`person_id`),
  KEY `ix_qualification_award_site_id` (`site_id`),
  KEY `ix_qualification_award_person_created_by` (`created_by`),
  KEY `ix_qualification_award_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_qualification_award_person_id_person_id` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_qualification_award_site_id_site_id` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
  CONSTRAINT `fk_qualification_award_created_by_person_id` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_qualification_award_last_updated_by_person_id` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE `qualification_award_hist` (
  `hist_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hist_timestamp` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `hist_transaction_type` char(1) NOT NULL DEFAULT 'U',
  `id` int(10) unsigned DEFAULT NULL,
  `person_id` int(10) unsigned DEFAULT NULL,
  `site_id` int(10) unsigned DEFAULT NULL,
  `vehicle_class_group_id` int(10) unsigned DEFAULT NULL,
  `certificate_number` varchar(50) DEFAULT NULL,
  `date_of_qualification` date DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_on` datetime(6) DEFAULT NULL,
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL,
  `version` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`hist_id`),
  KEY `uq_qualification_award` (`id`,`version`)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- triggers
SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');



DROP TRIGGER IF EXISTS `tr_qualification_award_bi`;
DELIMITER $$
CREATE TRIGGER `tr_qualification_award_bi` BEFORE INSERT
ON `qualification_award` FOR EACH ROW  BEGIN
SET
NEW.`version` = 1,
NEW.`created_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`created_by`),
NEW.`last_updated_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`last_updated_by`);
END;
$$
DELIMITER ;



DROP TRIGGER IF EXISTS `tr_qualification_award_ai`;
DELIMITER $$
CREATE TRIGGER `tr_qualification_award_ai` AFTER INSERT
ON `qualification_award`
FOR EACH ROW BEGIN
INSERT INTO  `qualification_award_hist` (
`hist_transaction_type`,
`id`,
`person_id`,
`site_id`,
`vehicle_class_group_id`,
`certificate_number`,
`date_of_qualification`,
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
 NEW.`site_id`,
 NEW.`vehicle_class_group_id`,
 NEW.`certificate_number`,
 NEW.`date_of_qualification`,
 NEW.`created_by`,
 NEW.`created_on`,
 NEW.`last_updated_by`,
 NEW.`last_updated_on`,
 NEW.`version`
 );
END;
$$
DELIMITER ;



DROP TRIGGER IF EXISTS `tr_qualification_award_bu`;
DELIMITER $$
CREATE TRIGGER `tr_qualification_award_bu` BEFORE UPDATE
ON `qualification_award` FOR EACH ROW  BEGIN
SET
NEW.`version` = OLD.`version` + 1,
NEW.`last_updated_by` = IF(@app_user_id IS NOT NULL, @app_user_id, NEW.`last_updated_by`);
END;
$$
DELIMITER ;



DROP TRIGGER IF EXISTS `tr_qualification_award_au`;
DELIMITER $$
CREATE TRIGGER `tr_qualification_award_au` AFTER UPDATE
ON `qualification_award`
FOR EACH ROW
INSERT INTO `qualification_award_hist`
(
`hist_transaction_type`,
`id`,
`person_id`,
`site_id`,
`vehicle_class_group_id`,
`certificate_number`,
`date_of_qualification`,
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
 NEW.`site_id`,
 NEW.`vehicle_class_group_id`,
 NEW.`certificate_number`,
 NEW.`date_of_qualification`,
 NEW.`created_by`,
 NEW.`created_on`,
 NEW.`last_updated_by`,
 NEW.`last_updated_on`,
 NEW.`version`
);
$$
DELIMITER ;



DROP TRIGGER IF EXISTS `tr_qualification_award_ad`;
DELIMITER $$
CREATE TRIGGER `tr_qualification_award_ad` AFTER DELETE
ON `qualification_award`
FOR EACH ROW
INSERT INTO `qualification_award_hist`
(
`hist_transaction_type`,
`id`,
`person_id`,
`site_id`,
`vehicle_class_group_id`,
`certificate_number`,
`date_of_qualification`,
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
OLD.`site_id`,
OLD.`vehicle_class_group_id`,
OLD.`certificate_number`,
OLD.`date_of_qualification`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`
);
$$
DELIMITER ;
