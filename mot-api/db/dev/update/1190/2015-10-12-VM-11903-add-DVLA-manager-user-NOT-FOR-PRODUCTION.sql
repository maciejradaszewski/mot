-- VM-11903
-- Add new user that has only DVLA-MANAGER role

SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');
SET @roleManager = (SELECT `id` FROM `person_system_role` WHERE `name` = 'DVLA-MANAGER');
SET @roleUser = (SELECT `id` FROM `person_system_role` WHERE `name` = 'USER');
SET @usernameManager = 'dvlaManager';

INSERT INTO `person` (`username`, `pin`, `person_auth_type_lookup_id`, `user_reference`, `mot_one_user_id`, `transition_status_id`, `title_id`, `first_name`, `middle_name`, `family_name`, `driving_licence_id`, `gender_id`, `date_of_birth`, `is_account_claim_required`, `mot1_details_updated_on`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
  (@usernameManager,'$2y$10$a7prN/y9lTc9HxxvvLhghOf5ms9LbwbPxclKlnH2j/HMDCa5jfhTe', 1, @app_user_id,NULL ,5,'1','DVLA',NULL,'MANAGER','1','1','1981-04-24','0',NULL,NULL,'1','2015-10-12 11:56:59.821907',NULL,'2015-10-12 11:56:59.883529','1','0');

SET @userIdManager = (SELECT `id` FROM `person` WHERE `username` = @usernameManager);

INSERT INTO `person_system_role_map` (`person_id`, `person_system_role_id`, `status_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
  (@userIdManager, @roleUser, 1, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  (@userIdManager, @roleManager, 1, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));
