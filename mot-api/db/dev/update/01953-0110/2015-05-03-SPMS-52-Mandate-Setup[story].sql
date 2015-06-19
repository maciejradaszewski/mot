-- liquibase formatted sql

-- changeset peleodiase:20150417125800

INSERT INTO `configuration` (`key`, `value`, `created_by`)
VALUES
  ('directDebitCollectionDates', '5,20', 2);

INSERT INTO `direct_debit_status` (`name`, `code`, `created_by`)
VALUES
  ('SUBMITTED', 'SBT', 2),
  ('FAILED', 'F', 2);

INSERT INTO
  permission (`code`, `name`, `created_by`)
VALUES
  ('SLOTS-PAYMENT-DIRECT-DEBIT', '', 2);

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    (SELECT `id`
     FROM role
     WHERE `code` = 'AUTHORISED-EXAMINER-DESIGNATED-MANAGER'),
    (SELECT `id`
     FROM permission
     WHERE `code` = 'SLOTS-PAYMENT-DIRECT-DEBIT'),
    2
  ),
  (
    (SELECT `id`
     FROM role
     WHERE `code` = 'AUTHORISED-EXAMINER-DELEGATE'),
    (SELECT `id`
     FROM permission
     WHERE `code` = 'SLOTS-PAYMENT-DIRECT-DEBIT'),
    2
  )
    ON DUPLICATE KEY UPDATE `created_by` = VALUES(`created_by`)
  ;

ALTER TABLE `direct_debit` CHANGE `mandate_id` `mandate_reference` VARCHAR(50) DEFAULT NULL
COMMENT 'Unique reference to direct debit mandate created in CPMS';
ALTER TABLE `direct_debit` ADD UNIQUE INDEX `ux_direct_debit_mandate_reference_id` (`mandate_reference`, `id`);
ALTER TABLE `direct_debit` ADD COLUMN `is_active` TINYINT(1) DEFAULT 0
AFTER `batch_number`;
