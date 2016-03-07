SET @static_user = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');

UPDATE `notification_template`
  SET `content` = 'To continue to access the MOT testing service you must <a href="${change_password_url}">change your password</a> as soon as possible. Your current password will stop working ${expiryDay}.',
      `last_updated_on` = CURRENT_TIMESTAMP,
      `version` = `version` + 1
  WHERE `id` = 24;