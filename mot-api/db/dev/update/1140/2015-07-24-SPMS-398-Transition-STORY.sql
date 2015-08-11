SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data' LIMIT 1);

INSERT INTO
  `payment_type` (`type_name`, `active`, `is_adjustable`,  `display_order`, `created_by`)
VALUES
  ('Transition', 1, 1, 6, @created_by);

INSERT INTO
  permission (`code`, `name`, `created_by`)
VALUES
  ('SLOTS-TRANSITION', '', @created_by);

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    (SELECT `id` FROM role WHERE `code` = 'CRON'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-TRANSITION'),
    @created_by
);