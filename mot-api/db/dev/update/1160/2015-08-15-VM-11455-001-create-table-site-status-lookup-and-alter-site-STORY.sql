SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' || `user_reference` = 'Static Data');

-- Create new site_status_lookup table
CREATE TABLE IF NOT EXISTS `site_status_lookup` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `code` varchar(5) NOT NULL,
  `display_order` smallint unsigned NOT NULL,
  `created_by` int unsigned DEFAULT NULL,
  `created_on` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_modified_by` int unsigned DEFAULT NULL,
  `last_modified_on` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_site_status_lookup_display_order` (`display_order`),
  KEY `ix_site_status_lookup_created_by` (`created_by`),
  KEY `ix_site_status_lookup_last_modified_by` (`last_modified_by`),
  CONSTRAINT `fk_site_status_lookup_created_by_person_id` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_site_status_lookup_last_modified_by_person_id` FOREIGN KEY (`last_modified_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB COMMENT 'A business approved list of statuses for a site';

-- Create history table for mot.site_status_lookup
CREATE TABLE `site_status_lookup_hist`
(
`hist_id` bigint unsigned not null auto_increment,
`id` bigint(20) unsigned,
`name` varchar(30),
`code` varchar(5),
`display_order` smallint(5) unsigned,
`created_by` int(10) unsigned,
`created_on` timestamp(6) null default null,
`last_modified_by` int(10) unsigned,
`last_modified_on` timestamp(6) null default null,
`version` int(10) unsigned,
`expired_by` int unsigned,
`expired_on` timestamp(6) null default null,
PRIMARY KEY (`hist_id`),
UNIQUE INDEX uk_site_status_lookup_hist_id_version (`id`,`version`)
) ENGINE=InnoDB;

ALTER TABLE `site_status_lookup_hist`
MODIFY `created_by` int unsigned not null,
MODIFY `last_modified_by` int unsigned not null;

-- Create triggers for site_status_lookup
DROP TRIGGER IF EXISTS `tr_site_status_lookup_bi`;
DELIMITER $$
CREATE TRIGGER `tr_site_status_lookup_bi` BEFORE INSERT
ON `site_status_lookup` FOR EACH ROW  BEGIN
SET
NEW.`version` = 1,
NEW.`created_by` = @app_user_id,
NEW.`last_modified_by` = @app_user_id;
END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_site_status_lookup_bu`;
DELIMITER $$
CREATE TRIGGER `tr_site_status_lookup_bu` BEFORE UPDATE
ON `site_status_lookup` FOR EACH ROW  BEGIN
SET
NEW.`version` = OLD.`version` + 1,
NEW.`last_modified_by` = @app_user_id;
END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_site_status_lookup_ai`;
DELIMITER $$
CREATE TRIGGER `tr_site_status_lookup_ai` AFTER
INSERT ON `site_status_lookup`
FOR EACH ROW BEGIN
INSERT INTO `site_status_lookup_hist`
(
`id`,
`name`,
`code`,
`display_order`,
`created_by`,
`created_on`,
`last_modified_by`,
`last_modified_on`,
`version`
)
VALUES
(
NEW.`id`,
NEW.`name`,
NEW.`code`,
NEW.`display_order`,
NEW.`created_by`,
NEW.`created_on`,
NEW.`last_modified_by`,
NEW.`last_modified_on`,
NEW.`version`
);
END;
$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `tr_site_status_lookup_au` AFTER
UPDATE ON `site_status_lookup`
FOR EACH ROW BEGIN UPDATE `site_status_lookup_hist`
SET `expired_by` = @app_user_id,`expired_on` = current_timestamp(6)
WHERE `id` = OLD.`id` and `expired_on` is null;
INSERT INTO `site_status_lookup_hist`
(
`id`,
`name`,
`code`,
`display_order`,
`created_by`,
`created_on`,
`last_modified_by`,
`last_modified_on`,
`version`
)
VALUES
(
NEW.`id`,
NEW.`name`,
NEW.`code`,
NEW.`display_order`,
NEW.`created_by`,
NEW.`created_on`,
NEW.`last_modified_by`,
NEW.`last_modified_on`,
NEW.`version`
);
END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS `tr_site_status_lookup_ad`;
DELIMITER $$
  CREATE TRIGGER `tr_site_status_lookup_ad` AFTER
  DELETE ON `site_status_lookup`
  FOR EACH ROW BEGIN UPDATE `site_status_lookup_hist`
  SET `expired_by` = @app_user_id, `expired_on` = current_timestamp(6)
  WHERE `id` = OLD.`id` and `expired_on` is null;
END;
$$
DELIMITER ;

-- ADD COLUMN 'site_status_id' and 'status_changed_on' WITH NULL
ALTER TABLE `site`
  ADD COLUMN `site_status_id` BIGINT UNSIGNED NULL COMMENT 'Vehicle Testing Station Status' AFTER `site_number`,
  ADD COLUMN `status_changed_on` DATETIME(6) NULL COMMENT 'Vehicle Testing Station Status Effective Date' AFTER `site_status_id`;

-- ADD COLUMN 'site_status_id' and 'status_changed_on' WITH NULL
ALTER TABLE `site_hist`
  ADD COLUMN `site_status_id` BIGINT UNSIGNED NULL COMMENT 'Vehicle Testing Station Status' AFTER `site_number`,
  ADD COLUMN `status_changed_on` DATETIME(6) NULL COMMENT 'Vehicle Testing Station Status Effective Date' AFTER `site_status_id`;

-- Create after triggers for site
DROP TRIGGER IF EXISTS `tr_site_ai`;
DELIMITER ;;
CREATE TRIGGER `tr_site_ai` AFTER INSERT
ON `site` FOR EACH ROW
INSERT INTO  `site_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES ('I', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`)
;;

DELIMITER ;
DROP TRIGGER IF EXISTS `tr_site_au`;
DELIMITER ;;
  CREATE TRIGGER `tr_site_au` AFTER UPDATE
ON `site` FOR EACH ROW
INSERT INTO  `site_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`organisation_id`,
`name`,
`site_number`,
`site_status_id`,
`status_changed_on`,
`default_brake_test_class_1_and_2_id`,
`default_service_brake_test_class_3_and_above_id`,
`default_parking_brake_test_class_3_and_above_id`,
`last_site_assessment_id`,
`dual_language`,
`scottish_bank_holiday`,
`latitude`,
`longitude`,
`type_id`,
`transition_status_id`,
`non_working_day_country_lookup_id`,
`first_login_by`,
`first_login_on`,
`first_test_carried_out_by`,
`first_test_carried_out_number`,
`first_test_carried_out_on`,
`first_live_test_carried_out_by`,
`first_live_test_carried_out_number`,
`first_live_test_carried_out_on`,
`mot1_details_updated_on`,
`mot1_vts_device_status_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`)
VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
OLD.`organisation_id`,
OLD.`name`,
OLD.`site_number`,
OLD.`site_status_id`,
OLD.`status_changed_on`,
OLD.`default_brake_test_class_1_and_2_id`,
OLD.`default_service_brake_test_class_3_and_above_id`,
OLD.`default_parking_brake_test_class_3_and_above_id`,
OLD.`last_site_assessment_id`,
OLD.`dual_language`,
OLD.`scottish_bank_holiday`,
OLD.`latitude`,
OLD.`longitude`,
OLD.`type_id`,
OLD.`transition_status_id`,
OLD.`non_working_day_country_lookup_id`,
OLD.`first_login_by`,
OLD.`first_login_on`,
OLD.`first_test_carried_out_by`,
OLD.`first_test_carried_out_number`,
OLD.`first_test_carried_out_on`,
OLD.`first_live_test_carried_out_by`,
OLD.`first_live_test_carried_out_number`,
OLD.`first_live_test_carried_out_on`,
OLD.`mot1_details_updated_on`,
OLD.`mot1_vts_device_status_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`)
;;

DELIMITER ;

DROP TRIGGER IF EXISTS `tr_site_ad`;
DELIMITER ;;
CREATE TRIGGER `tr_site_ad` AFTER DELETE
ON `site` FOR EACH ROW
INSERT INTO  `site_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
`organisation_id`,
`name`,
`site_number`,
`site_status_id`,
`status_changed_on`,
`default_brake_test_class_1_and_2_id`,
`default_service_brake_test_class_3_and_above_id`,
`default_parking_brake_test_class_3_and_above_id`,
`last_site_assessment_id`,
`dual_language`,
`scottish_bank_holiday`,
`latitude`,
`longitude`,
`type_id`,
`transition_status_id`,
`non_working_day_country_lookup_id`,
`first_login_by`,
`first_login_on`,
`first_test_carried_out_by`,
`first_test_carried_out_number`,
`first_test_carried_out_on`,
`first_live_test_carried_out_by`,
`first_live_test_carried_out_number`,
`first_live_test_carried_out_on`,
`mot1_details_updated_on`,
`mot1_vts_device_status_id`,
`mot1_legacy_id`,
`created_by`,
`created_on`,
`last_updated_by`,
`last_updated_on`,
`version`,
`batch_number`)
VALUES ('D', COALESCE(@BATCH_NUMBER,0), OLD.`id`,
OLD.`organisation_id`,
OLD.`name`,
OLD.`site_number`,
OLD.`site_status_id`,
OLD.`status_changed_on`,
OLD.`default_brake_test_class_1_and_2_id`,
OLD.`default_service_brake_test_class_3_and_above_id`,
OLD.`default_parking_brake_test_class_3_and_above_id`,
OLD.`last_site_assessment_id`,
OLD.`dual_language`,
OLD.`scottish_bank_holiday`,
OLD.`latitude`,
OLD.`longitude`,
OLD.`type_id`,
OLD.`transition_status_id`,
OLD.`non_working_day_country_lookup_id`,
OLD.`first_login_by`,
OLD.`first_login_on`,
OLD.`first_test_carried_out_by`,
OLD.`first_test_carried_out_number`,
OLD.`first_test_carried_out_on`,
OLD.`first_live_test_carried_out_by`,
OLD.`first_live_test_carried_out_number`,
OLD.`first_live_test_carried_out_on`,
OLD.`mot1_details_updated_on`,
OLD.`mot1_vts_device_status_id`,
OLD.`mot1_legacy_id`,
OLD.`created_by`,
OLD.`created_on`,
OLD.`last_updated_by`,
OLD.`last_updated_on`,
OLD.`version`,
OLD.`batch_number`)
;;
DELIMITER ;
