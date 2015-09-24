# VM-11687
# adding MANAGE-DVSA-ROLES permission to roles
# document with list of roles is attached in the jira ticket

SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

INSERT IGNORE INTO `permission` (`name`, `code`, `is_restricted`, `created_by`)
VALUES ('VM-10619 Role Management', 'MANAGE-DVSA-ROLES', 1, @created_by);

SET @manage_dvsa_roles_id = (SELECT `id` FROM `permission` WHERE `code` = 'MANAGE-DVSA-ROLES');

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1'),
    @manage_dvsa_roles_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT'),
    @manage_dvsa_roles_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER'),
    @manage_dvsa_roles_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-MANAGER'),
    @manage_dvsa_roles_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVLA-MANAGER'),
    @manage_dvsa_roles_id,
    @created_by
  );