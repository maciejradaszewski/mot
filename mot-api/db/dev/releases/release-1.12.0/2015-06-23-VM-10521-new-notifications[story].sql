SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

INSERT INTO `notification_template` (`subject`, `content`, `created_by`)
  VALUES ('Passed Group ${vehicle_group} demonstration test', 'You passed your demonstration test. You are now qualified to test Group ${vehicle_group} vehicles.', @created_by);