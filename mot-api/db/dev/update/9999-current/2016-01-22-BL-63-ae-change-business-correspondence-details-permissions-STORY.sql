SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

SET @permission_to_update_registered_office_address_code = 'AE-UPDATE-REGISTERED-OFFICE-ADDRESS';
SET @permission_to_update_registered_office_email_code = 'AE-UPDATE-REGISTERED-OFFICE-EMAIL';
SET @permission_to_update_registered_office_phone_code = 'AE-UPDATE-REGISTERED-OFFICE-PHONE';
SET @permission_to_update_correspondence_address_code = 'AE-UPDATE-CORRESPONDENCE-ADDRESS';
SET @permission_to_update_correspondence_email_code = 'AE-UPDATE-CORRESPONDENCE-EMAIL';
SET @permission_to_update_correspondence_phone_code = 'AE-UPDATE-CORRESPONDENCE-PHONE';

SET @ao1 = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1');
SET @ao2 = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2');
SET @ve = (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER');
SET @scheme_manager = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT');
SET @scheme_user = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');
SET @aedm = (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DESIGNATED-MANAGER');
SET @aed = (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DELEGATE');

-- add permissions for update site details : classes, type, status
INSERT INTO `permission` (`name`, `code`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
  ('User can update address in AE registered office', @permission_to_update_registered_office_address_code, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('User can update email in AE registered office', @permission_to_update_registered_office_email_code, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('User can update telephone in AE registered office', @permission_to_update_registered_office_phone_code, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('User can update address in AE correspondence', @permission_to_update_correspondence_address_code, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('User can update email in AE correspondence', @permission_to_update_correspondence_email_code, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('User can update telephone in AE correspondence', @permission_to_update_correspondence_phone_code, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @permission_to_update_registered_office_address = (SELECT `id` FROM `permission` WHERE `code` = @permission_to_update_registered_office_address_code);
SET @permission_to_update_registered_office_email = (SELECT `id` FROM `permission` WHERE `code` = @permission_to_update_registered_office_email_code);
SET @permission_to_update_registered_office_phone = (SELECT `id` FROM `permission` WHERE `code` = @permission_to_update_registered_office_phone_code);
SET @permission_to_update_correspondence_address = (SELECT `id` FROM `permission` WHERE `code` = @permission_to_update_correspondence_address_code);
SET @permission_to_update_correspondence_email = (SELECT `id` FROM `permission` WHERE `code` = @permission_to_update_correspondence_email_code);
SET @permission_to_update_correspondence_phone = (SELECT `id` FROM `permission` WHERE `code` = @permission_to_update_correspondence_phone_code);

-- add permissions to roles that edit business/correspondence:
INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by` ,`created_on`, `last_updated_by`, `last_updated_on`)
  SELECT `role_id`, `permission_id`, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6) FROM
  (
    SELECT @scheme_manager AS `role_id`, @permission_to_update_registered_office_address AS `permission_id` UNION
    SELECT @scheme_user, @permission_to_update_registered_office_address UNION
    SELECT @ao1, @permission_to_update_registered_office_address UNION
    SELECT @ao2, @permission_to_update_registered_office_address UNION
    SELECT @ve, @permission_to_update_registered_office_address UNION

    SELECT @scheme_manager, @permission_to_update_registered_office_email UNION
    SELECT @scheme_user, @permission_to_update_registered_office_email UNION
    SELECT @ao1, @permission_to_update_registered_office_email UNION
    SELECT @ao2, @permission_to_update_registered_office_email UNION
    SELECT @ve, @permission_to_update_registered_office_email UNION
    SELECT @aedm, @permission_to_update_registered_office_email UNION
    SELECT @aed, @permission_to_update_registered_office_email UNION

    SELECT @scheme_manager, @permission_to_update_registered_office_phone UNION
    SELECT @scheme_user, @permission_to_update_registered_office_phone UNION
    SELECT @ao1, @permission_to_update_registered_office_phone UNION
    SELECT @ao2, @permission_to_update_registered_office_phone UNION
    SELECT @ve, @permission_to_update_registered_office_phone UNION
    SELECT @aedm, @permission_to_update_registered_office_phone UNION
    SELECT @aed, @permission_to_update_registered_office_phone UNION

    SELECT @scheme_manager, @permission_to_update_correspondence_address UNION
    SELECT @scheme_user, @permission_to_update_correspondence_address UNION
    SELECT @ao1, @permission_to_update_correspondence_address UNION
    SELECT @ao2, @permission_to_update_correspondence_address UNION
    SELECT @ve, @permission_to_update_correspondence_address UNION
    SELECT @aedm, @permission_to_update_correspondence_address UNION
    SELECT @aed, @permission_to_update_correspondence_address UNION

    SELECT @scheme_manager, @permission_to_update_correspondence_email UNION
    SELECT @scheme_user, @permission_to_update_correspondence_email UNION
    SELECT @ao1, @permission_to_update_correspondence_email UNION
    SELECT @ao2, @permission_to_update_correspondence_email UNION
    SELECT @ve, @permission_to_update_correspondence_email UNION
    SELECT @aedm, @permission_to_update_correspondence_email UNION
    SELECT @aed, @permission_to_update_correspondence_email UNION

    SELECT @scheme_manager, @permission_to_update_correspondence_phone UNION
    SELECT @scheme_user, @permission_to_update_correspondence_phone UNION
    SELECT @ao1, @permission_to_update_correspondence_phone UNION
    SELECT @ao2, @permission_to_update_correspondence_phone UNION
    SELECT @ve, @permission_to_update_correspondence_phone UNION
    SELECT @aedm, @permission_to_update_correspondence_phone UNION
    SELECT @aed, @permission_to_update_correspondence_phone
  ) AS union_alias;

