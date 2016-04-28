# BL-1715
# Creating new notification

SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');

INSERT INTO `notification_template` (`id`, `content`, `subject`, `created_by`,`created_on`, `last_updated_by`, `last_updated_on`)
VALUES
(
  28,
  'Tester qualification certificate details for group ${group} recorded by ${user}. Certificate number ${certificateNumber} and Certificate date ${dateOfQualification}',
  'Group ${group} certificate added',
  @app_user_id,
  CURRENT_TIMESTAMP (6),
  @app_user_id,
  CURRENT_TIMESTAMP (6)
);
