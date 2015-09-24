SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');
SET @dvla_import_role_id = (SELECT `id` FROM `role` WHERE `code` = 'DVLA-IMPORT');
SET @p_mot_test_read_all = (SELECT `id` FROM `permission` WHERE `code` = 'MOT-TEST-READ-ALL');

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`, `last_updated_by`, `last_updated_on`)
VALUES
  (
    @dvla_import_role_id,
    @p_mot_test_read_all,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP(6)
  );
