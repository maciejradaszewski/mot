# Fix area offices to be of type 1 (Area Office) and set site number
# to 2 digits by removing anything after the second character
UPDATE site
SET type_id=1, site_number=SUBSTRING(site_number, 1, 2)
WHERE id BETWEEN 3000 AND 3013;

SET @site_status_id = (SELECT `id`
                       FROM `site_status_lookup`
                       WHERE `name` = 'Extinct'
                       LIMIT 1);

# Add area office 10, 13 and 16,17 as extinct so we can verify locally these area offices don't appear
INSERT INTO `site` (`id`, `organisation_id`, `name`, `site_number`, `site_status_id`, `status_changed_on`, `default_brake_test_class_1_and_2_id`, `default_service_brake_test_class_3_and_above_id`, `default_parking_brake_test_class_3_and_above_id`, `last_site_assessment_id`, `dual_language`, `scottish_bank_holiday`, `latitude`, `longitude`, `type_id`, `transition_status_id`, `non_working_day_country_lookup_id`, `first_login_by`, `first_login_on`, `first_test_carried_out_by`, `first_test_carried_out_number`, `first_test_carried_out_on`, `first_live_test_carried_out_by`, `first_live_test_carried_out_number`, `first_live_test_carried_out_on`, `mot1_details_updated_on`, `mot1_vts_device_status_id`, `mot1_legacy_id`, `created_by`)
VALUES
  (3014, 3, 'Area Office 10', '10', @site_status_id, NULL, 5, 5, 5, NULL, 0, 0, NULL, NULL, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
  (3015, 3, 'Area Office 13', '13', @site_status_id, NULL, 5, 5, 5, NULL, 0, 0, NULL, NULL, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
  (3016, 3, 'Area Office 17', '17', @site_status_id, NULL, 5, 5, 5, NULL, 0, 0, NULL, NULL, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
  (3017, 3, 'Area Office 18', '18', @site_status_id, NULL, 5, 5, 5, NULL, 0, 0, NULL, NULL, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
  (3018, 3, 'Area Office 19', '19FOO', @site_status_id, NULL, 5, 5, 5, NULL, 0, 0, NULL, NULL, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1);
