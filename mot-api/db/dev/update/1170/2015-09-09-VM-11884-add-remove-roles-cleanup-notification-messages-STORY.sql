# Notification template message for when a DVSA User removes a role

# Generic user
SET @app_user_id = (SELECT id FROM person WHERE user_reference = 'Static Data' OR username = 'static data');

UPDATE `notification_template`
SET
`content` = 'Your ${positionName} role association has been removed from ${organisationName} (${siteOrOrganisationId}).  If you were not expecting this to happen contact ${contactText}.'
WHERE
`id`='10';

UPDATE `notification_template`
SET
`content` = 'Your ${positionName} role association has been removed from ${siteName} (${siteOrOrganisationId}).  If you were not expecting this to happen contact ${contactText}.'
WHERE
`id`='11';