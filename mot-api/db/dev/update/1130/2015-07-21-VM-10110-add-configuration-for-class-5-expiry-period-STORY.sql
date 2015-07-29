-- Adds the standard expiry for a class 5 vehicle. This value is used to calculate
-- the notional expiry date when no previous test is currently  held on record.
SET @created_by = (SELECT id FROM person WHERE user_reference = 'Static Data' OR username = 'static data');

INSERT INTO `configuration` (`key`, `value`, `created_by`)
VALUES('yearsBeforeFirstMotTestIsDueClass5', 1, @created_by);
