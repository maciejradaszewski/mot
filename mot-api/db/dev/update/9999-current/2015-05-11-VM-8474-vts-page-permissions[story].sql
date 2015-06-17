-- VM-8474
-- permissions for viewing vts page


SET @aed                = (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DELEGATE');
SET @aedm               = (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DESIGNATED-MANAGER');
SET @siteManager        = (SELECT `id` FROM `role` WHERE `code` = 'SITE-MANAGER');
SET @siteAdmin          = (SELECT `id` FROM `role` WHERE `code` = 'SITE-ADMIN');
SET @tester             = (SELECT `id` FROM `role` WHERE `code` = 'TESTER');

SET @ve                 = (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER');
SET @schemeUser         = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');
SET @schemeManagement   = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT');
SET @areaOffice1        = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1');
SET @areaOffice2        = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2');
SET @customerOperative  = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE');
SET @dvlaOperative      = (SELECT `id` FROM `role` WHERE `code` = 'DVLA-OPERATIVE');


########################################################################################################################
--  VTS-UPDATE-NAME
########################################################################################################################

SET @permission = (SELECT `id` FROM `permission` WHERE `code` = 'VTS-UPDATE-NAME');
DELETE `map` FROM `role_permission_map` `map` WHERE `map`.`permission_id` = @permission;


########################################################################################################################
--  VTS-UPDATE-BUSINESS-DETAILS
########################################################################################################################

SET @permission = (SELECT `id` FROM `permission` WHERE `code` = 'VTS-UPDATE-BUSINESS-DETAILS');
DELETE `map` FROM `role_permission_map` `map` WHERE `map`.`permission_id` = @permission;

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
-- internal roles
   (@ve,                 @permission, 2),
   (@schemeUser,         @permission, 2),
   (@schemeManagement,   @permission, 2),
   (@areaOffice1,        @permission, 2),
   (@areaOffice2,        @permission, 2),
-- trade roles
   (@aed,                @permission, 2),
   (@aedm,               @permission, 2),
   (@siteManager,        @permission, 2)
;


########################################################################################################################
--  VTS-UPDATE-CORRESPONDENCE-DETAILS
########################################################################################################################

SET @permission = (SELECT `id` FROM `permission` WHERE `code` = 'VTS-UPDATE-CORRESPONDENCE-DETAILS');
DELETE `map` FROM `role_permission_map` `map` WHERE `map`.`permission_id` = @permission;

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
-- internal roles
   (@ve,                 @permission, 2),
   (@schemeUser,         @permission, 2),
   (@schemeManagement,   @permission, 2),
   (@areaOffice1,        @permission, 2),
   (@areaOffice2,        @permission, 2),
-- trade roles
   (@aed,                @permission, 2),
   (@aedm,               @permission, 2),
   (@siteManager,        @permission, 2)
;


########################################################################################################################
--  UPDATE-TESTING-SCHEDULE
########################################################################################################################

INSERT INTO `permission` (`code`, `name`, `created_by`)
VALUES ('TESTING-SCHEDULE-UPDATE' , 'Updating VTS opening hours', 2);

SET @permission = (SELECT `id` FROM `permission` WHERE `code` = 'TESTING-SCHEDULE-UPDATE');
DELETE `map` FROM `role_permission_map` `map` WHERE `map`.`permission_id` = @permission;

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
-- internal roles
  (@ve,                 @permission, 2),
  (@areaOffice1,        @permission, 2),
  (@areaOffice2,        @permission, 2),
  -- trade roles
  (@aed,                @permission, 2),
  (@aedm,               @permission, 2),
  (@siteManager,        @permission, 2),
  (@siteAdmin,          @permission, 2)
;

########################################################################################################################
--  EVENT-READ
########################################################################################################################

SET @permission = (SELECT `id` FROM `permission` WHERE `code` = 'EVENT-READ');
DELETE `map` FROM `role_permission_map` `map` WHERE `map`.`permission_id` = @permission;

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
-- internal roles
  (@ve,                 @permission, 2),
  (@schemeUser,         @permission, 2),
  (@schemeManagement,   @permission, 2),
  (@areaOffice1,        @permission, 2),
  (@areaOffice2,        @permission, 2),
  (@customerOperative,  @permission, 2)
;

########################################################################################################################
--  LIST-EVENT-HISTORY
########################################################################################################################

SET @permission = (SELECT `id` FROM `permission` WHERE `code` = 'LIST-EVENT-HISTORY');
DELETE `map` FROM `role_permission_map` `map` WHERE `map`.`permission_id` = @permission;

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
-- internal roles
  (@ve,                 @permission, 2),
  (@schemeUser,         @permission, 2),
  (@schemeManagement,   @permission, 2),
  (@areaOffice1,        @permission, 2),
  (@areaOffice2,        @permission, 2),
  (@customerOperative,  @permission, 2)
;

########################################################################################################################
--  VIEW-TESTS-IN-PROGRESS-AT-VTS
########################################################################################################################

SET @permission = (SELECT `id` FROM `permission` WHERE `code` = 'VIEW-TESTS-IN-PROGRESS-AT-VTS');
DELETE `map` FROM `role_permission_map` `map` WHERE `map`.`permission_id` = @permission;

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
-- internal roles
  (@ve,                 @permission, 2),
  (@schemeUser,         @permission, 2),
  (@schemeManagement,   @permission, 2),
  (@areaOffice1,        @permission, 2),
  (@areaOffice2,        @permission, 2),
  (@customerOperative,  @permission, 2),

-- trade roles
  (@aed,                @permission, 2),
  (@aedm,               @permission, 2),
  (@siteManager,        @permission, 2),
  (@siteAdmin,          @permission, 2),
  (@tester,             @permission, 2)
;

########################################################################################################################
--  DEFAULT-BRAKE-TESTS-CHANGE
########################################################################################################################

SET @permission = (SELECT `id` FROM `permission` WHERE `code` = 'DEFAULT-BRAKE-TESTS-CHANGE');
DELETE `map` FROM `role_permission_map` `map` WHERE `map`.`permission_id` = @permission;

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
-- internal roles
  (@ve,                 @permission, 2),
  (@areaOffice1,        @permission, 2),
  (@areaOffice2,        @permission, 2),

  -- trade roles
  (@aed,                @permission, 2),
  (@aedm,               @permission, 2),
  (@siteManager,        @permission, 2)
;

########################################################################################################################
--  MOT-TEST-SHORT-SUMMARY-READ
########################################################################################################################
SET @permission = (SELECT `id` FROM `permission` WHERE `code` = 'MOT-TEST-SHORT-SUMMARY-READ');
DELETE `map` FROM `role_permission_map` `map` WHERE `map`.`permission_id` = @permission;
DELETE FROM `permission` WHERE `code` = 'MOT-TEST-SHORT-SUMMARY-READ';

########################################################################################################################
--  VEHICLE-TESTING-STATION-READ
########################################################################################################################

SET @permission = (SELECT `id` FROM `permission` WHERE `code` = 'VEHICLE-TESTING-STATION-READ');
DELETE `map` FROM `role_permission_map` `map` WHERE `map`.`permission_id` = @permission;

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
-- internal roles
  (@ve,                 @permission, 2),
  (@schemeUser,         @permission, 2),
  (@schemeManagement,   @permission, 2),
  (@areaOffice1,        @permission, 2),
  (@areaOffice2,        @permission, 2),
  (@customerOperative,  @permission, 2),
  (@dvlaOperative,      @permission, 2),

  -- trade roles
  (@aed,                @permission, 2),
  (@aedm,               @permission, 2),
  (@siteManager,        @permission, 2),
  (@siteAdmin,          @permission, 2),
  (@tester,             @permission, 2)
;
