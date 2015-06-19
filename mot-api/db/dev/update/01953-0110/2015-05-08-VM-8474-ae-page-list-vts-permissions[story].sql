SET @created_by = (SELECT `id` FROM `person` WHERE `username` = 'static data' );
SET @permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'VEHICLE-TESTING-STATION-LIST-AT-AE');

SET @ae = (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER');

DELETE FROM `role_permission_map` WHERE `role_id` = @ae AND `permission_id` = @permission_id;

SET @t = (SELECT `id` FROM `role` WHERE `code` = 'TESTER');
SET @sa = (SELECT `id` FROM `role` WHERE `code` = 'SITE-ADMIN');
SET @sm = (SELECT `id` FROM `role` WHERE `code` = 'SITE-MANAGER');
SET @schu = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');

INSERT INTO `role_permission_map`
    (`role_id`, `permission_id`, `created_by`)
    VALUES
    (@t, @permission_id, @created_by),
    (@sa, @permission_id, @created_by),
    (@sm, @permission_id, @created_by),
    (@schu, @permission_id, @created_by);
