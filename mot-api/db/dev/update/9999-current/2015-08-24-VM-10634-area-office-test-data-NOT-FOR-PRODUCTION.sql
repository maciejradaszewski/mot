-- create some faked area office site entities for AO pick-list production
-- for Create AE / Edit AE and Create site / Edit Site -> AO referencing.

INSERT INTO `site` (`id`, `organisation_id`, `name`, `site_number`, `default_brake_test_class_1_and_2_id`, `default_service_brake_test_class_3_and_above_id`, `default_parking_brake_test_class_3_and_above_id`, `last_site_assessment_id`, `dual_language`, `scottish_bank_holiday`, `latitude`, `longitude`, `type_id`, `transition_status_id`, `non_working_day_country_lookup_id`, `first_login_by`, `first_login_on`, `first_test_carried_out_by`, `first_test_carried_out_number`, `first_test_carried_out_on`, `first_live_test_carried_out_by`, `first_live_test_carried_out_number`, `first_live_test_carried_out_on`, `mot1_details_updated_on`, `mot1_vts_device_status_id`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number`, `site_status_id`)
VALUES
('3000', '10', 'Area Office 01', '01FOO', '5', '5', '5', NULL, '0', '0', NULL, NULL, '3', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '2014-12-05 11:56:52.497767', NULL, '2015-03-23 09:12:10.330670', '1', '0', '1'),
('3001',  '3', 'Area Office 02', '02BAR', '5', '5', '5', NULL, '0', '0', NULL, NULL, '3', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '2014-12-05 11:56:52.497767', NULL, '2015-03-23 09:12:10.330670', '1', '0', '1'),
('3002',  '3', 'Area Office 03', '03BAZ', '5', '5', '5', NULL, '0', '0', NULL, NULL, '3', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '2014-12-05 11:56:52.497767', NULL, '2015-03-23 09:12:10.330670', '1', '0', '1'),
('3006',  '3', 'Area Office 04', '04BAX', '5', '5', '5', NULL, '0', '0', NULL, NULL, '3', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '2014-12-05 11:56:52.497767', NULL, '2015-03-23 09:12:10.330670', '1', '0', '1'),
('3003',  '2001', 'Area Office 12', '12MAL', '5', '5', '5', NULL, '0', '0', NULL, NULL, '3', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '2014-12-05 11:56:52.497767', NULL, '2015-03-23 09:12:10.330670', '1', '0', '1'),
('3004', '2001', 'Area Office 14', '14DAX', '5', '5', '5', NULL, '0', '0', NULL, NULL, '3', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '2014-12-05 11:56:52.497767', NULL, '2015-03-23 09:12:10.330670', '1', '0', '1'),
('3005', '10', 'Area Office 15', '15DAY', '5', '5', '5', NULL, '0', '0', NULL, NULL, '3', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '2014-12-05 11:56:52.497767', NULL, '2015-03-23 09:12:10.330670', '1', '0', '1'),
('3007', '10', 'Area Office 16', '16DAY', '5', '5', '5', NULL, '0', '0', NULL, NULL, '3', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '2014-12-05 11:56:52.497767', NULL, '2015-03-23 09:12:10.330670', '1', '0', '1'),
('3008',  '3', 'Area Office 05', '05BA1', '5', '5', '5', NULL, '0', '0', NULL, NULL, '3', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '2014-12-05 11:56:52.497767', NULL, '2015-03-23 09:12:10.330670', '1', '0', '1'),
('3009',  '3', 'Area Office 06', '06BA2', '5', '5', '5', NULL, '0', '0', NULL, NULL, '3', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '2014-12-05 11:56:52.497767', NULL, '2015-03-23 09:12:10.330670', '1', '0', '1'),
('3010',  '3', 'Area Office 07', '07BA3', '5', '5', '5', NULL, '0', '0', NULL, NULL, '3', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '2014-12-05 11:56:52.497767', NULL, '2015-03-23 09:12:10.330670', '1', '0', '1'),
('3011',  '3', 'Area Office 08', '08BA4', '5', '5', '5', NULL, '0', '0', NULL, NULL, '3', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '2014-12-05 11:56:52.497767', NULL, '2015-03-23 09:12:10.330670', '1', '0', '1'),
('3012',  '3', 'Area Office 09', '09BA5', '5', '5', '5', NULL, '0', '0', NULL, NULL, '3', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '2014-12-05 11:56:52.497767', NULL, '2015-03-23 09:12:10.330670', '1', '0', '1'),
('3013',  '3', 'Area Office 11', '11BA6', '5', '5', '5', NULL, '0', '0', NULL, NULL, '3', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '2014-12-05 11:56:52.497767', NULL, '2015-03-23 09:12:10.330670', '1', '0', '1')

;

-- establish the assembly group linkage so that the above are classified as being
-- "is an area office for" --- we absolutely do not care about anything other than
-- being able to produce a pick-list of Area Offices for UI operations.

SET @area_office_for_id = (SELECT id
                           FROM assembly_role_type
                           WHERE code = 'AOF');

SET @app_user_id = (SELECT `id`
                    FROM `person`
                    WHERE `username` = 'static data' || `user_reference` = 'Static Data');

INSERT INTO `site_assembly_role_map` (`id`, `site_id`, `assembly_id`, `assembly_role_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
VALUES
  ('1', '3000', '0', '1', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('2', '3001', '0', '1', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('3', '3002', '0', '1', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('4', '3003', '0', '1', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('5', '3004', '0', '1', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('6', '3005', '0', '1', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('7', '3006', '0', '1', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('8', '3007', '0', '1', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('9', '3008', '0', '1', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('10', '3009', '0', '1', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('11', '3010', '0', '1', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('12', '3011', '0', '1', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('13', '3012', '0', '1', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  ('14', '3013', '0', '1', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

