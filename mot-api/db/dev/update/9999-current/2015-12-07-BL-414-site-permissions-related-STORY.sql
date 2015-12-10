SET @scheme_manager = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT');
SET @scheme_user = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');
SET @csco = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE');

SET @permission = (SELECT `id` FROM `permission` WHERE `code` = 'VTS-TEST-LOGS');

SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by` ,`created_on`, `last_updated_by`, `last_updated_on`)
VALUES
  (@scheme_manager, @permission, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6)),
  (@scheme_user, @permission, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6)),
  (@csco, @permission, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6));
