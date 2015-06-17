INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    (SELECT `id` FROM role WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE'),
    (SELECT `id` FROM permission WHERE `code` = 'CERTIFICATE-PRINT'),
    2
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'DVLA-OPERATIVE'),
    (SELECT `id` FROM permission WHERE `code` = 'CERTIFICATE-PRINT'),
    2
  );
