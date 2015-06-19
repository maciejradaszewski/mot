DELIMITER $$

CREATE TRIGGER `tr_person_bu` BEFORE UPDATE
ON `person` FOR EACH ROW
IF OLD.is_account_claim_required='1' AND NEW.is_account_claim_required='0' THEN
SET NEW.details_confirmed_on=NOW();
END IF;
$$