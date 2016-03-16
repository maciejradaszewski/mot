SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');

SET @create_sna_permission = (SELECT `id` FROM `permission` WHERE `code` = 'SPECIAL-NOTICE-ACKNOWLEDGE');
SET @create_snrc_permission = (SELECT `id` FROM `permission` WHERE `code` = 'SPECIAL-NOTICE-READ-CURRENT');

SET @aed = (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DELEGATE');
SET @aedm = (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DESIGNATED-MANAGER');

INSERT INTO `role_permission_map`
(`role_id`, `permission_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
VALUES
(@aed, @create_sna_permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
(@aed, @create_snrc_permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
(@aedm, @create_sna_permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
(@aedm, @create_snrc_permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6))
;
