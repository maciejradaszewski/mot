-- Alter table replacement_certificates and add columns
ALTER TABLE `replacement_certificate_draft`
ADD COLUMN `make_name` VARCHAR(50) NULL AFTER `make_id`;

ALTER TABLE `replacement_certificate_draft`
ADD COLUMN `model_name` VARCHAR(50) NULL AFTER `model_id`;
