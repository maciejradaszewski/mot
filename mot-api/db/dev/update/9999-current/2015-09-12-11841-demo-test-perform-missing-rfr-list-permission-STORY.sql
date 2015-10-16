-- VM-11841

SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');
SET @p_rfr_list_id = (SELECT `id` FROM `permission` WHERE `code` = 'RFR-LIST');

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`, `last_updated_by`, `last_updated_on`)
VALUES
  (
    (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DELEGATE'),
    @p_rfr_list_id,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DESIGNATED-MANAGER'),
    @p_rfr_list_id,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DEMOTEST'),
    @p_rfr_list_id,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1'),
    @p_rfr_list_id,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT'),
    @p_rfr_list_id,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER'),
    @p_rfr_list_id,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'SITE-ADMIN'),
    @p_rfr_list_id,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'SITE-MANAGER'),
    @p_rfr_list_id,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'TESTER-ACTIVE'),
    @p_rfr_list_id,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'TESTER-APPLICANT-DEMO-TEST-REQUIRED'),
    @p_rfr_list_id,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'TESTER-APPLICANT-INITIAL-TRAINING-FAILED'),
    @p_rfr_list_id,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'TESTER-APPLICANT-INITIAL-TRAINING-REQUIR'),
    @p_rfr_list_id,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'TESTER-INACTIVE'),
    @p_rfr_list_id,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-MANAGER'),
    @p_rfr_list_id,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE'),
    @p_rfr_list_id,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'FINANCE'),
    @p_rfr_list_id,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVLA-OPERATIVE'),
    @p_rfr_list_id,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2'),
    @p_rfr_list_id,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'GVTS-TESTER'),
    @p_rfr_list_id,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVLA-MANAGER'),
    @p_rfr_list_id,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  );
  