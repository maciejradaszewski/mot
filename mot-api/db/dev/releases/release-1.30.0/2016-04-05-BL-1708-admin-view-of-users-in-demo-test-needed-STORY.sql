-- BL-1708- add permissions to roles that can see the list of users in demo test needed state

SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data');
SET @view_users_in_dtn_state_code = 'VIEW-USERS-IN-DEMO-TEST-NEEDED-STATE';

INSERT INTO `permission`(`name`, `code`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
  ('Display list of users in demo test needed state', @view_users_in_dtn_state_code,
   @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @view_users_in_dtn_state_id = (SELECT `id` FROM `permission` WHERE `code` = @view_users_in_dtn_state_code);

SET @ve             = (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER');
SET @ao1            = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1');
SET @ao2            = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2');
SET @scheme_manager = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT');
SET @scheme_user    = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');
SET @csco           = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE');

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by` ,`created_on`, `last_updated_by`, `last_updated_on`)
  SELECT `role_id`, `permission_id`, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6) FROM
  (
    SELECT @ve AS `role_id`,@view_users_in_dtn_state_id AS `permission_id` UNION
    SELECT @ao1,            @view_users_in_dtn_state_id UNION
    SELECT @ao2,            @view_users_in_dtn_state_id UNION
    SELECT @scheme_manager, @view_users_in_dtn_state_id UNION
    SELECT @scheme_user,    @view_users_in_dtn_state_id UNION
    SELECT @csco,           @view_users_in_dtn_state_id
  ) AS union_alias;
