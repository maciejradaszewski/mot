SET @created_by = (SELECT `id` FROM `person` WHERE `username` = 'static data');

INSERT INTO `notification_template` (`content`, `subject`, `created_by`)
VALUES
	('You have been assigned the role of ${role}. You may need to sign in again for this to take effect.', 'Role assigned: ${role}', @created_by),
	('Your role of ${role} has been removed. You may need to sign in for this to take effect.', 'Role removed: ${role}', @created_by);
