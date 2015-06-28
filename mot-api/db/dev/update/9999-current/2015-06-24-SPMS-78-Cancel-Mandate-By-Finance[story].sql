-- liquibase formatted sql

-- changeset peleodiase:20150624000000

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    (SELECT `id` FROM role WHERE `code` = 'FINANCE'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-CANCEL-DIRECT-DEBIT'),
    1
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'FINANCE'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-MANAGE-DIRECT-DEBIT'),
    1
  )
   ON DUPLICATE KEY UPDATE created_by = VALUES(`created_by`);