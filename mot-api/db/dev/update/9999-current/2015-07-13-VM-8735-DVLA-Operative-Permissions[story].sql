-- Add permission "CERTIFICATE-REPLACEMENT-FULL" for the DVLA Operative User

SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVLA-OPERATIVE'),
    (SELECT `id` FROM `permission` WHERE `code` = 'CERTIFICATE-REPLACEMENT-FULL'),
    @created_by
  );

-- Remove permission "VEHICLE-TESTING-STATION-READ" for the DVLA Operative User
DELETE FROM `role_permission_map` WHERE
  `role_id` = (SELECT `id` FROM role WHERE `code` = 'DVLA-OPERATIVE')
  AND
  `permission_id` = (SELECT `id` FROM `permission` WHERE `code` = 'VEHICLE-TESTING-STATION-READ')
;