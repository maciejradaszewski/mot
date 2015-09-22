/**********************

VM-8601

Splitting permissions that where used on more than one level (system, organisation or site).

**********************/

SET @`app_user_id` = (SELECT `id`
                      FROM `person`
                      WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

############# Splitting MOT-TEST-LIST to MOT-TEST-LIST-AT-AE

SET @`permissionCopyFromId` = (SELECT `id`
                               FROM `permission`
                               WHERE `code` = 'MOT-TEST-LIST');

INSERT INTO `permission` (`code`, `name`, `created_by`, `last_updated_by`, `last_updated_on`)
VALUES
  ('MOT-TEST-LIST-AT-AE', 'Read list of MOT tests done in AE', @`app_user_id`, @`app_user_id`, CURRENT_TIMESTAMP(6));

SET @`permissionCopyToId` = (SELECT `id`
                             FROM `permission`
                             WHERE `code` = 'MOT-TEST-LIST-AT-AE');

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`, `last_updated_by`, `last_updated_on`)
  SELECT
    `map`.`role_id`,
    @`permissionCopyToId`,
    @`app_user_id`,
    @`app_user_id`,
    CURRENT_TIMESTAMP(6)
  FROM `role_permission_map` `map`
  WHERE `map`.`permission_id` = @`permissionCopyFromId`;


############# Splitting CERTIFICATE-PRINT to CERTIFICATE-PRINT-ANY

SET @`permissionCopyFromId` = (SELECT `id`
                               FROM `permission`
                               WHERE `code` = 'CERTIFICATE-PRINT');

INSERT INTO `permission` (`code`, `name`, `created_by`, `last_updated_by`, `last_updated_on`)
VALUES ('CERTIFICATE-PRINT-ANY', 'Allows to print any certificate in the system', @`app_user_id`, @`app_user_id`,
        CURRENT_TIMESTAMP(6));

SET @`permissionCopyToId` = (SELECT `id`
                             FROM `permission`
                             WHERE `code` = 'CERTIFICATE-PRINT-ANY');

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`, `last_updated_by`, `last_updated_on`)
  SELECT
    `map`.`role_id`,
    @`permissionCopyToId`,
    @`app_user_id`,
    @`app_user_id`,
    CURRENT_TIMESTAMP(6)
  FROM `role_permission_map` `map`
  WHERE `map`.`permission_id` = @`permissionCopyFromId`;



