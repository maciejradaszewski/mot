-- liquibase formatted sql

-- changeset peleodiase:20150616000000

INSERT INTO
  permission (`code`, `name`, `created_by`)
VALUES
  ('SLOTS-CANCEL-DIRECT-DEBIT', '', 2),
  ('SLOTS-MANAGE-DIRECT-DEBIT', '', 2);

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    (SELECT `id` FROM role WHERE `code` = 'AUTHORISED-EXAMINER-DESIGNATED-MANAGER'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-CANCEL-DIRECT-DEBIT'),
    2
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'AUTHORISED-EXAMINER-DELEGATE'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-CANCEL-DIRECT-DEBIT'),
    2
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'AUTHORISED-EXAMINER-DESIGNATED-MANAGER'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-MANAGE-DIRECT-DEBIT'),
    2
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'AUTHORISED-EXAMINER-DELEGATE'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-MANAGE-DIRECT-DEBIT'),
    2
  )
  ON DUPLICATE KEY UPDATE created_by = VALUES(`created_by`);
