-- liquibase formatted sql

-- changeset peleodiase:20150416125800

INSERT INTO
  permission (`code`, `name`, `created_by`)
VALUES
  ('SLOTS-TRANSACTION-READ-FULL', '', 2),
  ('SLOTS-PURCHASE-INSTANT-SETTLEMENT', '', 2);


INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    (SELECT `id` FROM role WHERE `code` = 'FINANCE'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-INCREMENT-BALANCE'),
    2
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'FINANCE'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-PURCHASE'),
    2
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'FINANCE'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-VIEW'),
    2
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'FINANCE'),
    (SELECT `id` FROM permission WHERE `code` = 'AUTHORISED-EXAMINER-LIST'),
    2
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'FINANCE'),
    (SELECT `id` FROM permission WHERE `code` = 'AUTHORISED-EXAMINER-READ'),
    2
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'FINANCE'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-PURCHASE-INSTANT-SETTLEMENT'),
    2
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'FINANCE'),
    (SELECT `id` FROM permission WHERE `code` = 'AE-SLOTS-USAGE-READ'),
    2
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'FINANCE'),
    (SELECT `id` FROM permission WHERE `code` = 'AUTHORISED-EXAMINER-READ-FULL'),
    2
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'FINANCE'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-TRANSACTION-READ-FULL'),
    2
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'AUTHORISED-EXAMINER-DELEGATE'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-TRANSACTION-READ-FULL'),
    2
  )
  ON DUPLICATE KEY UPDATE `created_by` = VALUES(`created_by`)
;