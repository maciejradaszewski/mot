SET @static_user = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

INSERT INTO `permission` (`name`, `code`, created_by, `last_updated_by`, `last_updated_on`) VALUES
('Display username', 'USERNAME-VIEW', @static_user, @static_user, CURRENT_TIMESTAMP(6)),
('Display username on vts page', 'VTS-USERNAME-VIEW', @static_user, @static_user, CURRENT_TIMESTAMP(6)),
('Display username on ae page', 'AE-USERNAME-VIEW', @static_user, @static_user, CURRENT_TIMESTAMP(6));

SET @system_permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'USERNAME-VIEW');
SET @vts_permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'VTS-USERNAME-VIEW');
SET @ae_permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'AE-USERNAME-VIEW');
SET @ao_role_id = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1');
SET @ve_role_id = (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER');
SET @csco_role_id = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE');

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`, `last_updated_by`, `last_updated_on`) VALUES
(@ao_role_id, @system_permission_id, @static_user, @static_user, CURRENT_TIMESTAMP(6)),
(@ao_role_id, @vts_permission_id, @static_user, @static_user, CURRENT_TIMESTAMP(6)),
(@ao_role_id, @ae_permission_id, @static_user, @static_user, CURRENT_TIMESTAMP(6)),
(@ve_role_id, @system_permission_id, @static_user, @static_user, CURRENT_TIMESTAMP(6)),
(@ve_role_id, @vts_permission_id, @static_user, @static_user, CURRENT_TIMESTAMP(6)),
(@ve_role_id, @ae_permission_id, @static_user, @static_user, CURRENT_TIMESTAMP(6)),
(@csco_role_id, @system_permission_id, @static_user, @static_user, CURRENT_TIMESTAMP(6)),
(@csco_role_id, @vts_permission_id, @static_user, @static_user, CURRENT_TIMESTAMP(6)),
(@csco_role_id, @ae_permission_id, @static_user, @static_user, CURRENT_TIMESTAMP(6));
