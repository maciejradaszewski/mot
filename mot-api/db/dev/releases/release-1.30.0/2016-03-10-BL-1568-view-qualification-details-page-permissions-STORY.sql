SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');

INSERT INTO
  `permission` (`name`, `code`, `is_restricted`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
VALUES
  ('qualification details for another user', 'VIEW-QUA-DETAILS-OF-ANOTHER-USER', 0, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6)),
  ('qualification details for the same site user', 'VIEW-QUA-DET-OF-THE-SAME-SITE-USER', 0, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6));

SET @view_qua_det_another_user_permission = (SELECT `id` FROM `permission` WHERE `code` = 'VIEW-QUA-DETAILS-OF-ANOTHER-USER');
SET @view_qua_det_the_same_site_user_permission = (SELECT `id` FROM `permission` WHERE `code` = 'VIEW-QUA-DET-OF-THE-SAME-SITE-USER');

SET @scheme_mgr = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT');
SET @scheme_user = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');
SET @ve = (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER');
SET @ao1 = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1');
SET @ao2 = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2');
SET @csco = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE');
SET @site_manager = (SELECT `id` FROM `role` WHERE `code` = 'SITE-MANAGER');
SET @aedm = (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DESIGNATED-MANAGER');
SET @aed = (SELECT `id` FROM `role` WHERE `code` = 'AUTHORISED-EXAMINER-DELEGATE');

INSERT INTO `role_permission_map`
(`role_id`, `permission_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
VALUES
(@scheme_mgr, @view_qua_det_another_user_permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
(@scheme_user, @view_qua_det_another_user_permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
(@ve, @view_qua_det_another_user_permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
(@ao1, @view_qua_det_another_user_permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
(@ao2, @view_qua_det_another_user_permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
(@csco, @view_qua_det_another_user_permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
(@site_manager, @view_qua_det_the_same_site_user_permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
(@aedm, @view_qua_det_the_same_site_user_permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
(@aed, @view_qua_det_the_same_site_user_permission, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6))
;
