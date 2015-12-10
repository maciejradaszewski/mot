SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');

INSERT INTO `permission`(`name`, `code`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
  ('User sees own Country in Contact details', 'VTS-VIEW-CONTACT-DETAILS-COUNTRY', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @scheme_manager = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT');
SET @scheme_user = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');
SET @cscm = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-MANAGER');
SET @csco = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE');
SET @AO1 = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1');
SET @AO2 = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2');
SET @VE = (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER');
SET @AEDM = (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DESIGNATED-MANAGER');
SET @AED = (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DELEGATE');
SET @SITE_MANAGER = (SELECT `id` FROM `role` WHERE `code` = 'SITE-MANAGER');
SET @SITE_ADMIN = (SELECT `id` FROM `role` WHERE `code` = 'SITE-ADMIN');

SET @EDIT_CONTACT_DETAILS = (SELECT `id` FROM `permission` WHERE `code` = 'VTS-UPDATE-BUSINESS-DETAILS');
SET @VIEW_COUNTRY_IN_CONTACT_DETAILS = (SELECT `id` FROM `permission` WHERE `code` = 'VTS-VIEW-CONTACT-DETAILS-COUNTRY');

DELETE FROM `role_permission_map` WHERE `permission_id`= @EDIT_CONTACT_DETAILS;

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by` ,`created_on`, `last_updated_by`, `last_updated_on`)
VALUES
  (@scheme_manager, @EDIT_CONTACT_DETAILS, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6)),
  (@scheme_user, @EDIT_CONTACT_DETAILS, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6)),
  (@AO1, @EDIT_CONTACT_DETAILS, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6)),
  (@AO2, @EDIT_CONTACT_DETAILS, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6)),
  (@VE, @EDIT_CONTACT_DETAILS, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6)),
  (@AEDM, @EDIT_CONTACT_DETAILS, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6)),
  (@AED, @EDIT_CONTACT_DETAILS, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6)),
  (@SITE_MANAGER, @EDIT_CONTACT_DETAILS, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6)),
  (@SITE_ADMIN, @EDIT_CONTACT_DETAILS, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6)),
  (@scheme_manager, @VIEW_COUNTRY_IN_CONTACT_DETAILS, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6)),
  (@scheme_user, @VIEW_COUNTRY_IN_CONTACT_DETAILS, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6)),
  (@AO1, @VIEW_COUNTRY_IN_CONTACT_DETAILS, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6)),
  (@AO2, @VIEW_COUNTRY_IN_CONTACT_DETAILS, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6)),
  (@VE, @VIEW_COUNTRY_IN_CONTACT_DETAILS, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6)),
  (@cscm, @VIEW_COUNTRY_IN_CONTACT_DETAILS, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6)),
  (@csco, @VIEW_COUNTRY_IN_CONTACT_DETAILS, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6));