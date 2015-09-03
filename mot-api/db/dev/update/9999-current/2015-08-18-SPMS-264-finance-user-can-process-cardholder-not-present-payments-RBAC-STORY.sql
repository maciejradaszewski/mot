SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data' LIMIT 1);

INSERT INTO
  permission (`code`, `name`, `is_restricted`, `created_by`)
VALUES
  ('SLOTS-PURCHASE-CARD-NOT-PRESENT', '', 1, @created_by); -- the flag `is_restricted` need to be set the same as SLOTS-PURCHASE

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    (SELECT `id` FROM role WHERE `code` = 'FINANCE'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-PURCHASE-CARD-NOT-PRESENT'),
    @created_by
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'FINANCE'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-PURCHASE-CARD'),
    @created_by
  );
