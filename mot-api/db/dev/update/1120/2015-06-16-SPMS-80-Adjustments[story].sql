-- liquibase formatted sql

-- changeset peleodiase:2015061600010

INSERT INTO
  test_slot_transaction_status (`code`, `name`, `created_by`)
VALUES
  ('IV', 'INVALID', 2);


INSERT INTO
  permission (`code`, `name`, `created_by`)
VALUES
  ('SLOTS-TXN-ADJUSTMENT', '', 2);

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    (SELECT `id` FROM role WHERE `code` = 'FINANCE'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-TXN-ADJUSTMENT'),
    2
  )
  ON DUPLICATE KEY UPDATE created_by = VALUES(`created_by`);
