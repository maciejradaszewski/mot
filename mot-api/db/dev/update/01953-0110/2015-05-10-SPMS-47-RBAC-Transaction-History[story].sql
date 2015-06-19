-- liquibase formatted sql

-- changeset peleodiase:20150426125800

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
    (
    (SELECT `id` FROM role WHERE `code` = 'AUTHORISED-EXAMINER-DESIGNATED-MANAGER'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-TRANSACTION-READ-FULL'),
    2
  )
  ON DUPLICATE KEY UPDATE `created_by` = VALUES(`created_by`)
;