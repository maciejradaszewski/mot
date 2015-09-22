-- VM-10407
-- permissions to update vts: testing facilities and site details

SET @areaOffice1        = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1');
SET @areaOffice2        = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2');

SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

SET @displayOrder = (SELECT MAX(`display_order`) FROM `event_type_lookup`);


########################################################################################################################
--  VTS-UPDATE-SITE-DETAILS
########################################################################################################################

INSERT INTO permission (`code`, `name`, created_by) VALUE ('VTS-UPDATE-SITE-DETAILS' ,'Updating site details', @app_user_id);

SET @permission = (SELECT `id` FROM `permission` WHERE `code` = 'VTS-UPDATE-SITE-DETAILS');

INSERT INTO role_permission_map (role_id, permission_id, created_by) VALUES
  (@areaOffice1, @permission, @app_user_id),
  (@areaOffice2, @permission, @app_user_id);


########################################################################################################################
--  VTS-UPDATE-TESTING-FACILITIES-DETAILS
########################################################################################################################

INSERT INTO permission (`code`, `name`, created_by) VALUE ('VTS-UPDATE-TESTING-FACILITIES-DETAILS' ,'Updating site testing facilities', @app_user_id);

SET @permission = (SELECT `id` FROM `permission` WHERE `code` = 'VTS-UPDATE-TESTING-FACILITIES-DETAILS');

INSERT INTO role_permission_map (role_id, permission_id, created_by) VALUES
  (@areaOffice1, @permission, @app_user_id),
  (@areaOffice2, @permission, @app_user_id);


########################################################################################################################
-- DVSA_ADMINISTRATOR_UPDATE_SITE (event type)
########################################################################################################################

INSERT INTO `event_type_lookup`
(`code`, `name`, `display_order`, `start_date`, `end_date`, `mot1_legacy_id`, `last_updated_by`, `last_updated_on`, `created_by`)
VALUES
('US', 'DVSA Administrator Update Site', @displayOrder + 1, '1900-01-01', null, null, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id);

