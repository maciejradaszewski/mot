SET @created_by = (SELECT `id` FROM `person` WHERE `username` = 'static data' );
SET @permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'LIST-AEP-AT-AUTHORISED-EXAMINER');

SET @ve = (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER');
SET @ao = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1');
SET @csco = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE');
SET @schu = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`)
    VALUES
    (@ve, @permission_id, @created_by),
    (@ao, @permission_id, @created_by),
    (@csco, @permission_id, @created_by),
    (@schu, @permission_id, @created_by);