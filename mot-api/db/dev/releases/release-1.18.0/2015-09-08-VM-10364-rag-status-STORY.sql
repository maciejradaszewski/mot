ALTER TABLE `enforcement_site_assessment`
  DROP FOREIGN KEY `fk_enforcement_site_assessment_authorised_examiner_id`,
  DROP FOREIGN KEY `fk_enforcement_site_assessment_tester`,
  DROP FOREIGN KEY `fk_enforcement_visit_outcome_id`,

-- drop old keys to recreate them with proper naming convention
  DROP FOREIGN KEY `fk_enforcement_site_assessment_vehicle_station_id`,
  DROP FOREIGN KEY `enforcement_site_assessment_ibfk_1`,
  DROP FOREIGN KEY `enforcement_site_assessment_ibfk_2`;

-- drop old indexes to recreate them with proper naming convention
ALTER TABLE `enforcement_site_assessment`
  DROP INDEX `vts`,
  DROP INDEX `person`,
  DROP INDEX `authorised_examiner_id`,
  DROP INDEX `created_by`,
  DROP INDEX `last_updated_by`;

ALTER TABLE `enforcement_site_assessment`
  DROP COLUMN `advisory_issued`,
  DROP COLUMN `visit_outcome_id`,
  CHANGE COLUMN `authorisation_for_authorised_examiner_id` `ae_representative_person_id` INT(11) UNSIGNED NULL DEFAULT NULL ,
  CHANGE COLUMN `ae_representative_position` `ae_representative_position` VARCHAR(100) NULL COMMENT 'Free text AE or AEs representative position in the organisation' ,
  CHANGE COLUMN `person_id` `tester_person_id` INT(10) UNSIGNED NULL COMMENT 'Reference to the tester who was present during the on-site Site assessment' ,
  ADD COLUMN `examiner_person_id` INT(10) UNSIGNED NOT NULL AFTER `tester_person_id`,
  ADD COLUMN `ae_organisation_id` INT(10) UNSIGNED NOT NULL AFTER `site_id`,
  DROP INDEX `visit_outcome_id` ;

-- recreate indexes with proper naming
ALTER TABLE `enforcement_site_assessment`
  ADD INDEX `ix_enforcement_site_assessment_site_id` (`site_id`),
  ADD INDEX `ix_enforcement_site_assessment_last_updated_by` (`last_updated_by`),
  ADD INDEX `ix_enforcement_site_assessment_created_by` (`created_by`),
  ADD INDEX `ix_enforcement_site_assessment_examiner_person_id` (`examiner_person_id`),
  ADD INDEX `ix_enforcement_site_assessment_ae_representative_person_id` (`ae_representative_person_id`),
  ADD INDEX `ix_enforcement_site_assessment_tester_person_id` (`tester_person_id`);

ALTER TABLE `enforcement_site_assessment`
  ADD CONSTRAINT `fk_enforcement_site_assessment_tester_person_id`
    FOREIGN KEY (`tester_person_id`)
    REFERENCES `person` (`id`);

ALTER TABLE `enforcement_site_assessment`
  ADD CONSTRAINT `fk_enforcement_site_assessment_ae_representative_person_id`
    FOREIGN KEY (`ae_representative_person_id`)
    REFERENCES `person` (`id`);

ALTER TABLE `enforcement_site_assessment`
  ADD CONSTRAINT `fk_enforcement_site_assessment_examiner_person_id`
    FOREIGN KEY (`examiner_person_id`)
    REFERENCES `person` (`id`);

-- recreating old fk's with proper naming convention
ALTER TABLE `enforcement_site_assessment`
  ADD CONSTRAINT `fk_enforcement_site_assessment_created_by`
    FOREIGN KEY (`created_by`)
    REFERENCES `person` (`id`);

ALTER TABLE `enforcement_site_assessment`
  ADD CONSTRAINT `fk_enforcement_site_assessment_last_updated_by`
    FOREIGN KEY (`last_updated_by`)
    REFERENCES `person` (`id`);

ALTER TABLE `enforcement_site_assessment`
  ADD CONSTRAINT `fk_enforcement_site_assessment_site_id`
    FOREIGN KEY (`site_id`)
    REFERENCES `site` (`id`);

ALTER TABLE `enforcement_site_assessment_hist`
  DROP COLUMN `advisory_issued`,
  DROP COLUMN `visit_outcome_id`,
  CHANGE COLUMN `authorisation_for_authorised_examiner_id` `ae_representative_person_id` INT(11) UNSIGNED NULL DEFAULT NULL ,
  CHANGE COLUMN `person_id` `tester_person_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  ADD COLUMN `examiner_person_id` INT(10) NULL AFTER `tester_person_id`,
  ADD COLUMN `ae_organisation_id` INT(10) NULL AFTER `site_id`;

DELIMITER $$

DROP TRIGGER IF EXISTS `tr_enforcement_site_assessment_ad` $$
DELIMITER ;
DELIMITER $$

DROP TRIGGER IF EXISTS tr_enforcement_site_assessment_au$$
CREATE TRIGGER `tr_enforcement_site_assessment_au` AFTER UPDATE
ON `enforcement_site_assessment` FOR EACH ROW
  INSERT INTO  `enforcement_site_assessment_hist` (
        `hist_transaction_type`,
        `hist_batch_number`, `id`,
        `site_id`,
        `ae_organisation_id`,
        `site_assessment_score`,
        `ae_representative_person_id`,
        `ae_representative_name`,
        `ae_representative_position`,
        `tester_person_id`,
        `examiner_person_id`,
        `visit_date`,
        `mot1_legacy_id`,
        `created_by`,
        `created_on`,
        `last_updated_by`,
        `last_updated_on`,
        `version`,
        `batch_number`
  )
  VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
        OLD.`site_id`,
        OLD.`ae_organisation_id`,
        OLD.`site_assessment_score`,
        OLD.`ae_representative_person_id`,
        OLD.`ae_representative_name`,
        OLD.`ae_representative_position`,
        OLD.`tester_person_id`,
        OLD.`examiner_person_id`,
        OLD.`visit_date`,
        OLD.`mot1_legacy_id`,
        OLD.`created_by`,
        OLD.`created_on`,
        OLD.`last_updated_by`,
        OLD.`last_updated_on`,
        OLD.`version`,
        OLD.`batch_number`
  )$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS tr_enforcement_site_assessment_ad$$
CREATE TRIGGER `tr_enforcement_site_assessment_ad` AFTER DELETE
ON `enforcement_site_assessment` FOR EACH ROW
  INSERT INTO `enforcement_site_assessment_hist` (
      `hist_transaction_type`,
      `id`,
      `site_id`,
      `ae_organisation_id`,
      `site_assessment_score`,
      `ae_representative_person_id`,
      `ae_representative_name`,
      `ae_representative_position`,
      `tester_person_id`,
      `examiner_person_id`,
      `visit_date`,
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
      OLD.`id`,
      OLD.`site_id`,
      OLD.`ae_organisation_id`,
      OLD.`site_assessment_score`,
      OLD.`ae_representative_person_id`,
      OLD.`ae_representative_name`,
      OLD.`ae_representative_position`,
      OLD.`tester_person_id`,
      OLD.`examiner_person_id`,
      OLD.`visit_date`,
      OLD.`mot1_legacy_id`,
      OLD.`created_by`,
      OLD.`created_on`,
      OLD.`last_updated_by`,
      OLD.`last_updated_on`,
      OLD.`version`,
      OLD.`batch_number`
  );
$$
DELIMITER ;