-- New permissions for the DVLA Operative User

SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' );


INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    (SELECT `id` FROM role WHERE `code` = 'DVLA-OPERATIVE'),
    (SELECT `id` FROM permission WHERE `code` = 'CERTIFICATE-REPLACEMENT'),
    @created_by
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'DVLA-OPERATIVE'),
    (SELECT `id` FROM permission WHERE `code` = 'CERTIFICATE-REPLACEMENT-SPECIAL-FIELDS'),
    @created_by
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'DVLA-OPERATIVE'),
    (SELECT `id` FROM permission WHERE `code` = 'MOT-TEST-WITHOUT-OTP'),
    @created_by
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'DVLA-OPERATIVE'),
    (SELECT `id` FROM permission WHERE `code` = 'FULL-VEHICLE-MOT-TEST-HISTORY-VIEW'),
    @created_by
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'DVLA-OPERATIVE'),
    (SELECT `id` FROM permission WHERE `code` = 'MOT-TEST-LIST'),
    @created_by
  ),
  (
    (SELECT `id` FROM role WHERE `code` = 'DVLA-OPERATIVE'),
    (SELECT `id` FROM permission WHERE `code` = 'DVSA-SITE-SEARCH'),
    @created_by
  )
  ;