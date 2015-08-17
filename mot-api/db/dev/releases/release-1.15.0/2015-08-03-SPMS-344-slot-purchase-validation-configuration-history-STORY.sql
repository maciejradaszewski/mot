SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data' LIMIT 1);

UPDATE `configuration_hist` SET
 `key` = 'maxSlotsPurchasable',
 `version` = `version` + 1,
 `last_updated_by` = @created_by,
 `last_updated_on  = NOW()
WHERE `key` = 'testSlotMaxAmount';
