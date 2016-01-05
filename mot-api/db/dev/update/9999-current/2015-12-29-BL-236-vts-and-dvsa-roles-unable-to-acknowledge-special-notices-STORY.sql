-- BL-236 - add permissions to roles that can acknowledge special notices

SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data');
SET @permission_to_acknowledge_special_notice_id = (SELECT `id` FROM `permission` WHERE `code` = 'SPECIAL-NOTICE-ACKNOWLEDGE');
SET @permission_to_recieve_special_notice_id = (SELECT `id` FROM `permission` WHERE `code` = 'SPECIAL-NOTICE-READ-CURRENT');

SET @ve             = (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER');
SET @ao1            = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1');
SET @ao2            = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2');
SET @scheme_manager = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-MANAGEMENT');
SET @scheme_user    = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-SCHEME-USER');
SET @csco           = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE');
SET @csm            = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-MANAGER');
SET @site_manager   = (SELECT `id` FROM `role` WHERE `code` = 'SITE-MANAGER');
SET @site_admin     = (SELECT `id` FROM `role` WHERE `code` = 'SITE-ADMIN');
SET @tester         = (SELECT `id` FROM `role` WHERE `code` = 'TESTER');

-- add permissions to roles that acknowledge special notices (tester already has that permission):
INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by` ,`created_on`, `last_updated_by`, `last_updated_on`)
  SELECT `role_id`, `permission_id`, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6) FROM
  (
    SELECT @ve AS `role_id`,@permission_to_acknowledge_special_notice_id AS `permission_id` UNION
    SELECT @ao1,            @permission_to_acknowledge_special_notice_id UNION
    SELECT @ao2,            @permission_to_acknowledge_special_notice_id UNION
    SELECT @scheme_manager, @permission_to_acknowledge_special_notice_id UNION
    SELECT @scheme_user,    @permission_to_acknowledge_special_notice_id UNION
    SELECT @csco,           @permission_to_acknowledge_special_notice_id UNION
    SELECT @csm,            @permission_to_acknowledge_special_notice_id UNION
    SELECT @site_manager,   @permission_to_acknowledge_special_notice_id UNION
    SELECT @site_admin,     @permission_to_acknowledge_special_notice_id
  ) AS union_alias;

-- currently everyone can recieve special notices - premission is assigned to USER role
DELETE FROM role_permission_map WHERE permission_id = @permission_to_recieve_special_notice_id;

-- add permissions to roles that can recieve and read special notices:
INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by` ,`created_on`, `last_updated_by`, `last_updated_on`)
  SELECT `role_id`, `permission_id`, @app_user_id, CURRENT_TIMESTAMP (6), @app_user_id, CURRENT_TIMESTAMP (6) FROM
  (
    SELECT @ve AS `role_id`,@permission_to_recieve_special_notice_id AS `permission_id` UNION
    SELECT @ao1,            @permission_to_recieve_special_notice_id UNION
    SELECT @ao2,            @permission_to_recieve_special_notice_id UNION
    SELECT @scheme_manager, @permission_to_recieve_special_notice_id UNION
    SELECT @scheme_user,    @permission_to_recieve_special_notice_id UNION
    SELECT @csco,           @permission_to_recieve_special_notice_id UNION
    SELECT @csm,            @permission_to_recieve_special_notice_id UNION
    SELECT @site_manager,   @permission_to_recieve_special_notice_id UNION
    SELECT @site_admin,     @permission_to_recieve_special_notice_id UNION
    SELECT @tester,         @permission_to_recieve_special_notice_id
  ) AS union_alias;
