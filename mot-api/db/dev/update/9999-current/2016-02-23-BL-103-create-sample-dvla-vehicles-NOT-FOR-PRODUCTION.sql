INSERT INTO `dvla_vehicle`
(
  `registration`, `registration_collapsed`, `registration_validation_character`, `vin`, `vin_reversed`, `vin_collapsed`, `vin_collapsed_reversed`,
  `model_code`, `make_code`, `make_in_full`, `colour_1_code`, `colour_2_code`, `propulsion_code`, `designed_gross_weight`, `unladen_weight`,
  `engine_number`, `engine_capacity`, `seating_capacity`, `manufacture_date`, `first_registration_date`, `is_seriously_damaged`,
  `recent_v5_document_number`, `is_vehicle_new_at_first_registration`, `body_type_code`, `wheelplan_code`,
  `sva_emission_standard`, `ct_related_mark`, `vehicle_id`, `dvla_vehicle_id`, `eu_classification`, `mass_in_service_weight`, `mot1_legacy_id`,
  `created_by`, `created_on`, `last_updated_by`, `last_updated_on`
)
VALUES
(
  'REG57X6', 'REG57X6', '9', 'VIN57XXXXXXXXXXX6', '6XXXXXXXXXXX75NIV', 'VIN57XXXXXXXXXXX6', 'VIN57XXXXXXXXXXX6',
	'000', '999', 'AUDI A4', 'S', 'S', '1', 0, 0,
	'ENG NUMXX1', 1595, 2, '2001-09-01', '2001-09-01', 0,
	'11135130322', 1, 18, 'A',
  NULL, NULL, NULL, NULL, NULL, NULL, NULL,
  0, CURRENT_TIMESTAMP (6), NULL, CURRENT_TIMESTAMP (6)
),
(
  'REG12X7', 'REG12X7', '9', 'VIN12XXXXXXXXXXX7', '7XXXXXXXXXXX21NIV', 'VIN12XXXXXXXXXXX7', 'VIN12XXXXXXXXXXX7',
	'000', '999', NULL, 'S', 'S', '1', 0, 0,
	'ENG NUMXX2', 1595, 2, '2001-09-01', '2001-09-01', 0,
	'11135130322', 1, 18, 'A',
  NULL, NULL, NULL, NULL, NULL, NULL, NULL,
  0, CURRENT_TIMESTAMP (6), NULL, CURRENT_TIMESTAMP (6)
)
;
