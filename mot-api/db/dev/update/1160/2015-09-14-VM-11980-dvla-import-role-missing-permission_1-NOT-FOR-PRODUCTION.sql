SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

INSERT INTO `role` ( `name`, `code`, `is_internal`, `is_trade`,  `created_by`, `last_updated_by`,  `last_updated_on`)
VALUES
	('DVLA Import', 'DVLA-IMPORT', 0, 0, @created_by, @created_by, CURRENT_TIMESTAMP (6));

SET @dvla_import_role_id = (SELECT `id` FROM `role` WHERE `code` = 'DVLA-IMPORT');

INSERT INTO `person_system_role` ( `name`, `full_name`, `short_name`, `role_id`, `created_by`,  `last_updated_by`, `last_updated_on`)
VALUES
	('DVLA-IMPORT', 'DVLA Import ', 'DVLA-IMPORT', @dvla_import_role_id,  @created_by, @created_by, CURRENT_TIMESTAMP (6));

SET @dvla_import_person_system_role_id = (SELECT `id` FROM `person_system_role` WHERE `name` = 'DVLA-IMPORT');

SET @dvla_import_person_id = (SELECT `id` FROM `person` WHERE `username` = 'dvla-import');

INSERT INTO `person_system_role_map` (`person_id`, `person_system_role_id`, `status_id`,  `created_by`, `last_updated_by`, `last_updated_on`)
VALUES
	(@dvla_import_person_id, @dvla_import_person_system_role_id,1, @created_by, @created_by, CURRENT_TIMESTAMP (6));


SET @p_certificate_replacement = (SELECT `id` FROM `permission` WHERE `code` = 'CERTIFICATE-REPLACEMENT');
SET @p_mot_test_read = (SELECT `id` FROM `permission` WHERE `code` = 'MOT-TEST-READ');
SET @p_certificate_replacement_special_fields = (SELECT `id` FROM `permission` WHERE `code` = 'CERTIFICATE-REPLACEMENT-SPECIAL-FIELDS');
SET @p_mot_test_without_otp = (SELECT `id` FROM `permission` WHERE `code` = 'MOT-TEST-WITHOUT-OTP');


INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`, `last_updated_by`, `last_updated_on`)
VALUES
(
    @dvla_import_role_id,
    @p_mot_test_without_otp,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP(6)
  ),
(
    @dvla_import_role_id,
    @p_mot_test_read,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP(6)
  ),
  (
    @dvla_import_role_id,
    @p_certificate_replacement,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP(6)
  ),
  (
    @dvla_import_role_id,
    @p_certificate_replacement_special_fields,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP(6)
  );
