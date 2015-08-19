SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' || `user_reference` = 'Static Data');
DELETE FROM role_permission_map
WHERE
    role_id IN (
        SELECT
            id
        FROM
            role
        WHERE
            code IN (
                'AUTHORISED-EXAMINER',
                'DVSA-SCHEME-MANAGEMENT',
                'DVSA-SCHEME-USER',
                'CUSTOMER-SERVICE-CENTRE-OPERATIVE',
                'CUSTOMER-SERVICE-MANAGEMENT',
                'FINANCE',
                'TESTER',
                'SITE-ADMIN',
                'SITE-MANAGER'
            )
    )
    AND permission_id = (
        SELECT
            id
        FROM
            permission
        WHERE
            code = 'AE-TEST-LOG'
    );