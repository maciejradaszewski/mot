SET @created_by = (SELECT `id` FROM `person` WHERE `username` = 'static data');

INSERT INTO `role` (`code`, `name`, `created_by`)
VALUES
	('VM-10519-USER', 'VM-10519 User', @created_by);

SET @role_id = LAST_INSERT_ID();

INSERT INTO `person_system_role` (`name`, `full_name`, `short_name`, `role_id`, `created_by`)
VALUES
	('VM-10519-USER', 'VM-10519 User', 'VM10519USER', @role_id, @created_by);

SET @permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'ASSESS-DEMO-TEST');

INSERT INTO `role_permission_map` ( `role_id`, `permission_id`, `created_by`)
VALUES
	(@role_id, @permission_id, @created_by);
