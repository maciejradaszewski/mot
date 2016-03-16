SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');

SET @mot_demo_test_perform = (SELECT `id` FROM `permission` WHERE `code` = 'MOT-DEMO-TEST-PERFORM');
SET @vehicle_read = (SELECT `id` FROM `permission` WHERE `code` = 'VEHICLE-READ');
SET @rfr_list = (SELECT `id` FROM `permission` WHERE `code` = 'RFR-LIST');



SET @user = (SELECT `id` FROM `role` WHERE `code` = 'USER');

INSERT INTO `role_permission_map`
(`role_id`, `permission_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
VALUES
(@user, @mot_demo_test_perform, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
(@user, @vehicle_read, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
(@user, @rfr_list, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6))
;
