SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data' LIMIT 1);

INSERT INTO `configuration` (`key`, `value`, `created_by`)
VALUES  ('directDebitSetupTimeout', 2100, @created_by);