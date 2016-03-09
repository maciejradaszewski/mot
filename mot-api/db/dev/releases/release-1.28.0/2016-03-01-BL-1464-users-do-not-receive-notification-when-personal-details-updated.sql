SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');


INSERT INTO `notification_template` (`id`,`content`,`subject`,`created_by`,`created_on`, `last_updated_by`, `last_updated_on`)
VALUES
(
    26,
    'Your personal details have been changed by DVSA, please review your profile. If any details are wrong or you did not request the change please contact 0330 123 5654.',
    'Personal details changed',
    @app_user_id,
    CURRENT_TIMESTAMP (6),
    @app_user_id,
    CURRENT_TIMESTAMP (6)
);
