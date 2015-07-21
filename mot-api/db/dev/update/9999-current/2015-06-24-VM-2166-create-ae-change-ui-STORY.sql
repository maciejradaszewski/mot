
DELETE FROM role_permission_map
WHERE
    role_id IN (
        SELECT
            id
        FROM
            role
        WHERE
            code IN (
                'DVSA-SCHEME-MANAGEMENT',
                'DVSA-SCHEME-USER'
            )
    )
    AND permission_id = (
        SELECT
            id
        FROM
            permission
        WHERE
            code = 'DISPLAY-DVSA-ADMIN-BOX'
    );

SET @user = (SELECT id FROM person WHERE username = 'static data');

--  Simon (PO) said need to change it (same to MOT1).
UPDATE company_type SET name='Company', last_updated_by=@user, version=version+1 WHERE code = 'RC';       --  before Registered Company
UPDATE company_type SET name='Public Body', last_updated_by=@user, version=version+1 WHERE code = 'PA';   --  before Public Authority