SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');
SET @permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'AE-SLOTS-USAGE-READ');

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`,`created_on`, `last_updated_by`, `last_updated_on`)
VALUES
((SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE'), @permission_id, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6));
