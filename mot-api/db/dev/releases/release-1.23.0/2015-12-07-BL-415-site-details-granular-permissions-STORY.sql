SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

SET @permission_to_view_type_code = 'VTS-DETAILS-VIEW-TYPE';
SET @permission_to_update_site_details_code = 'VTS-UPDATE-SITE-DETAILS';

SET @ao1 = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1');
SET @ao2 = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2');
SET @ve = (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER');
SET @csm = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-MANAGER');
SET @csco = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE');
SET @scheme_manager = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT');
SET @scheme_user = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');


INSERT INTO `permission` (`name`, `code`, `is_restricted`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
  VALUES ('VTS - view VTS type', @permission_to_view_type_code, 0, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6));

SET @permission_to_view_type = (SELECT `id` FROM `permission` WHERE `code` = @permission_to_view_type_code);
SET @permission_to_update_site_details = (SELECT `id` FROM `permission` WHERE `code` = @permission_to_update_site_details_code);

-- remove all permissions from roles that can update site details
DELETE FROM role_permission_map WHERE permission_id =  @permission_to_update_site_details;

-- add permissions to roles that edit site details:
INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by` ,`created_on`, `last_updated_by`, `last_updated_on`)
  SELECT `role_id`, `permission_id`, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6) FROM
  (
    SELECT @scheme_manager AS `role_id`, @permission_to_update_site_details AS `permission_id` UNION
    SELECT @scheme_user, @permission_to_update_site_details UNION
    SELECT @ao1, @permission_to_update_site_details UNION
    SELECT @ao2, @permission_to_update_site_details UNION
    SELECT @ve, @permission_to_update_site_details
  ) AS union_alias;

-- add permissions to roles that can view VTS type:
INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by` ,`created_on`, `last_updated_by`, `last_updated_on`)
  SELECT `role_id`, `permission_id`, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6) FROM
  (
    SELECT @ao1 AS `role_id`, @permission_to_view_type AS `permission_id` UNION
    SELECT @ao2, @permission_to_view_type UNION
    SELECT @scheme_manager, @permission_to_view_type UNION
    SELECT @scheme_user, @permission_to_view_type UNION
    SELECT @ve, @permission_to_view_type UNION
    SELECT @csm, @permission_to_view_type UNION
    SELECT @csco, @permission_to_view_type
  ) AS union_alias;

