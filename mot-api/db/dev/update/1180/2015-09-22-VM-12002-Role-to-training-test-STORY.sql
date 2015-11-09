SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');
SET @vehicle_read = (SELECT `id` FROM `permission` WHERE `code` ='VEHICLE-READ');

INSERT INTO `role_permission_map` (`role_id`,`permission_id`,`created_by`, `last_updated_by`, `last_updated_on`)
VALUES((SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DELEGATE'), @vehicle_read, @created_by, @created_by, CURRENT_TIMESTAMP(6)),
      ((SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DESIGNATED-MANAGER'), @vehicle_read, @created_by, @created_by, CURRENT_TIMESTAMP(6)),
      ((SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-MANAGER'), @vehicle_read, @created_by, @created_by, CURRENT_TIMESTAMP(6)),
      ((SELECT `id` FROM `role` WHERE `code` = 'DEMOTEST'), @vehicle_read, @created_by, @created_by, CURRENT_TIMESTAMP(6)),
      ((SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER'), @vehicle_read, @created_by, @created_by, CURRENT_TIMESTAMP(6)),
      ((SELECT `id` FROM `role` WHERE `code` = 'FINANCE'), @vehicle_read, @created_by, @created_by, CURRENT_TIMESTAMP(6)),
      ((SELECT `id` FROM `role` WHERE `code` = 'GVTS-TESTER'), @vehicle_read, @created_by, @created_by, CURRENT_TIMESTAMP(6)),
      ((SELECT `id` FROM `role` WHERE `code` = 'SITE-ADMIN'), @vehicle_read, @created_by, @created_by, CURRENT_TIMESTAMP(6)),
      ((SELECT `id` FROM `role` WHERE `code` = 'TESTER-ACTIVE'), @vehicle_read, @created_by, @created_by, CURRENT_TIMESTAMP(6)),
      ((SELECT `id` FROM `role` WHERE `code` = 'TESTER-APPLICANT-INITIAL-TRAINING-FAILED'), @vehicle_read, @created_by, @created_by, CURRENT_TIMESTAMP(6)),
      ((SELECT `id` FROM `role` WHERE `code` = 'TESTER-APPLICANT-INITIAL-TRAINING-REQUIR'), @vehicle_read, @created_by, @created_by, CURRENT_TIMESTAMP(6)),
      ((SELECT `id` FROM `role` WHERE `code` = 'TESTER-INACTIVE'), @vehicle_read, @created_by, @created_by, CURRENT_TIMESTAMP(6));