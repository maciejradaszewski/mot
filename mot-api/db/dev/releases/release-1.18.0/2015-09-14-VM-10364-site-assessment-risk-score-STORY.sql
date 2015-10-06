-- VM-10364
-- site assessment risk score/status - permissions and site events

SET @areaOffice1        = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1');
SET @areaOffice2        = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2');
SET @schemeManagement   = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT');
SET @schemeUser         = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');
SET @ve                 = (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER');


SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');
SET @displayOrder = (SELECT MAX(`display_order`) FROM `event_type_lookup`);

#################################################################################################################
-- UPDATE_SITE_ASSESSMENT_RISK_SCORE (event type)
#################################################################################################################

INSERT INTO `event_type_lookup`
(`code`, `name`, `display_order`, `start_date`, `end_date`, `mot1_legacy_id`, `last_updated_by`, `last_updated_on`, `created_by`)
VALUES
('USARS', 'Update site assessment risk score', @displayOrder + 1, '1900-01-01', null, null, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id);

#################################################################################################################
-- VTS_VIEW_SITE_RISK_ASSESSMENT (permission)
#################################################################################################################

INSERT INTO permission (`code`, `name`, `last_updated_by`, `last_updated_on`, created_by) VALUE
  ('VTS-VIEW-SITE-RISK-ASSESSMENT' ,'View site risk assessment score', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id);

SET @permission = (SELECT `id` FROM `permission` WHERE `code` = 'VTS-VIEW-SITE-RISK-ASSESSMENT');

INSERT INTO role_permission_map (`role_id`, `permission_id`, `last_updated_by`, `last_updated_on`, `created_by`) VALUES
  (@areaOffice1,      @permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id),
  (@areaOffice2,      @permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id),
  (@schemeManagement, @permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id),
  (@schemeUser,       @permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id),
  (@ve,               @permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id)
;

#################################################################################################################
-- VTS_UPDATE_SITE_RISK_ASSESSMENT (permission)
#################################################################################################################

INSERT INTO permission (`code`, `name`, `last_updated_by`, `last_updated_on`, created_by) VALUE
  ('VTS-UPDATE-SITE-RISK-ASSESSMENT' ,'Update site risk assessment score', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id);

SET @permission = (SELECT `id` FROM `permission` WHERE `code` = 'VTS-UPDATE-SITE-RISK-ASSESSMENT');

INSERT INTO role_permission_map (`role_id`, `permission_id`, `last_updated_by`, `last_updated_on`, `created_by`) VALUES
  (@areaOffice1,      @permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id),
  (@areaOffice2,      @permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id),
  (@schemeManagement, @permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id),
  (@schemeUser,       @permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id),
  (@ve,               @permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id)
;