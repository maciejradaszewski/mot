SET @created_by = (SELECT `id` FROM `person` WHERE `username` = 'static data' );
SET @permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'USER-SEARCH');

SET @schememgt_id  = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT');
SET @schemeuser_id = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');
SET @csm_id        = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-MANAGER');
SET @dvlamgr_id    = (SELECT `id` FROM `role` WHERE `code` = 'DVLA-MANAGER');

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`) VALUES
  (@schememgt_id,  @permission_id, @created_by),
  (@schemeuser_id, @permission_id, @created_by),
  (@csm_id,        @permission_id, @created_by),
  (@dvlamgr_id,    @permission_id, @created_by);