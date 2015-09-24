SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data' LIMIT 1);
SET @code = 'RECENT-CERTIFICATE-PRINT';
INSERT INTO
  permission (`code`, `name`, `is_restricted`, `created_by`)
VALUES
  (@code, 'Print recent certificate', 0, @created_by);


SET @permissionId = (SELECT `id` FROM `permission` WHERE `code` = @code);

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    (SELECT `id` FROM role WHERE `code` = 'TESTER-ACTIVE'),
    @permissionId,
    @created_by
  )