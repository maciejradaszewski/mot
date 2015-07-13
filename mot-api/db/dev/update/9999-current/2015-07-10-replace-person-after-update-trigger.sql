# DROP TRIGGER IF EXISTS tr_person_au;
#
# CREATE DEFINER=`root`@`127.0.0.1` TRIGGER `tr_person_au` AFTER UPDATE
# ON `person` FOR EACH ROW
#     INSERT INTO  `person_hist` (`hist_transaction_type`, `hist_batch_number`, `id`,
#                               `username`,
#                               `pin`,
#                               `user_reference`,
#                               `mot_one_user_id`,
#                               `title_id`,
#                               `first_name`,
#                               `middle_name`,
#                               `family_name`,
#                               `driving_licence_id`,
#                               `gender_id`,
#                               `date_of_birth`,
#                               `disability`,
#                               `demo_test_tester_status_id`,
#                               `otp_failed_attempts`,
#                               `is_account_claim_required`,
#                               `is_password_change_required`,
#                               `transition_status_id`,
#                               `mot1_userid`,
#                               `mot1_current_smartcard_id`,
#                               `2fa_token_id`,
#                               `2fa_token_sent_on`,
#                               `details_confirmed_on`,
#                               `first_training_test_done_on`,
#                               `first_live_test_done_on`,
#                               `is_deceased`,
#                               `deceased_on`,
#                               `mot1_details_updated_on`,
#                               `mot1_legacy_id`,
#                               `created_by`,
#                               `created_on`,
#                               `last_updated_by`,
#                               `last_updated_on`,
#                               `version`,
#                               `batch_number`)
#     VALUES ('U', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`id`,
#           OLD.`username`,
#           OLD.`pin`,
#           OLD.`user_reference`,
#           OLD.`mot_one_user_id`,
#           OLD.`title_id`,
#           OLD.`first_name`,
#           OLD.`middle_name`,
#           OLD.`family_name`,
#           OLD.`driving_licence_id`,
#           OLD.`gender_id`,
#           OLD.`date_of_birth`,
#           OLD.`disability`,
#           OLD.`demo_test_tester_status_id`,
#           OLD.`otp_failed_attempts`,
#           OLD.`is_account_claim_required`,
#           OLD.`is_password_change_required`,
#           OLD.`transition_status_id`,
#           OLD.`mot1_userid`,
#           OLD.`mot1_current_smartcard_id`,
#           OLD.`2fa_token_id`,
#           OLD.`2fa_token_sent_on`,
#           OLD.`details_confirmed_on`,
#           OLD.`first_training_test_done_on`,
#           OLD.`first_live_test_done_on`,
#           OLD.`is_deceased`,
#           OLD.`deceased_on`,
#           OLD.`mot1_details_updated_on`,
#           OLD.`mot1_legacy_id`,
#           OLD.`created_by`,
#           OLD.`created_on`,
#           OLD.`last_updated_by`,
#           OLD.`last_updated_on`,
#           OLD.`version`,
#           OLD.`batch_number`);
#
#
#
#
