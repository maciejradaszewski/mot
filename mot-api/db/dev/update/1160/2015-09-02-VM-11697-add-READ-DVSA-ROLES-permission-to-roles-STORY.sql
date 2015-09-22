# VM-11687
# adding READ-DVSA-ROLES permission to roles
# document with list of roles is attached in the jira ticket

SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

INSERT INTO `permission` (`name`, `code`, `created_by`) VALUES
  ('Read DVSA Roles', 'READ-DVSA-ROLES', @created_by);

SET @read_dvsa_roles_id = (SELECT `id` FROM `permission` WHERE `code` = 'READ-DVSA-ROLES');

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1'),
    @read_dvsa_roles_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2'),
    @read_dvsa_roles_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT'),
    @read_dvsa_roles_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER'),
    @read_dvsa_roles_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER'),
    @read_dvsa_roles_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-MANAGER'),
    @read_dvsa_roles_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE'),
    @read_dvsa_roles_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVLA-MANAGER'),
    @read_dvsa_roles_id,
    @created_by
  );