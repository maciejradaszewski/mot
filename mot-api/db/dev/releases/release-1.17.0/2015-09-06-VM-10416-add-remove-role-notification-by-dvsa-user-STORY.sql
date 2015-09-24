# Generic user
SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' || `user_reference` = 'Static Data');

# Add notification template
INSERT INTO
    `notification_template` (`content`, `subject`, `created_by`, `created_on`)
VALUES
    (
        'Your ${positionName} role association has been removed from ${siteName} (${siteOrOrganisationId}).  If you were not expecting this to happen contact your local DVSA area office.',
        'Role Removal',
        @app_user_id,
        CURRENT_TIMESTAMP (6)
    );
