SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

SET @permission_to_update_business_details_name_code = 'AE-UPDATE-NAME';
SET @permission_to_update_business_details_trading_name_code = 'AE-UPDATE-TRADING-NAME';
SET @permission_to_update_business_details_type_code = 'AE-UPDATE-TYPE';
SET @permission_to_update_business_details_status_code = 'AE-UPDATE-STATUS';
SET @permission_to_update_business_details_DVSA_area_office_code = 'AE-UPDATE-DVSA-AREA-OFFICE';

SET @ao1 = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1');
SET @ao2 = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2');
SET @ve = (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER');
SET @scheme_manager = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT');
SET @scheme_user = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');

-- add permissions for update site details : classes, type, status
INSERT INTO `permission`(`name`, `code`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
  ('User can update name', @permission_to_update_business_details_name_code, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('User can update trading name', @permission_to_update_business_details_trading_name_code, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('User can update type', @permission_to_update_business_details_type_code, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('User can update status', @permission_to_update_business_details_status_code, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('User can update DVSA area office', @permission_to_update_business_details_DVSA_area_office_code, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @permission_to_update_business_details_name = (SELECT `id` FROM `permission` WHERE `code` = @permission_to_update_business_details_name_code);
SET @permission_to_update_business_details_trading_name = (SELECT `id` FROM `permission` WHERE `code` = @permission_to_update_business_details_trading_name_code);
SET @permission_to_update_business_details_type = (SELECT `id` FROM `permission` WHERE `code` = @permission_to_update_business_details_type_code);
SET @permission_to_update_business_details_status = (SELECT `id` FROM `permission` WHERE `code` = @permission_to_update_business_details_status_code);
SET @permission_to_update_business_details_DVSA_area_office = (SELECT `id` FROM `permission` WHERE `code` = @permission_to_update_business_details_DVSA_area_office_code);

-- add permissions to roles that edit business details:
INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by` ,`created_on`, `last_updated_by`, `last_updated_on`)
  SELECT `role_id`, `permission_id`, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6) FROM
  (
    SELECT @scheme_manager AS `role_id`, @permission_to_update_business_details_name AS `permission_id` UNION
    SELECT @scheme_user, @permission_to_update_business_details_name UNION
    SELECT @ao1, @permission_to_update_business_details_name UNION
    SELECT @ao2, @permission_to_update_business_details_name UNION
    SELECT @ve, @permission_to_update_business_details_name UNION

    SELECT @scheme_manager, @permission_to_update_business_details_trading_name UNION
    SELECT @scheme_user, @permission_to_update_business_details_trading_name UNION
    SELECT @ao1, @permission_to_update_business_details_trading_name UNION
    SELECT @ao2, @permission_to_update_business_details_trading_name UNION
    SELECT @ve, @permission_to_update_business_details_trading_name UNION

    SELECT @scheme_manager, @permission_to_update_business_details_type UNION
    SELECT @scheme_user, @permission_to_update_business_details_type UNION
    SELECT @ao1, @permission_to_update_business_details_type UNION
    SELECT @ao2, @permission_to_update_business_details_type UNION
    SELECT @ve, @permission_to_update_business_details_type UNION

    SELECT @scheme_manager, @permission_to_update_business_details_status UNION
    SELECT @scheme_user, @permission_to_update_business_details_status UNION
    SELECT @ao1, @permission_to_update_business_details_status UNION
    SELECT @ao2, @permission_to_update_business_details_status UNION
    SELECT @ve, @permission_to_update_business_details_status UNION

    SELECT @scheme_manager, @permission_to_update_business_details_DVSA_area_office UNION
    SELECT @scheme_user, @permission_to_update_business_details_DVSA_area_office UNION
    SELECT @ao1, @permission_to_update_business_details_DVSA_area_office UNION
    SELECT @ao2, @permission_to_update_business_details_DVSA_area_office UNION
    SELECT @ve, @permission_to_update_business_details_DVSA_area_office
  ) AS union_alias;

