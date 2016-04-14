SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');

INSERT INTO
  `permission` (`name`, `code`, `is_restricted`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
VALUES
  ('create qualification certificate for another user', 'CREATE-MOT-TESTING-CERTIFICATE-FOR-USER', 0, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6)),
  ('update qualification certificate for another user', 'UPDATE-MOT-TESTING-CERTIFICATE-FOR-USER', 0, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6));

SET @create = (SELECT `id` FROM `permission` WHERE `code` = 'CREATE-MOT-TESTING-CERTIFICATE-FOR-USER');
SET @update = (SELECT `id` FROM `permission` WHERE `code` = 'UPDATE-MOT-TESTING-CERTIFICATE-FOR-USER');

INSERT INTO `role_permission_map`
(`role_id`, `permission_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
VALUES
((SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1'), @create, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
((SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1'), @update, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
((SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2'), @create, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
((SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2'), @update, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
((SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER'), @create, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
((SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER'), @update, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
((SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT'), @create, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
((SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT'), @update, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
((SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER'), @create, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
((SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER'), @update, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));
