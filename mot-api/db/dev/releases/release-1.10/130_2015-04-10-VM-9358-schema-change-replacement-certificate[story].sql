-- Alter table replacement_certificates and add column
ALTER TABLE `replacement_certificate_draft`
ADD COLUMN `is_vin_registration_changed` TINYINT UNSIGNED NOT NULL DEFAULT '0' AFTER `replacement_reason`;

-- Alter table certificate_replacement and add column
ALTER TABLE `certificate_replacement`
ADD COLUMN `is_vin_registration_changed` TINYINT UNSIGNED NOT NULL DEFAULT '0' AFTER `reason`;
