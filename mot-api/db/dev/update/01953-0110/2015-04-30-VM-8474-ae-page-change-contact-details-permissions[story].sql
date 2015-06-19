SET @created_by = (SELECT `id` FROM `person` WHERE `username` = 'static data' );
SET @permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'AUTHORISED-EXAMINER-UPDATE');

SET @ve = (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER');
SET @csco = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE');
SET @schm = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT');
SET @schu = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');
SET @fin = (SELECT `id` FROM `role` WHERE `code` = 'FINANCE');

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`)
    VALUES
    (@ve, @permission_id, @created_by),
    (@csco, @permission_id, @created_by),
    (@schm, @permission_id, @created_by),
    (@schu, @permission_id, @created_by),
    (@fin, @permission_id, @created_by);