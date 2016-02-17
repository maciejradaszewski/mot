SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');

SET @create_aep_permission = (SELECT `id` FROM `permission` WHERE `code` = 'AUTHORISED-EXAMINER-PRINCIPAL-CREATE');
SET @remove_aep_permission = (SELECT `id` FROM `permission` WHERE `code` = 'AUTHORISED-EXAMINER-PRINCIPAL-REMOVE');

SET @scheme_mgr = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT');
SET @scheme_user = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');
SET @ao1 = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1');



INSERT INTO `role_permission_map`
(`role_id`, `permission_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
VALUES
(@scheme_mgr, @create_aep_permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
(@scheme_user, @create_aep_permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
(@ao1, @create_aep_permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
(@scheme_mgr, @remove_aep_permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
(@scheme_user, @remove_aep_permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
(@ao1, @remove_aep_permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6))
;
