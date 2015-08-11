-- Permission code column needs to be longer for the next change VM-11013-permissions-for-internal-roles.sql
SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR username = 'static data');

-- Alter the code column to be bigger
ALTER TABLE `permission` CHANGE COLUMN `code` `code` VARCHAR(50) CHARACTER SET latin1;

ALTER TABLE `permission_hist` CHANGE COLUMN `code` `code` VARCHAR(50);

-- Create a table that maps permissions to internal roles to determine who can manage what
CREATE TABLE `permission_to_assign_role_map` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `permission_id` INT UNSIGNED NOT NULL,
  `role_id` INT UNSIGNED NOT NULL,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `created_on` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_modified_by` INT UNSIGNED DEFAULT NULL,
  `last_modified_on` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  `version` INT UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_permission_to_assign_role_map` (`role_id`,`permission_id`),
  KEY `ix_permission_to_assign_role_map_person_created_by` (`created_by`),
  KEY `ix_permission_to_assign_role_map_person_last_modified_by` (`last_modified_by`),
  CONSTRAINT `fk_permission_to_assign_role_map_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_permission_to_assign_role_map_person_last_modified_by` FOREIGN KEY (`last_modified_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_permission_to_assign_role_map_permission_id` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`),
  CONSTRAINT `fk_permission_to_assign_role_map_role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`)
) ENGINE=InnoDB COMMENT="A One To One relationship to explain which permission is needed to manage the given role, the user should have this permission granted to them by RBAC";

-- Create history table and update trigger for permission_to_assign_role_map
-- Create history table for mot.permission_to_assign_role_map
CREATE TABLE `permission_to_assign_role_map_hist` (
  `hist_id` bigint unsigned not null auto_increment,
  `id` bigint(20) unsigned,
  `permission_id` int(10) unsigned,
  `role_id` int(10) unsigned,
  `created_by` int(10) unsigned,
  `created_on` timestamp(6),
  `last_modified_by` int(10) unsigned,
  `last_modified_on` timestamp(6),
  `version` int(10) unsigned,
  `expired_by` int unsigned,
  `expired_on` timestamp(6) null default null,
  PRIMARY KEY (`hist_id`),
  UNIQUE INDEX uk_permission_to_assign_role_map_hist_id_version (`id`,`version`)
) ENGINE=InnoDB;

ALTER TABLE `permission_to_assign_role_map_hist` MODIFY `created_by` int unsigned not null, MODIFY `last_modified_by` int unsigned not null;

-- BEFORE TRIGGERS
-- Create before triggers for permission_to_assign_role_map
DROP TRIGGER IF EXISTS `tr_permission_to_assign_role_map_bi`;

DELIMITER $$
CREATE TRIGGER `tr_permission_to_assign_role_map_bi` BEFORE INSERT
ON `permission_to_assign_role_map` FOR EACH ROW
BEGIN
  SET NEW.`version` = 1, NEW.`created_by` = @app_user_id, NEW.`last_modified_by` = @app_user_id;
END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_permission_to_assign_role_map_bu`;

DELIMITER $$
CREATE TRIGGER `tr_permission_to_assign_role_map_bu` BEFORE UPDATE
ON `permission_to_assign_role_map` FOR EACH ROW
BEGIN
  SET NEW.`version` = OLD.`version` + 1, NEW.`last_modified_by` = @app_user_id;
END;
$$
DELIMITER ;

-- AFTER TRIGGERS
DROP TRIGGER IF EXISTS `tr_permission_to_assign_role_map_ai`;

DELIMITER $$
CREATE TRIGGER `tr_permission_to_assign_role_map_ai` AFTER INSERT
ON `permission_to_assign_role_map` FOR EACH ROW

BEGIN
INSERT INTO `permission_to_assign_role_map_hist`
(`id`,
`permission_id`,
`role_id`,
`created_by`,
`created_on`,
`last_modified_by`,
`last_modified_on`,
`version`)
VALUES (NEW.`id`,
NEW.`permission_id`,
NEW.`role_id`,
NEW.`created_by`,
NEW.`created_on`,
NEW.`last_modified_by`,
NEW.`last_modified_on`,
NEW.`version`);
END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_permission_to_assign_role_map_au`;

DELIMITER $$
CREATE TRIGGER `tr_permission_to_assign_role_map_au` AFTER UPDATE
ON `permission_to_assign_role_map` FOR EACH ROW

BEGIN
UPDATE `permission_to_assign_role_map_hist`
SET `expired_by` = @app_user_id,
`expired_on` = current_timestamp(6)
WHERE `id` = OLD.`id` and `expired_on` is null;

INSERT INTO `permission_to_assign_role_map_hist`
(`id`,
`permission_id`,
`role_id`,
`created_by`,
`created_on`,
`last_modified_by`,
`last_modified_on`,
`version`)
VALUES (NEW.`id`,
NEW.`permission_id`,
NEW.`role_id`,
NEW.`created_by`,
NEW.`created_on`,
NEW.`last_modified_by`,
NEW.`last_modified_on`,
NEW.`version`);
END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_permission_to_assign_role_map_ad`;

DELIMITER $$
CREATE TRIGGER `tr_permission_to_assign_role_map_ad` AFTER DELETE
ON `permission_to_assign_role_map` FOR EACH ROW

BEGIN
  UPDATE `permission_to_assign_role_map_hist`
  SET `expired_by` = @app_user_id,
  `expired_on` = current_timestamp(6)
  WHERE `id` = OLD.`id` and `expired_on` is null;
END;
$$
DELIMITER ;

