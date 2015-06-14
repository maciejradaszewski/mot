DROP TRIGGER `tr_mot_test_au`;

ALTER TABLE `mot_test`
  ADD COLUMN `client_ip` varchar(45) DEFAULT "0.0.0.0" NULL;

ALTER TABLE `mot_test_hist`
  ADD COLUMN `client_ip` varchar(45) DEFAULT NULL;

CREATE TRIGGER `tr_mot_test_au` AFTER
  UPDATE 
  ON `mot_test` FOR EACH ROW
  INSERT INTO `mot`.`mot_test_hist` (
                          `id`, 
                          `person_id`, 
                          `vehicle_id`, 
                          `vehicle_version`, 
                          `document_id`, 
                          `site_id`, 
                          `primary_colour_id`, 
                          `secondary_colour_id`, 
                          `vehicle_class_id`, 
                          `tested_as_fuel_type_id`, 
                          `vin`, 
                          `empty_vin_reason_id`, 
                          `registration`, 
                          `empty_vrm_reason_id`, 
                          `make_id`, 
                          `model_id`, 
                          `model_detail_id`, 
                          `country_of_registration_id`, 
                          `has_registration`, 
                          `mot_test_type_id`, 
                          `started_date`, 
                          `completed_date`, 
                          `status_id`, 
                          `issued_date`, 
                          `expiry_date`, 
                          `mot_test_id_original`, 
                          `prs_mot_test_id`, 
                          `mot_test_reason_for_cancel_id`, 
                          `reason_for_cancel_comment_id`, 
                          `reason_for_termination_comment`, 
                          `full_partial_retest_id`, 
                          `partial_reinspection_comment_id`, 
                          `items_not_tested_comment_id`, 
                          `one_person_test`, 
                          `one_person_reinspection`, 
                          `complaint_ref`, 
                          `number`, 
                          `odometer_reading_id`, 
                          `private`, 
                          `emergency_log_id`, 
                          `emergency_reason_lookup_id`, 
                          `emergency_reason_comment_id`, 
                          `vehicle_weight_source_lookup_id`, 
                          `vehicle_weight`, 
                          `incognito_vehicle_id`, 
                          `address_comment_id`, 
                          `mot1_legacy_id`, 
                          `created_by`, 
                          `created_on`, 
                          `last_updated_by`, 
                          `last_updated_on`, 
                          `version`, 
                          `batch_number`, 
                          `make_name`, 
                          `model_name`, 
                          `model_detail_name`, 
                          `client_ip` 
              ) 
              VALUES 
              ( 
                          old.`id`, 
                          old.`person_id`, 
                          old.`vehicle_id`, 
                          old.`vehicle_version`, 
                          old.`document_id`, 
                          old.`site_id`, 
                          old.`primary_colour_id`, 
                          old.`secondary_colour_id`, 
                          old.`vehicle_class_id`, 
                          old.`tested_as_fuel_type_id`, 
                          old.`vin`, 
                          old.`empty_vin_reason_id`, 
                          old.`registration`, 
                          old.`empty_vrm_reason_id`, 
                          old.`make_id`, 
                          old.`model_id`, 
                          old.`model_detail_id`, 
                          old.`country_of_registration_id`, 
                          old.`has_registration`, 
                          old.`mot_test_type_id`, 
                          old.`started_date`, 
                          old.`completed_date`, 
                          old.`status_id`, 
                          old.`issued_date`, 
                          old.`expiry_date`, 
                          old.`mot_test_id_original`, 
                          old.`prs_mot_test_id`, 
                          old.`mot_test_reason_for_cancel_id`, 
                          old.`reason_for_cancel_comment_id`, 
                          old.`reason_for_termination_comment`, 
                          old.`full_partial_retest_id`, 
                          old.`partial_reinspection_comment_id`, 
                          old.`items_not_tested_comment_id`, 
                          old.`one_person_test`, 
                          old.`one_person_reinspection`, 
                          old.`complaint_ref`, 
                          old.`number`, 
                          old.`odometer_reading_id`, 
                          old.`private`, 
                          old.`emergency_log_id`, 
                          old.`emergency_reason_lookup_id`, 
                          old.`emergency_reason_comment_id`, 
                          old.`vehicle_weight_source_lookup_id`, 
                          old.`vehicle_weight`, 
                          old.`incognito_vehicle_id`, 
                          old.`address_comment_id`, 
                          old.`mot1_legacy_id`, 
                          old.`created_by`, 
                          old.`created_on`, 
                          old.`last_updated_by`, 
                          old.`last_updated_on`, 
                          old.`version`, 
                          old.`batch_number`, 
                          old.`make_name`, 
                          old.`model_name`, 
                          old.`model_detail_name`, 
                          old.`client_ip` 
              );

DROP TRIGGER `tr_mot_test_ad`;

CREATE TRIGGER `tr_mot_test_ad` after 
  DELETE 
  ON `mot_test` FOR EACH ROW
  INSERT INTO `mot`.`mot_test_hist` (
                          `id`, 
                          `person_id`, 
                          `vehicle_id`, 
                          `vehicle_version`, 
                          `document_id`, 
                          `site_id`, 
                          `primary_colour_id`, 
                          `secondary_colour_id`, 
                          `vehicle_class_id`, 
                          `tested_as_fuel_type_id`, 
                          `vin`, 
                          `empty_vin_reason_id`, 
                          `registration`, 
                          `empty_vrm_reason_id`, 
                          `make_id`, 
                          `model_id`, 
                          `model_detail_id`, 
                          `country_of_registration_id`, 
                          `has_registration`, 
                          `mot_test_type_id`, 
                          `started_date`, 
                          `completed_date`, 
                          `status_id`, 
                          `issued_date`, 
                          `expiry_date`, 
                          `mot_test_id_original`, 
                          `prs_mot_test_id`, 
                          `mot_test_reason_for_cancel_id`, 
                          `reason_for_cancel_comment_id`, 
                          `reason_for_termination_comment`, 
                          `full_partial_retest_id`, 
                          `partial_reinspection_comment_id`, 
                          `items_not_tested_comment_id`, 
                          `one_person_test`, 
                          `one_person_reinspection`, 
                          `complaint_ref`, 
                          `number`, 
                          `odometer_reading_id`, 
                          `private`, 
                          `emergency_log_id`, 
                          `emergency_reason_lookup_id`, 
                          `emergency_reason_comment_id`, 
                          `vehicle_weight_source_lookup_id`, 
                          `vehicle_weight`, 
                          `incognito_vehicle_id`, 
                          `address_comment_id`, 
                          `mot1_legacy_id`, 
                          `created_by`, 
                          `created_on`, 
                          `last_updated_by`, 
                          `last_updated_on`, 
                          `version`, 
                          `batch_number`, 
                          `make_name`, 
                          `model_name`, 
                          `model_detail_name`, 
                          `client_ip` 
              ) 
              VALUES 
              ( 
                          old.`id`, 
                          old.`person_id`, 
                          old.`vehicle_id`, 
                          old.`vehicle_version`, 
                          old.`document_id`, 
                          old.`site_id`, 
                          old.`primary_colour_id`, 
                          old.`secondary_colour_id`, 
                          old.`vehicle_class_id`, 
                          old.`tested_as_fuel_type_id`, 
                          old.`vin`, 
                          old.`empty_vin_reason_id`, 
                          old.`registration`, 
                          old.`empty_vrm_reason_id`, 
                          old.`make_id`, 
                          old.`model_id`, 
                          old.`model_detail_id`, 
                          old.`country_of_registration_id`, 
                          old.`has_registration`, 
                          old.`mot_test_type_id`, 
                          old.`started_date`, 
                          old.`completed_date`, 
                          old.`status_id`, 
                          old.`issued_date`, 
                          old.`expiry_date`, 
                          old.`mot_test_id_original`, 
                          old.`prs_mot_test_id`, 
                          old.`mot_test_reason_for_cancel_id`, 
                          old.`reason_for_cancel_comment_id`, 
                          old.`reason_for_termination_comment`, 
                          old.`full_partial_retest_id`, 
                          old.`partial_reinspection_comment_id`, 
                          old.`items_not_tested_comment_id`, 
                          old.`one_person_test`, 
                          old.`one_person_reinspection`, 
                          old.`complaint_ref`, 
                          old.`number`, 
                          old.`odometer_reading_id`, 
                          old.`private`, 
                          old.`emergency_log_id`, 
                          old.`emergency_reason_lookup_id`, 
                          old.`emergency_reason_comment_id`, 
                          old.`vehicle_weight_source_lookup_id`, 
                          old.`vehicle_weight`, 
                          old.`incognito_vehicle_id`, 
                          old.`address_comment_id`, 
                          old.`mot1_legacy_id`, 
                          old.`created_by`, 
                          old.`created_on`, 
                          old.`last_updated_by`, 
                          old.`last_updated_on`, 
                          old.`version`, 
                          old.`batch_number`, 
                          old.`make_name`, 
                          old.`model_name`, 
                          old.`model_detail_name`, 
                          old.`client_ip` 
              )
