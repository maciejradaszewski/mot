ALTER TABLE `test_slot_transaction`
MODIFY `slots_after` INT(10) signed NOT NULL DEFAULT 0;

ALTER TABLE `test_slot_transaction_hist`
MODIFY `slots_after` INT(10) signed NOT NULL DEFAULT 0;