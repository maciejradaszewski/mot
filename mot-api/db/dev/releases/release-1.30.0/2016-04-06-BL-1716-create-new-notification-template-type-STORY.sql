# BL-1716
# Creating new event type

SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');

INSERT INTO `notification_template` (`id`, `content`, `subject`, `created_by`,`created_on`, `last_updated_by`, `last_updated_on`)
VALUES
(
  27,
  'Tester qualification certificate removed for group ${group} by ${user}. Certificate number ${certificateNumber} and Certificate date ${dateOfQualification}',
  'Group ${group} certificate removal',
  @app_user_id,
  CURRENT_TIMESTAMP (6),
  @app_user_id,
  CURRENT_TIMESTAMP (6)
);
