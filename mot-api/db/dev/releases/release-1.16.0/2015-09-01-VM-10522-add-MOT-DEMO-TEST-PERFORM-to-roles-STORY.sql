# VM-10522
# added new permission 'MOT-DEMO-TEST-PERFORM' to a list of roles provided by PO (Chris Price)
# document with list of these roles is attahced in jira to this ticket

SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

INSERT INTO `permission` (`name`, `code`, `created_by`) VALUES
  ('MOT demo test perform', 'MOT-DEMO-TEST-PERFORM', @created_by);

SET @mot_demo_test_perform_id = (SELECT `id` FROM `permission` WHERE `code` = 'MOT-DEMO-TEST-PERFORM');

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DELEGATE'),
    @mot_demo_test_perform_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DESIGNATED-MANAGER'),
    @mot_demo_test_perform_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DEMOTEST'),
    @mot_demo_test_perform_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1'),
    @mot_demo_test_perform_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT'),
    @mot_demo_test_perform_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER'),
    @mot_demo_test_perform_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'SITE-ADMIN'),
    @mot_demo_test_perform_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'SITE-MANAGER'),
    @mot_demo_test_perform_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'TESTER'),
    @mot_demo_test_perform_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'TESTER-ACTIVE'),
    @mot_demo_test_perform_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'TESTER-APPLICANT-DEMO-TEST-REQUIRED'),
    @mot_demo_test_perform_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'TESTER-APPLICANT-INITIAL-TRAINING-FAILED'),
    @mot_demo_test_perform_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'TESTER-APPLICANT-INITIAL-TRAINING-REQUIR'),
    @mot_demo_test_perform_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'TESTER-INACTIVE'),
    @mot_demo_test_perform_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER'),
    @mot_demo_test_perform_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-MANAGER'),
    @mot_demo_test_perform_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE'),
    @mot_demo_test_perform_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'FINANCE'),
    @mot_demo_test_perform_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVLA-OPERATIVE'),
    @mot_demo_test_perform_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2'),
    @mot_demo_test_perform_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'GVTS-TESTER'),
    @mot_demo_test_perform_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVLA-MANAGER'),
    @mot_demo_test_perform_id,
    @created_by
  );
