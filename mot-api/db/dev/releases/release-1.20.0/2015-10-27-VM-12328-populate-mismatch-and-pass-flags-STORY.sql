SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

UPDATE `certificate_replacement`
SET `include_in_mismatch_file` = 1,
    `include_in_passes_file` = 1,
    `last_updated_by` = @app_user_id,
    `last_updated_on` = CURRENT_TIMESTAMP(6)
WHERE `is_vin_vrm_expiry_changed` = 1;
