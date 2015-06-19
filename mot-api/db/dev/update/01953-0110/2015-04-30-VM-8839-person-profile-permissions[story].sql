-- VM-8839
-- permissions for viewing person profile and personal details

INSERT INTO `permission` (`code`, `name`, `created_by`)
VALUES
  ('VTS-EMPLOYEE-PROFILE-READ', 'View profile of a VTS employee', 2),
  ('AE-EMPLOYEE-PROFILE-READ', 'View profile of a AE employee', 2)
;

SET @readAtVts          = (SELECT `id` FROM `permission` WHERE `code` = 'VTS-EMPLOYEE-PROFILE-READ');
SET @readAtAe           = (SELECT `id` FROM `permission` WHERE `code` = 'AE-EMPLOYEE-PROFILE-READ');

SET @aed                = (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DELEGATE');
SET @aedm               = (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DESIGNATED-MANAGER');
SET @siteManager        = (SELECT `id` FROM `role` WHERE `code` = 'SITE-MANAGER');
SET @ve                 = (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER');
SET @schemeUser         = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');
SET @schemeManagement   = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT');
SET @areaOffice1        = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1');
SET @areaOffice2        = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2');
SET @customerOperative  = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE');

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
-- internal roles
  (@ve,                 @readAtVts, 2),
  (@schemeUser,         @readAtVts, 2),
  (@schemeManagement,   @readAtVts, 2),
  (@areaOffice1,        @readAtVts, 2),
  (@areaOffice2,        @readAtVts, 2),
  (@customerOperative,  @readAtVts, 2),
-- trade roles
  (@aed,                @readAtVts, 2),
  (@aedm,               @readAtVts, 2),
  (@siteManager,        @readAtVts, 2)
;

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
-- internal roles
  (@ve,                 @readAtAe, 2),
  (@schemeUser,         @readAtAe, 2),
  (@schemeManagement,   @readAtAe, 2),
  (@areaOffice1,        @readAtAe, 2),
  (@areaOffice2,        @readAtAe, 2),
  (@customerOperative,  @readAtAe, 2),
-- trade roles
  (@aedm,               @readAtAe, 2)
;

SET @viewOtherProfile = (SELECT `id` FROM `permission` WHERE `code` = 'VIEW-OTHER-USER-PROFILE');
DELETE `map` FROM `role_permission_map` `map`
WHERE `map`.`permission_id` = @viewOtherProfile
AND   `map`.`role_id` IN (@siteManager, @aedm, @aed);

UPDATE `permission`
SET `code` = 'PERSON-BASIC-DATA-READ'
WHERE `code` = 'ADMIN-PERSON-READ';
