SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

/* List of Roles */
SET @role__csm = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-MANAGER');

/* List of Permissions */
SET @permission__create_message_for_other_user = (SELECT `id` FROM `permission` WHERE `code` = 'CREATE-MESSAGE-FOR-OTHER-USER');
SET @permission__event_read = (SELECT `id` FROM `permission` WHERE `code` = 'EVENT-READ');
SET @permission__person_basic_data_read = (SELECT `id` FROM `permission` WHERE `code` = 'PERSON-BASIC-DATA-READ');

/* Add Permissions to Roles */
INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`, `last_updated_by`, `created_on`, `last_updated_on`)
VALUES
  (
    @role__csm,
    @permission__create_message_for_other_user,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__csm,
    @permission__event_read,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  ),
  (
    @role__csm,
    @permission__person_basic_data_read,
    @app_user_id,
    @app_user_id,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  );