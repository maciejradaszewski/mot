SET @created_by = (SELECT `id`
                   FROM `person`
                   WHERE `user_reference` = 'Static Data' OR
                         `username` = 'static data');

SET @permission_id = (SELECT `id`
                      FROM `permission`
                      WHERE `code` = 'VIEW-OTHER-USER-PROFILE');

SET @role_csm_id = (SELECT `id`
                    FROM `role`
                    WHERE `code` = 'CUSTOMER-SERVICE-MANAGER');

SET @role_dvla_manager_id = (SELECT `id`
                             FROM `role`
                             WHERE `code` = 'DVLA-MANAGER');

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`, `last_updated_by`, `last_updated_on`)
VALUES
  (@role_csm_id, @permission_id, @created_by, @created_by, CURRENT_TIMESTAMP(6)),
  (@role_dvla_manager_id, @permission_id, @created_by, @created_by, CURRENT_TIMESTAMP(6));
