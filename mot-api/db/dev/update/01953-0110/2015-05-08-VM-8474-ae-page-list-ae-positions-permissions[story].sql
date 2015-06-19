SET @created_by = (SELECT `id` FROM `person` WHERE `username` = 'static data' );
SET @permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'LIST-AE-POSITIONS');

SET @t = (SELECT `id` FROM `role` WHERE `code` = 'TESTER');
SET @sa = (SELECT `id` FROM `role` WHERE `code` = 'SITE-ADMIN');
SET @csco = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE');
SET @schm = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT');
SET @schu = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');

INSERT INTO `role_permission_map`
    (`role_id`, `permission_id`, `created_by`)
    VALUES
    (@t, @permission_id, @created_by),
    (@sa, @permission_id, @created_by),
    (@csco, @permission_id, @created_by),
    (@schm, @permission_id, @created_by),
    (@schu, @permission_id, @created_by);
