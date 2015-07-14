SET @created_by = (SELECT `id` FROM `person` WHERE `username` = 'static data');

INSERT INTO `role` (`code`, `name`, `created_by`)
VALUES
	('VM-10519-USER', 'VM-10519 User', @created_by);

SET @role_id = (SELECT `id` FROM `role` WHERE `code` = 'VM-10519-USER');

INSERT INTO `person_system_role` (`name`, `full_name`, `short_name`, `role_id`, `created_by`)
VALUES
	('VM-10519-USER', 'VM-10519 User', 'VM10519USER', @role_id, @created_by);

SET @permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'ASSESS-DEMO-TEST');

INSERT INTO `role_permission_map` ( `role_id`, `permission_id`, `created_by`)
VALUES
	(@role_id, @permission_id, @created_by);

-- create new person
INSERT INTO `person` (`username`, `pin`, `user_reference`, `title_id`, `first_name`, `middle_name`, `family_name`, `driving_licence_id`, `gender_id`, `date_of_birth`, `is_account_claim_required`, `is_password_change_required`, `transition_status_id`,  `is_deceased`, `created_by`)
VALUES
	('vm10519user', '$2y$10$a7prN/y9lTc9HxxvvLhghOf5ms9LbwbPxclKlnH2j/HMDCa5jfhTe', 'ventures-0007-40cb-ad57-b8290ee036b5', 1, 'Vm10519', 'VE', 'User', 1, 1, '1970-01-29', 0, 0, 5, 0, 1);

SET @person_id = (SELECT `id` FROM `person` WHERE `username` = 'vm10519user');
SET @role_user_id = (SELECT `id` FROM `person_system_role` WHERE `name` = 'User');
SET @role_ve_id = (SELECT `id` FROM `person_system_role` WHERE `name` = 'VEHICLE-EXAMINER');
SET @role_vm10519_id = (SELECT `id` FROM `person_system_role` WHERE `name` = 'VM-10519-USER');

INSERT INTO `person_system_role_map` (`person_id`, `person_system_role_id`, `status_id`, `created_by`)
VALUES
	(@person_id, @role_user_id, 1, 1),
	(@person_id, @role_ve_id, 1, 1),
	(@person_id, @role_vm10519_id, 1, 1);
