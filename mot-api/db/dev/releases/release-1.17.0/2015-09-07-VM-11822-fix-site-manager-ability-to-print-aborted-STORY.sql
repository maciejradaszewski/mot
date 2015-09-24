-- VM-11822

SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data' LIMIT 1);

SET @code = 'MOT-TEST-READ-ALL';
SET @permissionId = (SELECT `id` FROM `permission` WHERE `code` = @code);
INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`, `last_updated_by`, `last_updated_on`)
VALUES
  (
    (SELECT `id` FROM role WHERE `code` = 'SITE-MANAGER'),
    @permissionId,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  );


SET @code = 'CERTIFICATE-PRINT';
SET @permissionId = (SELECT `id` FROM `permission` WHERE `code` = @code);
INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`, `last_updated_by`, `last_updated_on`)
VALUES
  (
    (SELECT `id` FROM role WHERE `code` = 'SITE-MANAGER'),
    @permissionId,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'SITE-ADMIN'),
    @permissionId,
    @created_by,
    @created_by,
    CURRENT_TIMESTAMP (6)
  );