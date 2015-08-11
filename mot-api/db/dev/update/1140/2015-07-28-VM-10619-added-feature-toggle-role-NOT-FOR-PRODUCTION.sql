SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

INSERT INTO `role` (`code`, `name`, `created_by`)
VALUES
	('VM-10619-USER', 'VM-10619 User', @created_by);

SET @role_id = (SELECT `id` FROM `role` WHERE `code` = 'VM-10619-USER');

INSERT INTO `person_system_role` (`name`, `full_name`, `short_name`, `role_id`, `created_by`)
VALUES
	('VM-10619-USER', 'VM-10619 User', 'VM10619USER', @role_id, @created_by);

INSERT INTO `permission` (`name`, `code`, `is_restricted`, `created_by`)
  VALUES ('VM-10619 Role Management', 'MANAGE-DVSA-ROLES', 1, @created_by);

SET @permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'MANAGE-DVSA-ROLES');

INSERT INTO `role_permission_map` ( `role_id`, `permission_id`, `created_by`)
VALUES
	(@role_id, @permission_id, @created_by);

-- create new person
INSERT INTO `person` (`username`, `pin`, `user_reference`, `title_id`, `first_name`, `middle_name`, `family_name`, `driving_licence_id`, `gender_id`, `date_of_birth`, `is_account_claim_required`, `is_password_change_required`, `transition_status_id`,  `is_deceased`, `created_by`)
VALUES
	('vm10619user', '$2y$10$a7prN/y9lTc9HxxvvLhghOf5ms9LbwbPxclKlnH2j/HMDCa5jfhTe', 'ventures-0007-40cb-ad57-b8290ee036b5', 1, 'Vm10619', 'VE', 'User', 1, 1, '1970-01-29', 0, 0, 5, 0, 1);

SET @person_id = (SELECT `id` FROM `person` WHERE `username` = 'vm10619user');
SET @role_user_id = (SELECT `id` FROM `person_system_role` WHERE `name` = 'User');
SET @role_schm_id = (SELECT `id` FROM `person_system_role` WHERE `name` = 'DVSA-SCHEME-MANAGEMENT');
SET @role_vm10619_id = (SELECT `id` FROM `person_system_role` WHERE `name` = 'VM-10619-USER');

SET @role_status_id = (SELECT `id` FROM `business_role_status` WHERE `code` = 'AC'); # Active

INSERT INTO `person_system_role_map` (`person_id`, `person_system_role_id`, `status_id`, `created_by`)
VALUES
	(@person_id, @role_user_id, @role_status_id, @created_by),
	(@person_id, @role_schm_id, @role_status_id, @created_by),
	(@person_id, @role_vm10619_id, @role_status_id, @created_by);
