SET @created_by = (SELECT `id` FROM `person` WHERE  `username` = 'static data');
SET @permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'AUTHORISED-EXAMINER-LIST');

SET @aed = (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DELEGATE');
DELETE FROM `role_permission_map` WHERE `role_id` = @aed AND `permission_id` = @permission_id;

SET @aedm = (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DESIGNATED-MANAGER');
DELETE FROM `role_permission_map` WHERE `role_id` = @aedm AND `permission_id` = @permission_id;

SET @schu = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');
SET @aer_permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'AUTHORISED-EXAMINER-READ');

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`)
    VALUES
    (@schu, @permission_id, @created_by),
    (@schu, @aer_permission_id, @created_by);
