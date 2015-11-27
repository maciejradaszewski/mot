-- VM-9601
-- A user on behalf of whom the scheduling mechanism will be calling API endpoints to broadcast special notices and expire inactive testers. The password will be managed by OpenAM.
-- In terms of roles, the user would need to hold roles attached to the user used in development currently: sn-cron-job.

-- change id of test users to 100+
  -- this fragment IS NOT FOR PRODUCTION!
  /*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
  UPDATE `person_contact_detail_map`      SET `person_id`=`person_id`+100 WHERE `person_id`>2 AND `person_id`<100;
  UPDATE `auth_for_testing_mot`           SET `person_id`=`person_id`+100 WHERE `person_id`>2 AND `person_id`<100;
  UPDATE `person_security_question_map`   SET `person_id`=`person_id`+100 WHERE `person_id`>2 AND `person_id`<100;
  UPDATE `person_security_question_map`   SET `created_by`=`created_by`+100 WHERE `created_by`>2 AND `created_by`<100;
  UPDATE `person_system_role_map`         SET `person_id`=`person_id`+100 WHERE `person_id`>2 AND `person_id`<100;
  UPDATE `site_business_role_map`         SET `person_id`=`person_id`+100 WHERE `person_id`>2 AND `person_id`<100;
  UPDATE `organisation_business_role_map` SET `person_id`=`person_id`+100 WHERE `person_id`>2 AND `person_id`<100;
  UPDATE `enforcement_site_assessment`    SET `person_id`=`person_id`+100 WHERE `person_id`>2 AND `person_id`<100;
  UPDATE `jasper_document_variables`      SET `created_by`=`created_by`+100 WHERE `created_by`>2 AND `created_by`<100;
  UPDATE `direct_debit`                   SET `person_id`=`person_id`+100 WHERE `person_id`>2 AND `person_id`<100;
  UPDATE `auth_for_ae_person_as_principal_map` SET `person_id`=`person_id`+100 WHERE `person_id`>2 AND `person_id`<100;

  /*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;

  UPDATE `person` SET `id`=`id`+100 WHERE `id`>2 AND `id`<100;
  -- /not for production end.

-- FOR PRODUCTION:
INSERT INTO `person` (`id`, `username`, `pin`, `user_reference`, `mot_one_user_id`, `title_id`, `first_name`, `middle_name`, `family_name`, `driving_licence_id`, `gender_id`, `date_of_birth`, `disability`, `demo_test_tester_status_id`, `otp_failed_attempts`, `is_account_claim_required`, `transition_status_id`, `mot1_userid`, `mot1_current_smartcard_id`, `2fa_token_id`, `2fa_token_sent_on`, `details_confirmed_on`, `first_training_test_done_on`, `first_live_test_done_on`, `is_deceased`, `deceased_on`, `mot1_details_updated_on`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number`)
VALUES
(4, 'cron-job', '', NULL, NULL, NULL, 123456, 'cron_first', 'cron_middle', NULL, NULL, '0000-00-00', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 1, '2014-12-05 11:56:51.902817', NULL, NULL, 2, 0);

SET @cron_old_id = (SELECT `id` FROM `person` WHERE `username`='sn-cron-job');

-- NOT FOR PRODUCTION
-- clean-up after sn-cron-job user
UPDATE `auth_for_testing_mot`         SET `person_id`=4 WHERE `person_id`=@cron_old_id;
UPDATE `person_contact_detail_map`    SET `person_id`=4 WHERE `person_id`=@cron_old_id;
UPDATE `person_security_question_map` SET `person_id`=4 WHERE `person_id`=@cron_old_id;
UPDATE `person_security_question_map` SET `created_by`=4 WHERE `created_by`=@cron_old_id;
UPDATE `person_system_role_map`       SET `person_id`=4 WHERE `person_id`=@cron_old_id;

DELETE FROM `person` WHERE `id`=@cron_old_id;
-- / end.
