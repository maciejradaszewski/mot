SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

SET @test_item_category_id_10000 = 10000;

SET @section_test_item_selector_id = 0;

INSERT INTO `test_item_category` (`id`, `parent_test_item_category_id`, `section_test_item_category_id`,
                                  `business_rule_id`, `mot1_legacy_id`, `created_by`, `created_on`,
                                  `last_updated_by`, `last_updated_on`, `version`, `batch_number`)
VALUES
  (0, 0, 7000, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0);

INSERT INTO `rfr_vehicle_class_map` (`rfr_id`, `vehicle_class_id`, `business_rule_id`, `mot1_legacy_id`, `created_by`,
                                     `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number`)
VALUES
	(10001, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10001, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10001, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10002, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10002, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10002, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10003, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10003, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10003, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10004, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10004, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10004, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10005, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10005, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10005, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10006, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10006, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10006, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10007, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10007, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10007, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10008, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10008, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10008, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10009, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10009, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10009, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10010, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10010, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10010, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10011, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10011, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10011, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10012, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10012, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10012, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10013, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10013, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10013, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10014, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10014, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10014, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10015, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10015, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10015, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10016, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10016, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10016, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10017, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10017, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10017, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10018, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10018, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10018, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10019, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10019, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10019, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10020, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10020, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10020, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10021, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10021, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10021, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10022, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10022, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10022, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10023, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10023, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10023, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10024, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10024, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10024, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10025, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10025, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10025, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10026, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10026, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10026, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10027, 2, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10027, 4, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0),
	(10027, 7, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP(), NULL, NULL, 1, 0);

INSERT INTO `test_item_category_vehicle_class_map` (`test_item_category_id`, `vehicle_class_id`, `business_rule_id`,
                                                    `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`,
                                                    `last_updated_on`, `version`, `batch_number` )
VALUES
  ('10000', '7', NULL, NULL, '1', CURRENT_TIMESTAMP(), NULL, NULL, '1', '0'),
  ('10000', '5', NULL, NULL, '1', CURRENT_TIMESTAMP(), NULL, NULL, '1', '0'),
  ('10000', '4', NULL, NULL, '1', CURRENT_TIMESTAMP(), NULL, NULL, '1', '0'),
  ('10000', '3', NULL, NULL, '1', CURRENT_TIMESTAMP(), NULL, NULL, '1', '0'),
  ('10000', '2', NULL, NULL, '1', CURRENT_TIMESTAMP(), NULL, NULL, '1', '0'),
  ('10000', '1', NULL, NULL, '1', CURRENT_TIMESTAMP(), NULL, NULL, '1', '0');

DELETE FROM `rfr_language_content_map` WHERE `id` = 3120;
DELETE FROM `rfr_language_content_map` WHERE `id` = 3121;
DELETE FROM `rfr_language_content_map` WHERE `id` = 3113;
DELETE FROM `rfr_language_content_map` WHERE `id` = 3110;
DELETE FROM `rfr_language_content_map` WHERE `id` = 3111;

DELETE FROM `rfr_language_content_map` WHERE `id` = 7215;
DELETE FROM `rfr_language_content_map` WHERE `id` = 7216;
DELETE FROM `rfr_language_content_map` WHERE `id` = 7208;
DELETE FROM `rfr_language_content_map` WHERE `id` = 7205;
DELETE FROM `rfr_language_content_map` WHERE `id` = 7206;

DELETE FROM `rfr_vehicle_class_map` WHERE `rfr_id` = 10017;
DELETE FROM `rfr_vehicle_class_map` WHERE `rfr_id` = 10018;
DELETE FROM `rfr_vehicle_class_map` WHERE `rfr_id` = 10010;
DELETE FROM `rfr_vehicle_class_map` WHERE `rfr_id` = 10007;
DELETE FROM `rfr_vehicle_class_map` WHERE `rfr_id` = 10008;

DELETE FROM `reason_for_rejection` WHERE `id` = 10017;
DELETE FROM `reason_for_rejection` WHERE `id` = 10018;
DELETE FROM `reason_for_rejection` WHERE `id` = 10010;
DELETE FROM `reason_for_rejection` WHERE `id` = 10007;
DELETE FROM `reason_for_rejection` WHERE `id` = 10008;
