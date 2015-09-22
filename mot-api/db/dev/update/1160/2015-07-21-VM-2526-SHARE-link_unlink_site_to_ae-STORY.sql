SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

INSERT INTO `permission` (`name`, `code`, `created_by`, `last_updated_by`)
VALUES
    ('Associate a Site and an Organisation (AE) together', 'AE-SITE-LINK', @created_by, @created_by),
    ('Remove association between Organisation and Site', 'AE-SITE-UNLINK', @created_by, @created_by);


--  set permissions for AE  --
INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`, `last_updated_by`)
SELECT
    r.id as role_id,
    p.id as permission_id,
    @created_by, @created_by
FROM (
         SELECT _latin1 'DVSA-AREA-OFFICE-1' AS `code`
     ) as roles

    INNER JOIN role AS r ON
         r.code = roles.code

    CROSS JOIN permission AS p

WHERE
    p.code IN ('AE-SITE-LINK', 'AE-SITE-UNLINK');
