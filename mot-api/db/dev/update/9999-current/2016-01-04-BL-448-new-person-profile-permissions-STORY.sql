SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

/* List of Roles */
SET @role__ao1 = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1');
SET @role__ao2 = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2');
SET @role__csco = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE');
SET @role__csm = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-MANAGER');
SET @role__scheme_manager = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT');
SET @role__scheme_user = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');
SET @role__ve = (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER');

/* Add New Permissions */
INSERT INTO
  `permission` (`name`, `code`, `is_restricted`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
VALUES
  (
    'View driving licence details',
    'VIEW-DRIVING-LICENCE',
    0,
    @app_user_id,
    CURRENT_TIMESTAMP (6),
    @app_user_id,
    CURRENT_TIMESTAMP (6)
  ),
  (
    'View the date of birth',
    'VIEW-DATE-OF-BIRTH',
    0,
    @app_user_id,
    CURRENT_TIMESTAMP (6),
    @app_user_id,
    CURRENT_TIMESTAMP (6)
  ),
  (
    'Manage user accounts',
    'MANAGE-USER-ACCOUNTS',
    0,
    @app_user_id,
    CURRENT_TIMESTAMP (6),
    @app_user_id,
    CURRENT_TIMESTAMP (6)
  );

/* List of Permissions */
SET @permission__alter_tester_authorisation_status = (SELECT `id` FROM `permission` WHERE `code` = 'ALTER-TESTER-AUTHORISATION-STATUS');
SET @permission__change_driving_licence = (SELECT `id` FROM `permission` WHERE `code` = 'ADD-EDIT-DRIVING-LICENCE');
SET @permission__manage_user_accounts = (SELECT `id` FROM `permission` WHERE `code` = 'MANAGE-USER-ACCOUNTS');
SET @permission__profile_edit_others_email_address = (SELECT `id` FROM `permission` WHERE `code` = 'PROFILE-EDIT-OTHERS-EMAIL-ADDRESS');
SET @permission__view_date_of_birth = (SELECT `id` FROM `permission` WHERE `code` = 'VIEW-DATE-OF-BIRTH');
SET @permission__view_driving_licence = (SELECT `id` FROM `permission` WHERE `code` = 'VIEW-DRIVING-LICENCE');

/* Add Permissions to Roles */
INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`, `last_updated_by`, `created_on`, `last_updated_on`)
VALUES
  (
    @role__scheme_manager,
    @permission__view_date_of_birth,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__scheme_user,
    @permission__view_date_of_birth,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__ao1,
    @permission__view_date_of_birth,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__ao2,
    @permission__view_date_of_birth,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__ve,
    @permission__view_date_of_birth,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__csm,
    @permission__view_date_of_birth,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__csco,
    @permission__view_date_of_birth,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__scheme_manager,
    @permission__view_driving_licence,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__scheme_user,
    @permission__view_driving_licence,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__ao1,
    @permission__view_driving_licence,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__ao2,
    @permission__view_driving_licence,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__ve,
    @permission__view_driving_licence,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__csm,
    @permission__view_driving_licence,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__csco,
    @permission__view_driving_licence,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__scheme_manager,
    @permission__change_driving_licence,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__scheme_user,
    @permission__change_driving_licence,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__csco,
    @permission__manage_user_accounts,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__csm,
    @permission__manage_user_accounts,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__scheme_user,
    @permission__alter_tester_authorisation_status,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__scheme_manager,
    @permission__alter_tester_authorisation_status,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__scheme_manager,
    @permission__profile_edit_others_email_address,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__scheme_user,
    @permission__profile_edit_others_email_address,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__ao2,
    @permission__profile_edit_others_email_address,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__ve,
    @permission__profile_edit_others_email_address,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__csm,
    @permission__profile_edit_others_email_address,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  );

/* Remove Permissions from Roles */
DELETE FROM `role_permission_map`
WHERE
  `role_id` IN (
    SELECT
      `id`
    FROM
      `role`
    WHERE
      `code` IN (
        'DVLA-OPERATIVE',
        'DVLA-MANAGER'
      )
  )
  AND `permission_id` = (
    SELECT
      `id`
    FROM
      `permission`
    WHERE
      `code` = 'VIEW-TRADE-ROLES-OF-ANY-USER'
  );