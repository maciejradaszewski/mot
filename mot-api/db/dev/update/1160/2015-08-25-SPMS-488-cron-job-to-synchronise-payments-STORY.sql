SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data' LIMIT 1);

INSERT INTO `configuration` (`key`, `value`, `created_by`)
VALUES
  ('paymentInProgressTimeBox', 1800, @created_by ), -- 30 minutes
  ('paymentInProgressTimeout', 10800, @created_by ); -- 3 hours