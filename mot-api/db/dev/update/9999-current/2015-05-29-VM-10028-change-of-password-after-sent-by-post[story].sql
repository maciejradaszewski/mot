ALTER TABLE `person`
ADD COLUMN `is_password_change_required` TINYINT UNSIGNED DEFAULT '0' AFTER `is_account_claim_required`;

-- ALTER TABLE `person_hist`
-- ADD COLUMN `is_password_change_required` TINYINT UNSIGNED DEFAULT '0' AFTER `is_account_claim_required`;
