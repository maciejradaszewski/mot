-- VM-11903
-- Add VEHICLE-TESTING-STATION-READ, PERSON-BASIC-DATA-READ, EVENT-READ permissions for DVLA-MANAGER role

SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');
SET @permission_vehicle_station_read_id = (SELECT `id` FROM `permission` WHERE `code` = 'VEHICLE-TESTING-STATION-READ');
SET @permission_person_basic_data_read_id = (SELECT `id` FROM `permission` WHERE `code` = 'PERSON-BASIC-DATA-READ');
SET @permission_event_read_id = (SELECT `id` FROM `permission` WHERE `code` = 'EVENT-READ');
SET @role_id = (SELECT `id` FROM `role` WHERE `code` = 'DVLA-MANAGER');

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
  (@role_id, @permission_vehicle_station_read_id, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  (@role_id, @permission_event_read_id, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  (@role_id, @permission_person_basic_data_read_id, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));
