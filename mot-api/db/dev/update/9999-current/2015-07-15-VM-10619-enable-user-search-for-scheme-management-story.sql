SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

SET @permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'USER-SEARCH');
SET @permission_id_2 = (SELECT `id` FROM `permission` WHERE `code` = 'VIEW-OTHER-USER-PROFILE-DVSA-USER');

SET @schememgt_id  = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT');
SET @schemeuser_id = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');
SET @csm_id        = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-MANAGER');
SET @dvlamgr_id    = (SELECT `id` FROM `role` WHERE `code` = 'DVLA-MANAGER');

-- Adding user search
INSERT INTO `role_permission_map`
  (`permission_id`, `created_by`, `last_updated_by`, `last_updated_on`, `role_id`)
VALUES
  (@permission_id, @created_by, @created_by, CURRENT_TIMESTAMP(6), @schememgt_id),
  (@permission_id, @created_by, @created_by, CURRENT_TIMESTAMP(6), @schemeuser_id),
  (@permission_id, @created_by, @created_by, CURRENT_TIMESTAMP(6), @csm_id),
  (@permission_id, @created_by, @created_by, CURRENT_TIMESTAMP(6), @dvlamgr_id);

-- Adding DVSA profile view to all DVSA users
INSERT INTO `role_permission_map`
  (`permission_id`, `created_by`, `last_updated_by`, `last_updated_on`, `role_id`)
VALUES
  (@permission_id_2, @created_by, @created_by, CURRENT_TIMESTAMP(6), @schememgt_id),
  (@permission_id_2, @created_by, @created_by, CURRENT_TIMESTAMP(6), @schemeuser_id),
  (@permission_id_2, @created_by, @created_by, CURRENT_TIMESTAMP(6), @dvlamgr_id);
