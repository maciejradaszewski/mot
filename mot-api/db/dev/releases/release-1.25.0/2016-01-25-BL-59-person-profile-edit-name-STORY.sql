SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

/* List of Roles */
SET @role__ao1 = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1');
SET @role__ao2 = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2');
SET @role__scheme_manager = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT');
SET @role__scheme_user = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');
SET @role__ve = (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER');

/* Add New Permissions */
INSERT INTO
  `permission` (`name`, `code`, `is_restricted`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
VALUES
  (
    'Edit person name',
    'EDIT-PERSON-NAME',
    0,
    @app_user_id,
    CURRENT_TIMESTAMP (6),
    @app_user_id,
    CURRENT_TIMESTAMP (6)
  );

SET @permission__edit_person_name = (SELECT `id` FROM `permission` WHERE `code` = 'EDIT-PERSON-NAME');

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`, `last_updated_by`, `created_on`, `last_updated_on`)
VALUES
  (
    @role__ao1,
    @permission__edit_person_name,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__ao2,
    @permission__edit_person_name,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__scheme_manager,
    @permission__edit_person_name,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__scheme_user,
    @permission__edit_person_name,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__ve,
    @permission__edit_person_name,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
);

