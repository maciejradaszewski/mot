SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');


INSERT INTO `notification_template` (`id`,`content`,`subject`,`created_by`,`created_on`, `last_updated_by`, `last_updated_on`)
VALUES
(
    25,
    '${nameSurname} - ${userName} has removed their association from the role of ${role} at ${orgOrSiteName} (${orgOrSiteNumber})',
    'Role Disassociation',
    @app_user_id,
    CURRENT_TIMESTAMP (6),
    @app_user_id,
    CURRENT_TIMESTAMP (6)
);
