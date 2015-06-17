--
-- ALREADY IN PRODUCTION
--

SET @created_by = (SELECT `id` FROM `person` WHERE `username` = 'static data' );

INSERT INTO `role` (`name`, `code`, `created_by`) values ('GVTS Tester', 'GVTS-TESTER', @created_by);

INSERT INTO `person_system_role` (`name`, `full_name`, `short_name`, `role_id`, `created_by`) VALUES
  ('GVTS-TESTER', 'GVTS Tester', 'GVTST', (SELECT `id` FROM `role` WHERE `code` = 'GVTS-TESTER'), @created_by);