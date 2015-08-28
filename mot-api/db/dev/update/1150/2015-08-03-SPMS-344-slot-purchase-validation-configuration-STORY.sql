SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data' LIMIT 1);

UPDATE `configuration` SET
 `key` = 'maxSlotsPurchasable',
 `version` = `version` + 1,
 `last_updated_by` = @created_by,
 `last_updated_on`  = NOW()
WHERE `key` = 'testSlotMaxAmount';

INSERT INTO `configuration` (`key`, `value`, `created_by`)
VALUES
  ('maxSlotsHeld', '120000', @created_by);

