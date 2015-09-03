SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data' LIMIT 1);


INSERT INTO `test_slot_transaction_amendment_type` (`code`, `title`, `is_active`, `display_order`, `created_by`)
VALUES
  ('T703', 'Manual adjustment of transaction', 1, 1, @created_by),
  ('UNKN', 'Old values', 1, 1, @created_by)
;

UPDATE `test_slot_transaction_amendment_type` SET
  `title` = 'Slot Refund', -- OLD: Refund
  `version` = `version` + 1,
  `last_updated_by` = @created_by,
  `last_updated_on`  = NOW()
WHERE `code` = 'T702';

UPDATE `test_slot_transaction_amendment_type` SET
  `title` = 'Manual Adjustment of slots', -- OLD: Adjustment
  `version` = `version` + 1,
  `last_updated_by` = @created_by,
  `last_updated_on`  = NOW()
WHERE `code` = 'T700';


UPDATE `test_slot_transaction_amendment_reason` SET
  `description` = 'User requested', -- OLD: Refund
  `amendment_type_id` = (SELECT `id` from `test_slot_transaction_amendment_type` WHERE `code` = 'T702'),
  `version` = `version` + 1,
  `last_updated_by` = @created_by,
  `last_updated_on`  = NOW()
WHERE `code` = 'R101';

UPDATE `test_slot_transaction_amendment_reason` SET
  `amendment_type_id` = (SELECT `id` from `test_slot_transaction_amendment_type` WHERE `code` = 'T702'),
  `version` = `version` + 1,
  `last_updated_by` = @created_by,
  `last_updated_on`  = NOW()
WHERE `code` = 'R102'; -- Manual error


UPDATE `test_slot_transaction_amendment_reason` SET
  `description` = 'Card - Chargeback request made', -- OLD: Refund
  `amendment_type_id` = (SELECT `id` from `test_slot_transaction_amendment_type` WHERE `code` = 'T701'),
  `version` = `version` + 1,
  `last_updated_by` = @created_by,
  `last_updated_on`  = NOW()
WHERE `code` = 'R103';

UPDATE `test_slot_transaction_amendment_reason` SET
  `amendment_type_id` = (SELECT `id` from `test_slot_transaction_amendment_type` WHERE `code` = 'T702'),
  `version` = `version` + 1,
  `last_updated_by` = @created_by,
  `last_updated_on`  = NOW()
WHERE `code` = 'R104'; -- Account closure

UPDATE `test_slot_transaction_amendment_reason` SET
  `amendment_type_id` = (SELECT `id` from `test_slot_transaction_amendment_type` WHERE `code` = 'T700'),
  `version` = `version` + 1,
  `last_updated_by` = @created_by,
  `last_updated_on`  = NOW()
WHERE `code` = 'R105'; -- Top-up DVSA Garage


INSERT INTO `test_slot_transaction_amendment_reason` (`code`, `description`, `amendment_type_id`, `display_order`, `created_by`)
VALUES

  -- Failures
  ('R106', 'Cheque is greater than 6 months old', ( SELECT id FROM `test_slot_transaction_amendment_type` WHERE `code` = 'T701'), 1, @created_by),
  ('R107', 'Cheque - 2 sig. required, only 1 displayed', ( SELECT id FROM `test_slot_transaction_amendment_type` WHERE `code` = 'T701'), 2, @created_by),
  ('R108', 'Cheque - Fraud', ( SELECT id FROM `test_slot_transaction_amendment_type` WHERE `code` = 'T701'), 3, @created_by),
  ('R109', 'Cheque - Payment stopped', ( SELECT id FROM `test_slot_transaction_amendment_type` WHERE `code` = 'T701'), 4, @created_by),
  ('R110', 'Cheque - Returned to Drawer', ( SELECT id FROM `test_slot_transaction_amendment_type` WHERE `code` = 'T701'), 5, @created_by),
  ('R111', 'Cheque - Words and Figures do not match', ( SELECT id FROM `test_slot_transaction_amendment_type` WHERE `code` = 'T701'), 6, @created_by),
  -- UPDATE -- ('R112', 'Card - Chargeback request made'
  ('R112', 'Direct debit - DDI Claim made', ( SELECT id FROM `test_slot_transaction_amendment_type` WHERE `code` = 'T701'), 8, @created_by),
  ('R113', 'Direct debit - Mandate payment failure', ( SELECT id FROM `test_slot_transaction_amendment_type` WHERE `code` = 'T701'), 9, @created_by),

  -- Slot Refund
  -- UPDATE -- ('R101', 'User requested'
  -- UPDATE -- ('R102', 'Manual error'
  -- UPDATE -- ('R104', 'Account closure'

  -- Manual Adjustment of slots
  ('R114', 'Reconciliation', ( SELECT id FROM `test_slot_transaction_amendment_type` WHERE `code` = 'T700'), 14, @created_by),
  -- UPDATE -- ('R105', 'Top-up DVSA Garage'

  -- Manual adjustment of transaction
  ('R115', 'Incorrect Customer allocated', ( SELECT id FROM `test_slot_transaction_amendment_type` WHERE `code` = 'T703'), 15, @created_by),
  ('R116', 'Incorrect Amount input', ( SELECT id FROM `test_slot_transaction_amendment_type` WHERE `code` = 'T703'), 16, @created_by),
  ('R117', 'Incorrect Product allocated', ( SELECT id FROM `test_slot_transaction_amendment_type` WHERE `code` = 'T703'), 17, @created_by)
;


