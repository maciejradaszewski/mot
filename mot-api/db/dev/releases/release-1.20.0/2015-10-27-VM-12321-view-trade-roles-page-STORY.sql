-- VM-12321
-- permissions to view trade roles of a user

SET @`app_user_id` = (SELECT `id`
                      FROM `person`
                      WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

INSERT INTO `permission` (`code`, `name`, `created_by`, `last_updated_by`, `created_on`, `last_updated_on`) VALUE
  ('VIEW-TRADE-ROLES-OF-ANY-USER', 'View trade roles of any user in the system',
   @`app_user_id`, @`app_user_id`, CURRENT_TIMESTAMP(6), CURRENT_TIMESTAMP(6));

SET @`permission_id` = (SELECT `id`
                        FROM `permission`
                        WHERE `code` = 'VIEW-TRADE-ROLES-OF-ANY-USER');

INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`, `last_updated_by`, `created_on`, `last_updated_on`)
  SELECT
    `id`,
    @`permission_id`,
    @`app_user_id`,
    @`app_user_id`,
    CURRENT_TIMESTAMP(6),
    CURRENT_TIMESTAMP(6)
  FROM `role` `role`
  WHERE `role`.`is_internal`
        AND `role`.`code` != 'FINANCE';
