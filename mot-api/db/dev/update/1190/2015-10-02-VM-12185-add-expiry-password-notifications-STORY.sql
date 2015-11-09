# VM-12185
# Adding new notification

SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');

INSERT into `notification_template`
    (`id`, `content`, `subject`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`)
VALUES
    (24, "To continue to access the MOT testing service you must <a href=\"/profile/change-password\">change your password</a> as soon as possible. Your current password will stop working ${expiryDay}.", "Your password will expire ${expiryDay}", @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));