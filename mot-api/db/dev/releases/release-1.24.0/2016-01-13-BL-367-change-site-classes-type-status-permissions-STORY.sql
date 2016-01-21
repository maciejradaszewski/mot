SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

SET @permission_to_update_classes_code = 'VTS-UPDATE-CLASSES';
SET @permission_to_update_type_code = 'VTS-UPDATE-TYPE';
SET @permission_to_update_status_code = 'VTS-UPDATE-STATUS';
SET @permission_to_update_name_code = 'VTS-UPDATE-NAME';
SET @permission_to_update_phone_code = 'VTS-UPDATE-PHONE';
SET @permission_to_update_country_code = 'VTS-UPDATE-COUNTRY';
SET @permission_to_update_address_code = 'VTS-UPDATE-ADDRESS';
SET @permission_to_update_email_code = 'VTS-UPDATE-EMAIL';

SET @ao1 = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1');
SET @ao2 = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2');
SET @ve = (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER');
SET @scheme_manager = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT');
SET @scheme_user = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');
SET @aedm = (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DESIGNATED-MANAGER');
SET @aed = (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DELEGATE');
SET @siteManager = (SELECT `id` FROM `role` WHERE `code` = 'SITE-MANAGER');
SET @siteAdmin = (SELECT `id` FROM `role` WHERE `code` = 'SITE-ADMIN');

-- add permissions for update site details : classes, type, status
INSERT INTO `permission`(`name`, `code`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
  ('User can update classes in Site details', @permission_to_update_classes_code,
   @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('User can update type in Site details', @permission_to_update_type_code, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('User can update status in Site details', @permission_to_update_status_code, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),

  ('User can update phone number of a vts', @permission_to_update_phone_code, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('User can update country of a vts', @permission_to_update_country_code, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('User can update address of a vts', @permission_to_update_address_code, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('User can update email of a vts', @permission_to_update_email_code, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @permission_to_update_classes = (SELECT `id` FROM `permission` WHERE `code` = @permission_to_update_classes_code);
SET @permission_to_update_type = (SELECT `id` FROM `permission` WHERE `code` = @permission_to_update_type_code);
SET @permission_to_update_status = (SELECT `id` FROM `permission` WHERE `code` = @permission_to_update_status_code);
SET @permission_to_update_name = (SELECT `id` FROM `permission` WHERE `code` = @permission_to_update_name_code);
SET @permission_to_update_phone = (SELECT `id` FROM `permission` WHERE `code` = @permission_to_update_phone_code);
SET @permission_to_update_country = (SELECT `id` FROM `permission` WHERE `code` = @permission_to_update_country_code);
SET @permission_to_update_address = (SELECT `id` FROM `permission` WHERE `code` = @permission_to_update_address_code);
SET @permission_to_update_email = (SELECT `id` FROM `permission` WHERE `code` = @permission_to_update_email_code);

-- remove all permissions from roles that can update site details
DELETE FROM `role_permission_map` WHERE `permission_id`= @permission_to_update_name_code;

-- add permissions to roles that edit site details:
INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by` ,`created_on`, `last_updated_by`, `last_updated_on`)
  SELECT `role_id`, `permission_id`, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6) FROM
  (
    SELECT @scheme_manager AS `role_id`, @permission_to_update_classes AS `permission_id` UNION
    SELECT @scheme_user, @permission_to_update_classes UNION
    SELECT @ao1, @permission_to_update_classes UNION
    SELECT @ao2, @permission_to_update_classes UNION
    SELECT @ve, @permission_to_update_classes UNION

    SELECT @scheme_manager, @permission_to_update_type UNION
    SELECT @scheme_user, @permission_to_update_type UNION
    SELECT @ao1, @permission_to_update_type UNION
    SELECT @ao2, @permission_to_update_type UNION
    SELECT @ve, @permission_to_update_type UNION

    SELECT @scheme_manager, @permission_to_update_status UNION
    SELECT @scheme_user, @permission_to_update_status UNION
    SELECT @ao1, @permission_to_update_status UNION
    SELECT @ao2, @permission_to_update_status UNION
    SELECT @ve, @permission_to_update_status UNION

    SELECT @scheme_manager, @permission_to_update_name UNION
    SELECT @scheme_user, @permission_to_update_name UNION
    SELECT @ao1, @permission_to_update_name UNION
    SELECT @ao2, @permission_to_update_name UNION
    SELECT @ve, @permission_to_update_name UNION

    SELECT @scheme_manager, @permission_to_update_phone UNION
    SELECT @scheme_user, @permission_to_update_phone UNION
    SELECT @ao1, @permission_to_update_phone UNION
    SELECT @ao2, @permission_to_update_phone UNION
    SELECT @ve, @permission_to_update_phone UNION
    SELECT @aedm, @permission_to_update_phone UNION
    SELECT @aed, @permission_to_update_phone UNION
    SELECT @siteManager, @permission_to_update_phone UNION
    SELECT @siteAdmin, @permission_to_update_phone UNION

    SELECT @scheme_manager, @permission_to_update_country UNION
    SELECT @scheme_user, @permission_to_update_country UNION
    SELECT @ao1, @permission_to_update_country UNION
    SELECT @ao2, @permission_to_update_country UNION
    SELECT @ve, @permission_to_update_country UNION

    SELECT @scheme_manager, @permission_to_update_address UNION
    SELECT @scheme_user, @permission_to_update_address UNION
    SELECT @ao1, @permission_to_update_address UNION
    SELECT @ao2, @permission_to_update_address UNION
    SELECT @ve, @permission_to_update_address UNION

    SELECT @scheme_manager, @permission_to_update_email UNION
    SELECT @scheme_user, @permission_to_update_email UNION
    SELECT @ao1, @permission_to_update_email UNION
    SELECT @ao2, @permission_to_update_email UNION
    SELECT @ve, @permission_to_update_email UNION
    SELECT @aedm, @permission_to_update_email UNION
    SELECT @aed, @permission_to_update_email UNION
    SELECT @siteManager, @permission_to_update_email UNION
    SELECT @siteAdmin, @permission_to_update_email
  ) AS union_alias;

