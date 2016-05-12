# BL-1716
# Creating new event type

SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');
SET @display_order = (SELECT MAX(`display_order`) FROM `event_type_lookup`);

INSERT INTO `event_type_lookup` (`code`, `name`, `display_order`, `start_date`, `created_by`,`created_on`, `last_updated_by`, `last_updated_on`)
VALUES
('RGAC', 'Removal of Group A certificate', @display_order + 1, '1900-01-01', @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6)),
('RGBC', 'Removal of Group B certificate', @display_order + 2, '1900-01-01', @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6));