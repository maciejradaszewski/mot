SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

INSERT INTO `role` (`code`, `name`, `created_by`)
VALUES
  ('VM-9913-USER', 'VM-9913 User', @created_by);

SET @role_id = (SELECT `id` FROM `role` WHERE `code` = 'VM-9913-USER');

INSERT INTO `person_system_role` (`name`, `full_name`, `short_name`, `role_id`, `created_by`)
VALUES
  ('VM-9913-USER', 'VM-9913 User', 'VM9913USER', @role_id, @created_by);

SET @permission1_id = (SELECT `id` FROM `permission` WHERE `code` = 'AUTHORISED-EXAMINER-READ');
SET @permission2_id = (SELECT `id` FROM `permission` WHERE `code` = 'AUTHORISED-EXAMINER-READ-FULL');

INSERT INTO `role_permission_map` ( `role_id`, `permission_id`, `created_by`)
VALUES
  (@role_id, @permission1_id, @created_by);

INSERT INTO `role_permission_map` ( `role_id`, `permission_id`, `created_by`)
VALUES
  (@role_id, @permission2_id, @created_by);
