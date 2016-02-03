SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

SET @rfr_id_3457 = 10108;
SET @rfr_id_12 = 10109;

SET @lang_en = (SELECT `id` FROM `language_type` WHERE `code` = "EN");
SET @lang_cy = (SELECT `id` FROM `language_type` WHERE `code` = "CY");

INSERT INTO `reason_for_rejection`
  (`id`, `test_item_category_id`, `test_item_selector_name`, `test_item_selector_name_cy`, `inspection_manual_reference`, `minor_item`, `location_marker`, `qt_marker`, `note`, `manual`, `spec_proc`, `is_advisory`, `is_prs_fail`, `section_test_item_selector_id`, `can_be_dangerous`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
VALUES
  (@rfr_id_3457, 5073, "Lamps, Reflectors and Electrical Equipment > Headlamp aim", "", "1.8.B.1", 0, 1, 1, 0, 3, 0, 0, 1, 5000, 1, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  (@rfr_id_12  , 39,   "Motorcycle lighting and signalling > Headlamp aim", "", "1.6.B.1", 0, 1, 1, 0, 3, 0, 0, 1, 1, 1, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));


INSERT INTO `rfr_language_content_map`
  (`rfr_id`, `language_type_id`, `name`, `inspection_manual_description`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
VALUES
  (@rfr_id_3457, @lang_en, 'so that beam "kick up" is not visible on the screen', 'The beam image contains a "Kick up" that is not visible on the screen.', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  (@rfr_id_3457, @lang_cy, 'fel nad yw "tro" y pelydryn yn weladwy ar y sgrin', 'The beam image contains a "Kick up" that is not visible on the screen.', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  (@rfr_id_12, @lang_en, 'so that beam "kick up" is not visible on the screen', 'The beam image contains a "Kick up" that is not visible on the screen.', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  (@rfr_id_12, @lang_cy, 'fel nad yw "tro" y pelydryn yn weladwy ar y sgrin', 'The beam image contains a "Kick up" that is not visible on the screen.', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));


INSERT INTO `rfr_vehicle_class_map` (`rfr_id`, `vehicle_class_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
VALUES
  (@rfr_id_3457, (SELECT `id` FROM `vehicle_class` WHERE `code` = 3), @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  (@rfr_id_3457, (SELECT `id` FROM `vehicle_class` WHERE `code` = 4), @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  (@rfr_id_3457, (SELECT `id` FROM `vehicle_class` WHERE `code` = 5), @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  (@rfr_id_3457, (SELECT `id` FROM `vehicle_class` WHERE `code` = 7), @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  (@rfr_id_12,   (SELECT `id` FROM `vehicle_class` WHERE `code` = 1), @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  (@rfr_id_12,   (SELECT `id` FROM `vehicle_class` WHERE `code` = 2), @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));
