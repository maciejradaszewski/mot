SET @static_user = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');

INSERT INTO `configuration` (`key`, `value`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
  ('paymentInProgressTimeBoxEnd', '1800', @static_user, CURRENT_TIMESTAMP(6), @static_user, CURRENT_TIMESTAMP(6));

UPDATE `configuration` SET
  `value`='172800',
  `last_updated_by` = @static_user,
  `version` = (`version` + 1)
WHERE
  `key`='paymentInProgressTimeout';

UPDATE `configuration` SET
  `value`='345600',
  `key`='paymentInProgressTimeBoxStart',
  `last_updated_by` = @static_user,
  `version` = (`version` + 1)
WHERE
  `key`='paymentInProgressTimeBox';