# Allow AO1 to be able to remove Site Manager
# Allow Scheme Management to be able to remove Site Manager
# Allow Site Manager to be able to remove Site Manager

# Generic user
SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' || `user_reference` = 'Static Data');

# Permissions
SET @perm_remove_site_manager = (SELECT `id` FROM `permission` WHERE `code`='REMOVE-SITE-MANAGER');

# Roles
SET @role_ao1 = (SELECT `id` FROM `role` WHERE `code`='DVSA-AREA-OFFICE-1');
SET @role_ao2 = (SELECT `id` FROM `role` WHERE `code`='DVSA-AREA-OFFICE-2');
SET @role_dsm = (SELECT `id` FROM `role` WHERE `code`='DVSA-SCHEME-MANAGEMENT');
SET @site_manager = (SELECT `id` FROM `role` WHERE `code`='SITE-MANAGER');

# Add the mappings
INSERT INTO
    `role_permission_map` (`role_id`, `permission_id`, `created_by`, `created_on`)
VALUES
    (@role_dsm, @perm_remove_site_manager, @app_user_id, CURRENT_TIMESTAMP(6)),  # DSM => REMOVE-SITE-MANAGER
    (@role_ao1, @perm_remove_site_manager, @app_user_id, CURRENT_TIMESTAMP(6)),            # AO1 => REMOVE-SITE-MANAGER
    (@role_ao2, @perm_remove_site_manager, @app_user_id, CURRENT_TIMESTAMP(6)),            # AO2 => REMOVE-SITE-MANAGER
    (@site_manager, @perm_remove_site_manager, @app_user_id, CURRENT_TIMESTAMP(6));  # SM => REMOVE-SITE-MANAGER