SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');

INSERT INTO `permission`(`name`, `code`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
  ('View VTS test logs', 'VTS-TEST-LOGS', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @vts_test_log_permission = (SELECT `id` FROM `permission` WHERE `code` = 'VTS-TEST-LOGS');

INSERT INTO `role_permission_map`(`role_id`, `permission_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
  (
    (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DELEGATE'),
    @vts_test_log_permission,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    @app_user_id,
    CURRENT_TIMESTAMP(6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DESIGNATED-MANAGER'),
    @vts_test_log_permission,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    @app_user_id,
    CURRENT_TIMESTAMP(6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'SITE-MANAGER'),
    @vts_test_log_permission,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    @app_user_id,
    CURRENT_TIMESTAMP(6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'SITE-ADMIN'),
    @vts_test_log_permission,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    @app_user_id,
    CURRENT_TIMESTAMP(6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1'),
    @vts_test_log_permission,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    @app_user_id,
    CURRENT_TIMESTAMP(6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2'),
    @vts_test_log_permission,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    @app_user_id,
    CURRENT_TIMESTAMP(6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER'),
    @vts_test_log_permission,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    @app_user_id,
    CURRENT_TIMESTAMP(6)
  );
