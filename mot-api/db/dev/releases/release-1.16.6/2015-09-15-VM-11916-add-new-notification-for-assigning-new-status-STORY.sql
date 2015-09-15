# Generic user
SET @app_user_id = (SELECT id FROM person WHERE user_reference = 'Static Data' OR username = 'static data');

INSERT INTO `notification_template` (`content`, `subject`, `created_by`, `last_updated_by`, `last_updated_on`)
VALUES
    (
        'Your tester qualification status for group ${group} has been changed to ${newStatus}. If you have any questions about this change please contact your area office.',
        '${newStatus} : Tester Status change',
        @app_user_id,
        @app_user_id,
        CURRENT_TIMESTAMP(6)
    );