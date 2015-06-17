-- liquibase formatted sql

-- changeset peleodiase:20150512000000

INSERT INTO
  permission (`code`, `name`, `created_by`)
VALUES
  ('SLOTS-TRANSACTION-HISTORY-READ', '', 2)
     ON DUPLICATE KEY UPDATE created_by = VALUES(`created_by`)
  ;

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    (SELECT `id` FROM role WHERE `code` = 'FINANCE'),
    (SELECT `id` FROM permission WHERE `code` = 'VEHICLE-TESTING-STATION-READ'),
    2
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'FINANCE'),
    (SELECT `id` FROM permission WHERE `code` = 'SITE-SLOTS-USAGE-READ'),
    2
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'FINANCE'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-TRANSACTION-HISTORY-READ'),
   2
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'FINANCE'),
    (SELECT `id` FROM permission WHERE `code` = 'AE-SLOTS-USAGE-READ'),
    2
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'AUTHORISED-EXAMINER-DELEGATE'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-TRANSACTION-HISTORY-READ'),
   2
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'AUTHORISED-EXAMINER-DELEGATE'),
    (SELECT `id` FROM permission WHERE `code` = 'AE-SLOTS-USAGE-READ'),
    2
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'AUTHORISED-EXAMINER-DESIGNATED-MANAGER'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-TRANSACTION-HISTORY-READ'),
    2
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'AUTHORISED-EXAMINER-DESIGNATED-MANAGER'),
    (SELECT `id` FROM permission WHERE `code` = 'AE-SLOTS-USAGE-READ'),
    2
  )
     ON DUPLICATE KEY UPDATE created_by = VALUES(`created_by`)
;