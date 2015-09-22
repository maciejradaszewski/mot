# VM-11893
# Adding permission to allow AO1, AO2 and VE to add a manual event

SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');
SET @permission = 'EVENT-CREATE';
SET @ao1_id = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1');
SET @ao2_id = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2');
SET @ve_id = (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER');

INSERT INTO `permission` (`name`, `code`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
  ('Create a manual event', @permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @permission_id = (SELECT `id` FROM `permission` WHERE `code` = @permission);

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
  (@ao1_id, @permission_id, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  (@ao2_id, @permission_id, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  (@ve_id, @permission_id, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

