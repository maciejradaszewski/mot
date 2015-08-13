SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' || `user_reference` = 'Static Data');
SET @permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'DVSA-SITE-SEARCH');
SET @csco_role_id = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE');

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
VALUES
  (@csco_role_id, @permission_id, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));
