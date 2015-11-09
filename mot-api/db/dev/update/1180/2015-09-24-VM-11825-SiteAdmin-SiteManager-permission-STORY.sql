# VM-11825
# Adding view recent certificates permission to Site Admin and Site Manager

SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');
SET @permission_id = (SELECT `id` FROM `permission` WHERE `code` = 'RECENT-CERTIFICATE-PRINT');
SET @sm_id = (SELECT `id` FROM `role` WHERE `code` = 'SITE-MANAGER');
SET @sa_id = (SELECT `id` FROM `role` WHERE `code` = 'SITE-ADMIN');


INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
  (@sm_id, @permission_id, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
  (@sa_id, @permission_id, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6))
  
