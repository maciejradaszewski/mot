-- liquibase formatted sql

-- changeset peleodiase:20150514000000

INSERT INTO
  `test_slot_transaction_amendment_type` (`code`,`title`, `created_by`)
VALUES
  ('T702', 'Refund', 2);

ALTER TABLE `test_slot_transaction` ADD COLUMN `real_slots` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Number of slots after the initial value of slots have been adjusted or refunded' AFTER `slots`;


INSERT INTO
  permission (`code`, `name`, `created_by`)
VALUES
  ('SLOTS-REFUND', '', 2);

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    (SELECT `id`
     FROM role
     WHERE `code` = 'FINANCE'),
    (SELECT `id`
     FROM permission
     WHERE `code` = 'SLOTS-REFUND'),
    2
  )
  ON DUPLICATE KEY UPDATE created_by = VALUES(`created_by`);