SET @last_updated_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' );

UPDATE `notification_template`
  SET `content` = '${nomineeName} has ${action} the role of ${positionName} at ${siteName} (${siteNumber}).',
      `version` = (`version` + 1),
      `last_updated_by` = @last_updated_by
WHERE `content` = '${nomineeName} has ${action} nomination for the role of ${positionName} at ${siteName} (${siteNumber}).';

UPDATE `notification_template`
  SET `content` = '${nomineeName} has ${action} the role of ${positionName} at ${organisationName} (${organisationNumber}).',
      `version` = (`version` + 1),
      `last_updated_by` = @last_updated_by
WHERE `content` = '${nomineeName} has ${action} nomination for the role of ${positionName} at ${organisationName} (${organisationNumber}).';