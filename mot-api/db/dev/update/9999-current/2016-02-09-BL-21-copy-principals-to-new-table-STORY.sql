/**

BL-21: cleaning up AEPs on production

This file will copy AEPs from person table to new AEP table
It will also do a deep copy of contact details (with address).

The whole process consists of two steps:
- copying all AEP data from person table to a temporary table
- copying AEP data from the temporary table to the target AEP table

Fun facts from prod:
- all AEPs have 0 or 1 contact detail. (good for us, we don't need to choose one)
- some people are mapped more than once as AEPs (even up to 17 times)
- all users who are linked via `organisation_business_role_map`
  are also linked by `auth_for_ae_person_as_principal_map`
  again good for us - we can just delete all AEP records in `organisation_business_role_map`
- AEP role has zero permissions, so we don't have to worry about anything changing in that area

 */

SET @`app_user_id` = (SELECT `id`
                      FROM `person`
                      WHERE `user_reference` = 'Static Data' OR `username` = 'static data');
SET @`now` = CURRENT_TIMESTAMP(6);

SET @personalPhoneType = (SELECT id FROM phone_contact_type WHERE code= 'PERS');

DROP TEMPORARY TABLE IF EXISTS `AepTemporary`;
CREATE TEMPORARY TABLE `AepTemporary` (
  `id`                 INT         NOT NULL AUTO_INCREMENT,
  `original_person_id` INT         NOT NULL,                    # the id from the `person` table
  `auth_for_ae_id`   INT         NOT NULL,                    # id of authorisation of AE
  `had_username`       BOOL        NOT NULL,
  `first_name`         VARCHAR(50) NULL,
  `middle_name`        VARCHAR(50) NULL,
  `family_name`        VARCHAR(50) NULL,
  `dob`                DATETIME    NULL,
  `addressLine1`       VARCHAR(50) NULL,
  `addressLine2`       VARCHAR(50) NULL,
  `addressLine3`       VARCHAR(50) NULL,
  `addressLine4`       VARCHAR(50) NULL,
  `town`               VARCHAR(50) NULL,
  `country`            VARCHAR(50) NULL,
  `postcode`           VARCHAR(50) NULL,
  PRIMARY KEY (`id`)
);

################################################################################
# Let's extract AEPs to temporary table

INSERT INTO `AepTemporary`
(
  `original_person_id`,
  `auth_for_ae_id`,
  `had_username`,
  `first_name`,
  `middle_name`,
  `family_name`,
  `dob`,
  `addressLine1`,
  `addressLine2`,
  `addressLine3`,
  `addressLine4`,
  `town`,
  `country`,
  `postcode`)
  SELECT
             `person`.`id`                                `p_id`,
             `aep_map`.`auth_for_ae_id`                   `ae_id`,
             IF(`person`.`username` IS NULL, FALSE, TRUE) `hasUserName`,
             `person`.`first_name`                        `firstName`,
             `person`.`middle_name`                       `middleName`,
             `person`.`family_name`                       `familyName`,
             `person`.`date_of_birth`                     `dob`,
             `address`.`address_line_1`                   `addressLine1`,
             `address`.`address_line_2`                   `addressLine2`,
             `address`.`address_line_3`                   `addressLine3`,
             `address`.`address_line_4`                   `addressLine4`,
             `address`.`town`                             `town`,
             `address`.`country`                          `country`,
             `address`.`postcode`                         `postcode`
  FROM `auth_for_ae_person_as_principal_map` `aep_map`
    JOIN `person` `person` ON `person`.`id` = `aep_map`.`person_id`
    LEFT JOIN `person_contact_detail_map` `person_contact` ON `person`.`id` = `person_contact`.`person_id`
    LEFT JOIN `contact_detail` `contact` ON `contact`.`id` = `person_contact`.`contact_id`
    LEFT JOIN `address` `address` ON `address`.`id` = `contact`.`address_id`
# UNKNOWN user holds no information so we want to filter him out
  WHERE (`person`.`username` != 'UNKNOWN' || `person`.`username` IS NULL)
  ORDER BY `person`.`id` ASC;

##################################################################################################
# Now that we have AEPs in temporary table,
# we'll use stored procedure to create related records in
# `authorised_examiner_principal`, `contact_detail`, `address` tables.
#
# We'll use cursor to go though AEPs in temporary table
# and on each iteration we'll populated related tables

DELIMITER $$

DROP PROCEDURE IF EXISTS `GetFilteredData`$$
CREATE PROCEDURE `GetFilteredData`()
  BEGIN
    DECLARE `done` INT;

    DECLARE `aepAuthorisationId` INT;
    DECLARE `aepFirstName` VARCHAR(50);
    DECLARE `aepMiddleName` VARCHAR(50);
    DECLARE `aepFamilyName` VARCHAR(50);
    DECLARE `aepDob` DATETIME;
    DECLARE `aepAddressLine1` VARCHAR(50);
    DECLARE `aepAddressLine2` VARCHAR(50);
    DECLARE `aepAddressLine3` VARCHAR(50);
    DECLARE `aepAddressLine4` VARCHAR(50);
    DECLARE `aepTown` VARCHAR(50);
    DECLARE `aepCountry` VARCHAR(50);
    DECLARE `aepPostocode` VARCHAR(50);
    DECLARE `aepOriginalPersonId` INT;

    DECLARE `curs` CURSOR FOR SELECT
                                `aep`.`auth_for_ae_id`,
                                `aep`.`first_name`,
                                `aep`.`middle_name`,
                                `aep`.`family_name`,
                                `aep`.`dob`,
                                `aep`.`addressLine1`,
                                `aep`.`addressLine2`,
                                `aep`.`addressLine3`,
                                `aep`.`addressLine4`,
                                `aep`.`town`,
                                `aep`.`country`,
                                `aep`.`postcode`,
                                `aep`.`original_person_id`
                              FROM `AepTemporary` `aep`;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET `done` = 1;

    SET `done` = 0;
    OPEN `curs`;
    `read_loop`: LOOP
      FETCH `curs`
      INTO
        `aepAuthorisationId`,
        `aepFirstName`,
        `aepMiddleName`,
        `aepFamilyName`,
        `aepDob`,
        `aepAddressLine1`,
        `aepAddressLine2`,
        `aepAddressLine3`,
        `aepAddressLine4`,
        `aepTown`,
        `aepCountry`,
        `aepPostocode`,
        `aepOriginalPersonId`;

      IF `done`
      THEN
        LEAVE `read_loop`;
      END IF;

      INSERT INTO `address`
      (
        `address_line_1`,
        `address_line_2`,
        `address_line_3`,
        `address_line_4`,
        `town`,
        `country`,
        `postcode`,
        `created_by`,
        `created_on`,
        `last_updated_by`,
        `last_updated_on`
      )
      VALUES (
        `aepAddressLine1`,
        `aepAddressLine2`,
        `aepAddressLine3`,
        `aepAddressLine4`,
        `aepTown`,
        `aepCountry`,
        `aepPostocode`,
        @`app_user_id`,
        @`now`,
        @`app_user_id`,
        @`now`
      );

      INSERT INTO `contact_detail` (`address_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
      VALUES (LAST_INSERT_ID(), @`app_user_id`, @`now`, @`app_user_id`, @`now`);

      # Whole reason behind creating SP was requirement to set foreign keys between tables,
      # SP allows us to use LAST_INSERT_ID()

      SET @`contactId` = LAST_INSERT_ID();

      INSERT INTO `authorised_examiner_principal` (`auth_for_ae_id`, `contact_detail_id`, `first_name`, `middle_name`, `family_name`, `date_of_birth`, `original_person_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
      VALUES
        (`aepAuthorisationId`,
         @`contactId`,
         `aepFirstName`,
         `aepMiddleName`,
         `aepFamilyName`,
         `aepDob`,
         `aepOriginalPersonId`,
         @`app_user_id`,
         @`now`,
         @`app_user_id`,
         @`now`);
    END LOOP;

    CLOSE `curs`;
  END$$
DELIMITER ;

CALL GetFilteredData();

DROP PROCEDURE `GetFilteredData`;


##################################################################################################
# Now that we have AEPs stored in a new table
# we can remove AEP links via role and `auth_for_ae_person_as_principal_map`


DELETE `position` FROM `organisation_business_role_map` `position`
  JOIN `organisation_business_role` `role` ON `role`.`id` = `position`.`business_role_id`
WHERE `role`.`code` = 'AEP';


# Goodbye AEP role forever - we never intended to have you at all
DELETE `role` FROM `organisation_business_role` `role`
WHERE `role`.`code` = 'AEP';
