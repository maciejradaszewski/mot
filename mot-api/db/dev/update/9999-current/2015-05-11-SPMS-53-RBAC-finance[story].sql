-- liquibase formatted sql

-- changeset peleodiase:20150507120214

INSERT INTO
  permission (`code`, `name`, `created_by`)
VALUES
  ('SLOTS-REPORTS-GENERATE', '', 2),
  ('SLOTS-REPORTS-DOWNLOAD', '', 2)
  ;

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    (SELECT `id` FROM role WHERE `code` = 'FINANCE'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-REPORTS-GENERATE'),
    2
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'FINANCE'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-REPORTS-DOWNLOAD'),
    2
  )
   ON DUPLICATE KEY UPDATE created_by = VALUES(`created_by`)
;