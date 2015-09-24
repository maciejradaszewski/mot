SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');  
SET @demo_test_role_id = (SELECT `id` FROM `role` WHERE `code` =  'TESTER-APPLICANT-DEMO-TEST-REQUIRED');  
SET @vehicle_read = (SELECT `id` FROM `permission` WHERE `code` = 'VEHICLE-READ');  
 
INSERT INTO  `role_permission_map`
(
 `role_id`,
 `permission_id`, 
 `created_by`,
 `last_updated_by`, 
 `last_updated_on`
)
VALUES
(
  @demo_test_role_id,
  @vehicle_read,
  @created_by,
  @created_by,
  CURRENT_TIMESTAMP(6)
);