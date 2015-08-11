SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data' LIMIT 1);

INSERT INTO
  `test_slot_transaction_amendment_reason` (`code`,`description`, `created_by`)
VALUES
  ('R105','Top-up DVSA Garage', @created_by)
;