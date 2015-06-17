-- liquibase formatted sql

-- changeset peleodiase:20150429125800

ALTER TABLE `payment` ADD COLUMN `payment_details` TEXT AFTER `receipt_reference`;