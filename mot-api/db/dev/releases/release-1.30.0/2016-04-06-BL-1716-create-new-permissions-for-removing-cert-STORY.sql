SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');

INSERT INTO
  `permission` (`name`, `code`, `is_restricted`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
VALUES
  ('create qualification certificate for another user', 'REMOVE-MOT-TESTING-CERTIFICATE-FOR-USER', 0, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6));

SET @remove = (SELECT `id` FROM `permission` WHERE `code` = 'REMOVE-MOT-TESTING-CERTIFICATE-FOR-USER');

INSERT INTO `role_permission_map`
(`role_id`, `permission_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
VALUES
((SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1'), @remove, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
((SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2'), @remove, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
((SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER'), @remove, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
((SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT'), @remove, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
((SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER'), @remove, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));
