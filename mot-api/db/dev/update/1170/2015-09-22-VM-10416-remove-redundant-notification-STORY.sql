SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' || `user_reference` = 'Static Data');

DELETE FROM `notification_template` WHERE content = 'Your ${positionName} role association has been removed from ${siteName} (${siteOrOrganisationId}).  If you were not expecting this to happen contact your local DVSA area office.';