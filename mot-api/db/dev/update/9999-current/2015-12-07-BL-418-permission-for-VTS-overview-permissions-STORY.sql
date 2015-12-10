SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`, `last_updated_by`, `created_on`, `last_updated_on`)
VALUES
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT'),
    (SELECT `id` FROM `permission` WHERE `code` = 'TESTING-SCHEDULE-UPDATE'),
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER'),
    (SELECT `id` FROM `permission` WHERE `code` = 'TESTING-SCHEDULE-UPDATE'),
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT'),
    (SELECT `id` FROM `permission` WHERE `code` = 'DEFAULT-BRAKE-TESTS-CHANGE'),
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER'),
    (SELECT `id` FROM `permission` WHERE `code` = 'DEFAULT-BRAKE-TESTS-CHANGE'),
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'SITE-ADMIN'),
    (SELECT `id` FROM `permission` WHERE `code` = 'DEFAULT-BRAKE-TESTS-CHANGE'),
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER'),
    (SELECT `id` FROM `permission` WHERE `code` = 'VTS-UPDATE-TESTING-FACILITIES-DETAILS'),
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT'),
    (SELECT `id` FROM `permission` WHERE `code` = 'VTS-UPDATE-TESTING-FACILITIES-DETAILS'),
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER'),
    (SELECT `id` FROM `permission` WHERE `code` = 'VTS-UPDATE-TESTING-FACILITIES-DETAILS'),
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  );

