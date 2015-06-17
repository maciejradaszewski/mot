-- VM-9675
-- The following internal accounts need to be available on the production environment:
-- 1) For DVLA import (a.k.a etl_user). This account should be able to issue replacement certificates without entering a pin. This is used for cherished accounts
-- 2) DVLA OpenInterface User

INSERT INTO `person` (`id`, `username`, `pin`, `user_reference`, `mot_one_user_id`, `title_id`, `first_name`, `middle_name`, `family_name`, `driving_licence_id`, `gender_id`, `date_of_birth`, `disability`, `demo_test_tester_status_id`, `otp_failed_attempts`, `is_account_claim_required`, `transition_status_id`, `mot1_userid`, `mot1_current_smartcard_id`, `2fa_token_id`, `2fa_token_sent_on`, `details_confirmed_on`, `first_training_test_done_on`, `first_live_test_done_on`, `is_deceased`, `deceased_on`, `mot1_details_updated_on`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number`)
VALUES
  (3, 'dvla-import', '', NULL, NULL, NULL, ' ', ' ', ' ', NULL, NULL, '0000-00-00', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, '2014-12-05 11:56:51.902817', NULL, NULL, 2, 0);

INSERT INTO `person` (`id`, `username`, `pin`, `user_reference`, `mot_one_user_id`, `title_id`, `first_name`, `middle_name`, `family_name`, `driving_licence_id`, `gender_id`, `date_of_birth`, `disability`, `demo_test_tester_status_id`, `otp_failed_attempts`, `is_account_claim_required`, `transition_status_id`, `mot1_userid`, `mot1_current_smartcard_id`, `2fa_token_id`, `2fa_token_sent_on`, `details_confirmed_on`, `first_training_test_done_on`, `first_live_test_done_on`, `is_deceased`, `deceased_on`, `mot1_details_updated_on`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number`)
VALUES
  (5, 'dvla-openinterface', '', NULL, NULL, NULL, ' ', ' ', ' ', NULL, NULL, '0000-00-00', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, '2014-12-05 11:56:51.902817', NULL, NULL, 2, 0);
