SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');
SET @role = (SELECT `id` FROM `role` WHERE `code` =  'DVLA-OPERATIVE');
SET @permission = (SELECT `id` FROM `permission` WHERE `code` = 'VEHICLE-TESTING-STATION-READ');

INSERT INTO  `role_permission_map`
(
 `role_id`,
 `permission_id`,
 `created_by`,
 `last_updated_by`,
 `last_updated_on`
)
VALUES
(
  @role,
  @permission,
  @created_by,
  @created_by,
  CURRENT_TIMESTAMP(6)
);
