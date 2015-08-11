-- Permission matrix for which internal users can add roles to other users
SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR username = 'static data' );

-- Incorrect role.code as compare to person_system_role.name, checked with the business and -MANAGER acceptable
UPDATE `role` SET 
  `code`='CUSTOMER-SERVICE-MANAGER',
  `last_updated_by` = @app_user_id,
  `version` = (`version` + 1)
WHERE `code`='CUSTOMER-SERVICE-MANAGEMENT';

-- Permission codes
SET @perm_dvsa_sm      = 'MANAGE-ROLE-DVSA-SCHEME-MANAGEMENT';
SET @perm_dvsa_su      = 'MANAGE-ROLE-DVSA-SCHEME-USER';
SET @perm_csco         = 'MANAGE-ROLE-CUSTOMER-SERVICE-CENTRE-OPERATIVE';
SET @perm_csm          = 'MANAGE-ROLE-CUSTOMER-SERVICE-MANAGER';
SET @perm_dvla_mgr     = 'MANAGE-ROLE-DVLA-MANAGER';
SET @perm_dvla_op      = 'MANAGE-ROLE-DVLA-OPERATIVE';
SET @perm_ao1          = 'MANAGE-ROLE-DVSA-AREA-OFFICE-1';
SET @perm_ao2          = 'MANAGE-ROLE-DVSA-AREA-OFFICE-2';
SET @perm_finance      = 'MANAGE-ROLE-FINANCE';
SET @perm_ve           = 'MANAGE-ROLE-VEHICLE-EXAMINER';

-- Role codes
SET @role_dvsa_sm  = 'DVSA-SCHEME-MANAGEMENT';
SET @role_dvsa_su  = 'DVSA-SCHEME-USER';
SET @role_csco     = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE';
SET @role_csm      = 'CUSTOMER-SERVICE-MANAGER';
SET @role_dvla_mgr = 'DVLA-MANAGER';
SET @role_dvla_op  = 'DVLA-OPERATIVE';
SET @role_ao1      = 'DVSA-AREA-OFFICE-1';
SET @role_ao2      = 'DVSA-AREA-OFFICE-2';
SET @role_finance  = 'FINANCE';
SET @role_ve       = 'VEHICLE-EXAMINER';

-- Insert the new role into the DB
INSERT INTO `role` (`code`, `name`, `is_internal`, `created_by`, `last_updated_by`, `last_updated_on`) VALUES
  (@role_dvla_mgr, 'DVLA Manager', 1, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6));

-- Role id lookups
SET @role_dvsa_sm_id  = (SELECT `id` FROM `role` WHERE `code` = @role_dvsa_sm);
SET @role_dvsa_su_id  = (SELECT `id` FROM `role` WHERE `code` = @role_dvsa_su);
SET @role_csco_id     = (SELECT `id` FROM `role` WHERE `code` = @role_csco);
SET @role_csm_id      = (SELECT `id` FROM `role` WHERE `code` = @role_csm);
SET @role_dvla_mgr_id = (SELECT `id` FROM `role` WHERE `code` = @role_dvla_mgr);
SET @role_dvla_op_id  = (SELECT `id` FROM `role` WHERE `code` = @role_dvla_op);
SET @role_ao1_id      = (SELECT `id` FROM `role` WHERE `code` = @role_ao1);
SET @role_ao2_id      = (SELECT `id` FROM `role` WHERE `code` = @role_ao2);
SET @role_finance_id  = (SELECT `id` FROM `role` WHERE `code` = @role_finance);
SET @role_ve_id       = (SELECT `id` FROM `role` WHERE `code` = @role_ve);

-- Insert new role into person_system_role
INSERT INTO `person_system_role` (`name`, `full_name`, `short_name`, `role_id`, `created_by`, `last_updated_by`, `last_updated_on`) VALUES
  (@role_dvla_mgr, 'DVLA Manager', 'DM', @role_dvla_mgr_id, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6));

-- Copy the permissions from DVLA Operative
INSERT INTO role_permission_map (`role_id`, `permission_id`, `created_by`, `last_updated_by`, `last_updated_on`)
SELECT
	@role_dvla_mgr_id as role_id,
	`perm`.`id` AS permission_id,
	@app_user_id AS created_by,
	@app_user_id AS last_updated_by,
	CURRENT_TIMESTAMP(6) AS last_updated_on
FROM `permission` AS perm
JOIN `role_permission_map` AS rpm
	ON `rpm`.`permission_id` = `perm`.`id`
JOIN `role` as r
	ON `r`.`id` = `rpm`.`role_id`
WHERE `r`.`code` = @role_dvla_op;

-- Add the permission to LIST-EVENT-HISTORY to two roles
SET @list_permission = (SELECT `id` from `permission` WHERE `code` = 'LIST-EVENT-HISTORY');
INSERT INTO role_permission_map (`role_id`, `permission_id`, `created_by`, `last_updated_by`, `last_updated_on`) VALUES
  (@role_dvla_mgr_id, @list_permission, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@role_csm_id,      @list_permission, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6));

-- Insert all permissions
INSERT INTO `permission` (`code`, `name`, `is_restricted`, `created_by`, `last_updated_by`, `last_updated_on`) VALUES
  (@perm_dvsa_sm,      'Manage DVSA Scheme Manager role', 1, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_dvsa_su,      'Manage DVSA Scheme User role',    1, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_csco,         'Manage CSCO role',                1, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_csm,          'Manage CSM role',                 1, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_dvla_mgr,     'Manage DVLA Manager role',        1, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_dvla_op,      'Manage DVLA Operative role',      1, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_ao1,          'Manage Area Office User role',    1, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_ao2,          'Manage Area Office 2 User role',  1, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_finance,      'Manage Finance role',             1, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_ve,           'Manage VE role',                  1, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6));

-- Permission - lookup
SET @perm_manage_dvsa_sm_id  = (SELECT `id` FROM `permission` WHERE `code` = @perm_dvsa_sm);
SET @perm_manage_dvsa_su_id  = (SELECT `id` FROM `permission` WHERE `code` = @perm_dvsa_su);
SET @perm_manage_csco_id     = (SELECT `id` FROM `permission` WHERE `code` = @perm_csco);
SET @perm_manage_csm_id      = (SELECT `id` FROM `permission` WHERE `code` = @perm_csm);
SET @perm_manage_dvla_mgr_id = (SELECT `id` FROM `permission` WHERE `code` = @perm_dvla_mgr);
SET @perm_manage_dvla_op_id  = (SELECT `id` FROM `permission` WHERE `code` = @perm_dvla_op);
SET @perm_manage_ao1_id      = (SELECT `id` FROM `permission` WHERE `code` = @perm_ao1);
SET @perm_manage_ao2_id      = (SELECT `id` FROM `permission` WHERE `code` = @perm_ao2);
SET @perm_manage_finance_id  = (SELECT `id` FROM `permission` WHERE `code` = @perm_finance);
SET @perm_manage_ve_id       = (SELECT `id` FROM `permission` WHERE `code` = @perm_ve);

-- Add data into permission_to_assign_role_map to tell us what permission is needed to assign the role
INSERT INTO `permission_to_assign_role_map` (`permission_id`, `role_id`, `created_by`, `last_modified_by`, `last_modified_on`) VALUES
  (@perm_manage_dvsa_sm_id,   @role_dvsa_sm_id,  @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_manage_dvsa_su_id,   @role_dvsa_su_id,  @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_manage_csco_id,      @role_csco_id,     @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_manage_csm_id,       @role_csm_id,      @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_manage_dvla_mgr_id,  @role_dvla_mgr_id, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_manage_dvla_op_id,   @role_dvla_op_id,  @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_manage_ao1_id,       @role_ao1_id,      @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_manage_ao2_id,       @role_ao2_id,      @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_manage_finance_id,   @role_finance_id,  @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_manage_ve_id,        @role_ve_id,       @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6));

-- Add data into the role_permission_map table to define which role has which permissions
-- AO1 can manage AO2 and VE mot
INSERT INTO `role_permission_map` (`permission_id`, `role_id`, `created_by`, `last_updated_by`, `last_updated_on`) VALUES
  (@perm_manage_ao2_id, @role_ao1_id, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_manage_ve_id,  @role_ao1_id, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6));

-- DVLA Manager can manage a DVLA Operative
INSERT INTO `role_permission_map` (`permission_id`, `role_id`, `created_by`, `last_updated_by`, `last_updated_on`) VALUES
  (@perm_manage_dvla_op_id, @role_dvla_mgr_id, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6));

-- CSM can manage a CSCO
INSERT INTO `role_permission_map` (`permission_id`, `role_id`, `created_by`, `last_updated_by`, `last_updated_on`) VALUES
  (@perm_manage_csco_id, @role_csm_id, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6));

-- Scheme User can manage a Finance role
INSERT INTO `role_permission_map` (`permission_id`, `role_id`, `created_by`, `last_updated_by`, `last_updated_on`) VALUES
  (@perm_manage_finance_id, @role_dvsa_su_id, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6));

-- Scheme manager can manage everything
INSERT INTO `role_permission_map` (`permission_id`, `role_id`, `created_by`, `last_updated_by`, `last_updated_on`) VALUES
  (@perm_manage_dvsa_sm_id,  @role_dvsa_sm_id, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_manage_dvsa_su_id,  @role_dvsa_sm_id, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_manage_finance_id,  @role_dvsa_sm_id, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_manage_csm_id,      @role_dvsa_sm_id, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_manage_csco_id,     @role_dvsa_sm_id, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_manage_dvla_mgr_id, @role_dvsa_sm_id, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_manage_dvla_op_id,  @role_dvsa_sm_id, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_manage_ao1_id,      @role_dvsa_sm_id, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_manage_ao2_id,      @role_dvsa_sm_id, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
  (@perm_manage_ve_id,       @role_dvsa_sm_id, @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6));
