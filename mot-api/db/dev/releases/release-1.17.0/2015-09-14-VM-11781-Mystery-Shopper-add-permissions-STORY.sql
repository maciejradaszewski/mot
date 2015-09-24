
SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

INSERT INTO `permission` (`code`, `name`, `created_by`, `last_updated_by`, `last_updated_on`)
VALUES
  ('MANAGE-MYSTERY-SHOPPER-CAMPAIGN', 'Create, edit and cancel mystery shopper campaigns', @created_by, @created_by, CURRENT_TIMESTAMP(6));

SET @manage_dvsa_roles_id = (SELECT `id` FROM `permission` WHERE `code` = 'MANAGE-MYSTERY-SHOPPER-CAMPAIGN');

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`, `last_updated_by`, `last_updated_on`)
VALUES
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1'),
    @manage_dvsa_roles_id,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP(6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER'),
    @manage_dvsa_roles_id,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP(6)
  );