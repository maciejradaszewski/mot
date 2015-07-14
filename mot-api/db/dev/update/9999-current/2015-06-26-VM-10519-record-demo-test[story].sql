-- [story] VM-10519
-- VE and AO1 can record a demo performed by a tester

SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

INSERT INTO `permission` (`name`, `code`, `is_restricted`, `created_by`)
  VALUES ('Assess demo test', 'ASSESS-DEMO-TEST', 1, @created_by);
