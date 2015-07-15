-- VM-10292
-- introducing new permission if user is able to search vehicles, now this is checked using mix of
-- CERTIFICATE-PRINT and CERTIFICATE-READ which doesn't fit desired list of roles that should have access to the feature

SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

INSERT INTO `permission` (`code`, `name`, `created_by`)
VALUES
  ('CERTIFICATE-SEARCH', 'Search for certificates', @created_by)
;

SET @certificateSearch  = (SELECT `id` FROM `permission` WHERE `code` = 'CERTIFICATE-SEARCH');

SET @tester             = (SELECT `id` FROM `role` WHERE `code` = 'TESTER');
SET @areaOffice1        = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1');
SET @areaOffice2        = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2');
SET @customerOperative  = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE');
SET @dvlaOperative      = (SELECT `id` FROM `role` WHERE `code` = 'DVLA-OPERATIVE');

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
-- internal role
  (@tester,             @certificateSearch, @created_by),
  (@areaOffice1,        @certificateSearch, @created_by),
  (@areaOffice2,        @certificateSearch, @created_by),
  (@customerOperative,  @certificateSearch, @created_by),
  (@dvlaOperative,      @certificateSearch, @created_by)
;