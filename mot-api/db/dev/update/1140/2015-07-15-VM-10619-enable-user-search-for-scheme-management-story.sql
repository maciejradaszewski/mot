SET @static_user = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data' );

SET @permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'USER-SEARCH');

SET @schememgt_id  = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT');
SET @schemeuser_id = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');
SET @csm_id        = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-MANAGER');
SET @dvlamgr_id    = (SELECT `id` FROM `role` WHERE `code` = 'DVLA-MANAGER');

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`, `last_updated_by`, `last_updated_on`) VALUES
  (@schememgt_id,  @permission_id, @static_user, @static_user, CURRENT_TIMESTAMP(6)),
  (@schemeuser_id, @permission_id, @static_user, @static_user, CURRENT_TIMESTAMP(6)),
  (@csm_id,        @permission_id, @static_user, @static_user, CURRENT_TIMESTAMP(6)),
  (@dvlamgr_id,    @permission_id, @static_user, @static_user, CURRENT_TIMESTAMP(6));