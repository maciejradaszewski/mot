-- Updating recently added "is_internal" & "is_trade" flags in the role table
SET @static_user = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

UPDATE `role` SET
  `is_trade`='1',
  `is_internal`='0',
  `last_updated_by` = @static_user,
  `version` = (`version` + 1)
WHERE
  `code`='AUTHORISED-EXAMINER';

UPDATE `role` SET
  `is_trade`='1',
  `is_internal`='0',
  `last_updated_by` = @static_user,
  `version` = (`version` + 1)
WHERE `code`='AUTHORISED-EXAMINER-DELEGATE';

UPDATE `role` SET
  `is_trade`='1',
  `is_internal`='0',
  `last_updated_by` = @static_user,
  `version` = (`version` + 1)
WHERE `code`='AUTHORISED-EXAMINER-DESIGNATED-MANAGER';

UPDATE `role` SET
  `is_trade`='1',
  `is_internal`='0',
  `last_updated_by` = @static_user,
  `version` = (`version` + 1)
WHERE `code`='AUTHORISED-EXAMINER-PRINCIPAL';

UPDATE `role` SET
  `is_trade`='0',
  `is_internal`='1',
  `last_updated_by` = @static_user,
  `version` = (`version` + 1)
WHERE `code`='CUSTOMER-SERVICE-CENTRE-OPERATIVE';

UPDATE `role` SET
  `is_trade`='0',
  `is_internal`='1',
  `last_updated_by` = @static_user,
  `version` = (`version` + 1)
WHERE `code`='CUSTOMER-SERVICE-MANAGEMENT';

UPDATE `role` SET
  `is_trade`='0',
  `is_internal`='1',
  `last_updated_by` = @static_user,
  `version` = (`version` + 1)
WHERE `code`='DVLA-Manager';

UPDATE `role` SET
  `is_trade`='0',
  `is_internal`='1',
  `last_updated_by` = @static_user,
  `version` = (`version` + 1)
WHERE `code`='DVLA-OPERATIVE';

UPDATE `role` SET
  `is_trade`='0',
  `is_internal`='1',
  `last_updated_by` = @static_user,
  `version` = (`version` + 1)
WHERE `code`='DVSA-AREA-OFFICE-1';

UPDATE `role` SET
  `is_trade`='0',
  `is_internal`='1',
  `last_updated_by` = @static_user,
  `version` = (`version` + 1)
WHERE `code`='DVSA-AREA-OFFICE-2';

UPDATE `role` SET
  `is_trade`='0',
  `is_internal`='1',
  `last_updated_by` = @static_user,
  `version` = (`version` + 1)
WHERE `code`='DVSA-SCHEME-MANAGEMENT';

UPDATE `role` SET
  `is_trade`='0',
  `is_internal`='1',
  `last_updated_by` = @static_user,
  `version` = (`version` + 1)
WHERE `code`='DVSA-SCHEME-USER';

UPDATE `role` SET
  `is_trade`='0',
  `is_internal`='1',
  `last_updated_by` = @static_user,
  `version` = (`version` + 1)
WHERE `code`='FINANCE';

UPDATE `role` SET
  `is_trade`='1',
  `is_internal`='0',
  `last_updated_by` = @static_user,
  `version` = (`version` + 1)
WHERE `code`='SITE-ADMIN';

UPDATE `role` SET
  `is_trade`='1',
  `is_internal`='0',
  `last_updated_by` = @static_user,
  `version` = (`version` + 1)
WHERE `code`='SITE-MANAGER';

UPDATE `role` SET
  `is_trade`='1',
  `is_internal`='0',
  `last_updated_by` = @static_user,
  `version` = (`version` + 1)
WHERE `code`='TESTER';

UPDATE `role` SET
  `is_trade`='0',
  `is_internal`='1',
  `last_updated_by` = @static_user,
  `version` = (`version` + 1)
WHERE `code`='VEHICLE-EXAMINER';