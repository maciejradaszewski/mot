# Allow Vehicle Examiner to remove Site Manager

# Generic user
SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' || `user_reference` = 'Static Data');

# Permissions
SET @remove_site_manager = (SELECT `id` FROM `permission` WHERE `code`='REMOVE-SITE-MANAGER');

# Roles
SET @role_ve = (SELECT `id` FROM `role` WHERE `code`='VEHICLE-EXAMINER');

# Add the mappings
INSERT INTO
    `role_permission_map` (`role_id`, `permission_id`, `created_by`, `created_on`)
VALUES
    (@role_ve, @remove_site_manager, @app_user_id, CURRENT_TIMESTAMP(6));
