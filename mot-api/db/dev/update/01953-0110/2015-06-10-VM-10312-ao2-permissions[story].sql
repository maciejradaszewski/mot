SET @created_by = (SELECT `id` FROM `person` WHERE `username` = 'static data' );
SET @ao2_role_id = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2');

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    @ao2_role_id,
    (SELECT `id` FROM `permission` WHERE `code` = 'USER-SEARCH'),
    @created_by
  ),
  (
    @ao2_role_id,
    (SELECT `id` FROM `permission` WHERE `code` = 'USER-SEARCH-EXTENDED'),
    @created_by
  ),
  (
    @ao2_role_id,
    (SELECT `id` FROM `permission` WHERE `code` = 'VIEW-OTHER-USER-PROFILE-DVSA-USER'),
    @created_by
  ),
  (
    @ao2_role_id,
    (SELECT `id` FROM `permission` WHERE `code` = 'VEHICLE-MOT-TEST-HISTORY-READ'),
    @created_by
  ),
  (
    @ao2_role_id,
    (SELECT `id` FROM `permission` WHERE `code` = 'LIST-AEP-AT-AUTHORISED-EXAMINER'),
    @created_by
  ),
  (
    @ao2_role_id,
    (SELECT `id` FROM `permission` WHERE `code` = 'TESTER-READ'),
    @created_by
  ),
  (
    @ao2_role_id,
    (SELECT `id` FROM `permission` WHERE `code` = 'TESTER-READ-OTHERS'),
    @created_by
  )
;

DELETE FROM role_permission_map WHERE
role_id = @ao2_role_id
  AND
permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'AUTHORISED-EXAMINER-CREATE')
;
DELETE FROM role_permission_map WHERE
role_id = @ao2_role_id
  AND
permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'AUTHORISED-EXAMINER-UPDATE')
;
DELETE FROM role_permission_map WHERE
role_id = @ao2_role_id
  AND
permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'VEHICLE-TESTING-STATION-CREATE')
;
DELETE FROM role_permission_map WHERE
role_id = @ao2_role_id
  AND
permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'VEHICLE-TESTING-STATION-UPDATE')
;