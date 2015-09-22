# Allow AO1 to be able to add or remove AE roles
# Allow Scheme Management to be able to remove AE roles

# Generic user
SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' || `user_reference` = 'Static Data');

# Permissions
SET @perm_remove_position_from_ae = (SELECT `id` FROM `permission` WHERE `code`='REMOVE-POSITION-FROM-AE');
SET @perm_remove_aedm_from_ae = (SELECT `id` FROM `permission` WHERE `code`='REMOVE-AEDM-FROM-AE');
SET @perm_nominate_aedm = (SELECT `id` FROM `permission` WHERE `code`='NOMINATE-AEDM');
SET @perm_nominate_role = (SELECT `id` FROM `permission` WHERE `code`='NOMINATE-ROLE-AT-AE');

# Roles
SET @role_ao1 = (SELECT `id` FROM `role` WHERE `code`='DVSA-AREA-OFFICE-1');
SET @role_dsm = (SELECT `id` FROM `role` WHERE `code`='DVSA-SCHEME-MANAGEMENT');

# Add the mappings
INSERT INTO
    `role_permission_map` (`role_id`, `permission_id`, `created_by`, `created_on`)
VALUES
    (@role_dsm, @perm_remove_position_from_ae, @app_user_id, CURRENT_TIMESTAMP(6)),  # DSM => REMOVE-POSITION-FROM-AE
    (@role_dsm, @perm_remove_aedm_from_ae, @app_user_id, CURRENT_TIMESTAMP(6)),      # DSM => REMOVE-AEDM-FROM-AE
    (@role_ao1, @perm_nominate_aedm, @app_user_id, CURRENT_TIMESTAMP(6)),            # AO1 => NOMINATE-AEDM
    (@role_ao1, @perm_nominate_role, @app_user_id, CURRENT_TIMESTAMP(6)),            # AO1 => NOMINATE-ROLE-AT-AE
    (@role_ao1, @perm_remove_position_from_ae, @app_user_id, CURRENT_TIMESTAMP(6)),  # AO1 => REMOVE-POSITION-FROM-AE
    (@role_ao1, @perm_remove_aedm_from_ae, @app_user_id, CURRENT_TIMESTAMP(6));      # AO1 => REMOVE-AEDM-FROM-AE