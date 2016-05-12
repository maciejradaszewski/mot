SET @created_by_id = (SELECT `id` FROM `person` WHERE `username` = 'static data');
SET @permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'VEHICLE-READ');
SET @dvla_import_role = (SELECT `id` FROM `role` WHERE `code` = 'DVLA-IMPORT');

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (@dvla_import_role, @permission_id, @created_by_id);
