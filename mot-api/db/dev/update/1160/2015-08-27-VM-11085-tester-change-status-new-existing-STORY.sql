SET @app_user = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data' LIMIT 1);

INSERT INTO `notification_template` (`content`, `subject`, `mot1_legacy_id`, `created_by`) VALUES
('Your tester qualification status for group ${group} has been changed from ${previousStatus} to ${newStatus}. If you have any questions about this change please contact your area office.','${newStatus} : Tester Status change',NULL,@app_user);

INSERT INTO
  permission (`code`, `name`, `created_by`)
VALUES
  ('ALTER-TESTER-AUTHORISATION-STATUS', 'Ability to admin mot tester authorisation', @app_user);

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    (SELECT `id`
     FROM role
     WHERE `code` = 'DVSA-AREA-OFFICE-1'),
    (SELECT `id`
     FROM permission
     WHERE `code` = 'ALTER-TESTER-AUTHORISATION-STATUS'),
    @app_user
  ),
  (
    (SELECT `id`
     FROM role
     WHERE `code` = 'DVSA-AREA-OFFICE-2'),
    (SELECT `id`
     FROM permission
     WHERE `code` = 'ALTER-TESTER-AUTHORISATION-STATUS'),
    @app_user
  ),
  (
    (SELECT `id`
     FROM role
     WHERE `code` = 'VEHICLE-EXAMINER'),
    (SELECT `id`
     FROM permission
     WHERE `code` = 'ALTER-TESTER-AUTHORISATION-STATUS'),
    @app_user
  )
  ON DUPLICATE KEY UPDATE created_by = VALUES(`created_by`);
