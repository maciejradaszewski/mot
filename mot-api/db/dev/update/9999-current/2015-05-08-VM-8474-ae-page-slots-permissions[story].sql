SET @created_by = (SELECT `id` FROM `person` WHERE `username` = 'static data' );
SET @permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'AE-SLOTS-USAGE-READ');

SET @schu = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');
SET @schm = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT');
SET @do = (SELECT `id` FROM `role` WHERE `code` = 'DVLA-OPERATIVE');
SET @fin = (SELECT `id` FROM `role` WHERE `code` = 'FINANCE');
SET @tester = (SELECT `id` FROM `role` WHERE `code` = 'TESTER');
SET @csco = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE');
SET @sa = (SELECT `id` FROM `role` WHERE `code` = 'SITE-ADMIN');
SET @sm = (SELECT `id` FROM `role` WHERE `code` = 'SITE-MANAGER');
SET @aedm = (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DESIGNATED-MANAGER');
SET @aed = (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DELEGATE');
SET @ve = (SELECT `id` FROM `role` WHERE `code` ='VEHICLE-EXAMINER');
SET @ao1 = (SELECT `id` FROM `role` WHERE `code` ='DVSA-AREA-OFFICE-1');

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`)
    VALUES

    (@schu, @permission_id, @created_by),
    (@schm, @permission_id, @created_by);

SET @permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'AE-TEST-LOG');

DELETE FROM `role_permission_map` WHERE `role_id` = @do AND `permission_id` = @permission_id;

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`)
    VALUES
    (@tester, @permission_id, @created_by),
    (@sa, @permission_id, @created_by),
    (@sm, @permission_id, @created_by),
    (@csco, @permission_id, @created_by),
    (@schu, @permission_id, @created_by),
    (@schm, @permission_id, @created_by),
    (@fin, @permission_id, @created_by);

SET @permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'MOT-TEST-LIST');

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`)
    VALUES
    (@sm, @permission_id, @created_by),
    (@sa, @permission_id, @created_by),
    (@csco, @permission_id, @created_by),
    (@schm, @permission_id, @created_by),
    (@schu, @permission_id, @created_by),
    (@fin, @permission_id, @created_by);

INSERT INTO `permission` (`code`, `name`, `created_by`) VALUES ('AE-SLOTS-BALANCE-READ', 'Reading slots balance', @created_by);

SET @permission_id = LAST_INSERT_ID();

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`)
    VALUES
    (@tester, @permission_id, @created_by),
    (@sm, @permission_id, @created_by),
    (@sa, @permission_id, @created_by),
    (@aed, @permission_id, @created_by),
    (@aedm, @permission_id, @created_by),
    (@ve, @permission_id, @created_by),
    (@ao1, @permission_id, @created_by),
    (@csco, @permission_id, @created_by),
    (@schm, @permission_id, @created_by),
    (@schu, @permission_id, @created_by),
    (@fin, @permission_id, @created_by);
