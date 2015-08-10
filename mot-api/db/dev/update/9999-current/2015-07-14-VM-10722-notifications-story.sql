--  id is hard-coded due to limitation in the code, discussed with ana, separate jira will be raised
SET @static_user = (SELECT `id` FROM `person` WHERE `username` = 'static data' || `user_reference` = 'Static Data');

INSERT INTO `notification_template` (`id`, `content`, `subject`, `created_by`, `last_updated_by`, `last_updated_on`)
VALUES
	(16, 'You have been assigned the role of ${role}. You may need to sign in again for this to take effect.', 'Role assigned: ${role}', @static_user, @static_user, CURRENT_TIMESTAMP(6)),
	(17, 'Your role of ${role} has been removed. You may need to sign in for this to take effect.', 'Role removed: ${role}', @static_user, @static_user, CURRENT_TIMESTAMP(6));
