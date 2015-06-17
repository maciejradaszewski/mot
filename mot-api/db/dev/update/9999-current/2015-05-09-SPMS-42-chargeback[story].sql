-- liquibase formatted sql

-- changeset peleodiase:20150430125840

ALTER TABLE `test_slot_transaction_amendment` ADD COLUMN `test_slot_transaction_id` INT(10) UNSIGNED DEFAULT NULL
AFTER `organisation_id`;

ALTER TABLE `test_slot_transaction_amendment` ADD CONSTRAINT `fk_test_slot_transaction_amendment_test_slot_transaction_id` FOREIGN KEY (`test_slot_transaction_id`) REFERENCES `test_slot_transaction` (`id`);

INSERT INTO
  `test_slot_transaction_amendment_type` (`code`,`title`, `created_by`)
VALUES
  ('T701', 'Chargeback', 2);

INSERT INTO
  permission (`code`, `name`, `created_by`)
VALUES
  ('SLOTS-CHARGEBACK', '', 2);

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    (SELECT `id`
     FROM role
     WHERE `code` = 'FINANCE'),
    (SELECT `id`
     FROM permission
     WHERE `code` = 'SLOTS-CHARGEBACK'),
    2
  )
    ON DUPLICATE KEY UPDATE `created_by` = VALUES(`created_by`)
  ;