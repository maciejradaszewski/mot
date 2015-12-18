SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');
SET @person_auth_type_lookup_id = (SELECT `id` FROM `person_auth_type_lookup` WHERE `code` = 'PIN');
SET @title_id = (SELECT `id` FROM `title` WHERE `code` = 'MR');
SET @driving_licence_id = (SELECT `id` FROM `licence` WHERE `licence_number` = 'GARDN605109C99LY60' LIMIT 1);
SET @gender_id = (SELECT `id` FROM `gender` WHERE `code` = 'M');
SET @transition_status_id = (SELECT `id` FROM `transition_status` WHERE `code` = 'FULL');

INSERT INTO `person` (`username`, `pin`, `person_auth_type_lookup_id`, `user_reference`, `mot_one_user_id`, `title_id`, `first_name`, `middle_name`, `family_name`, `driving_licence_id`, `gender_id`, `date_of_birth`, `disability`, `demo_test_tester_status_id`, `otp_failed_attempts`, `is_account_claim_required`, `is_password_change_required`, `transition_status_id`, `mot1_userid`, `mot1_current_smartcard_id`, `2fa_token_id`, `2fa_token_sent_on`, `details_confirmed_on`, `first_training_test_done_on`, `first_live_test_done_on`, `is_deceased`, `deceased_on`, `mot1_details_updated_on`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
VALUES
   ('cscm', '$2y$10$a7prN/y9lTc9HxxvvLhghOf5ms9LbwbPxclKlnH2j/HMDCa5jfhTe', @person_auth_type_lookup_id, 'd1953e9c-2c25-11e4-a784-08002215d516', NULL, @title_id, 'John', '(Customer Service Centre Manager)', 'Rambo', @driving_licence_id, @gender_id, '1980-01-01', NULL, NULL, NULL, 0, 0, @transition_status_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6));

INSERT INTO
  `person_system_role_map` (`person_id`, `person_system_role_id`, `status_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
VALUES
  (
    (SELECT `id` FROM `person` WHERE `username` = 'cscm'),
    (SELECT `id` FROM `person_system_role` WHERE `name` = 'USER'),
    (SELECT `id` FROM `business_role_status` WHERE `code` = 'AC'),
    @app_user_id,
    CURRENT_TIMESTAMP (6),
    @app_user_id,
    CURRENT_TIMESTAMP (6)
  ),
  (
    (SELECT `id` FROM `person` WHERE `username` = 'cscm'),
    (SELECT `id` FROM `person_system_role` WHERE `name` = 'CUSTOMER-SERVICE-MANAGER'),
    (SELECT `id` FROM `business_role_status` WHERE `code` = 'AC'),
    @app_user_id,
    CURRENT_TIMESTAMP (6),
    @app_user_id,
    CURRENT_TIMESTAMP (6)
  );
