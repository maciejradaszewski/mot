UPDATE `notification_template`
SET `id`=18
WHERE `content`='Site ${siteNr} ${siteName} has been unlinked from Authorised Examiner ${aeNr} ${aeName}';

UPDATE `notification_template`
SET `id`=19
WHERE `content`='Site ${siteNr} ${siteName} has been linked to Authorised Examiner ${aeNr} ${aeName}.';
