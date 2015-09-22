ALTER TABLE payment_status ADD `cpms_code` SMALLINT UNSIGNED AFTER `code`, COMMENT 'CPMS code for given status type';

ALTER TABLE `payment` DROP FOREIGN KEY `fk_payment_payment_status`;

ALTER TABLE `payment` ADD CONSTRAINT `fk_payment_status_id_payment_status_id` FOREIGN KEY (`status_id`) REFERENCES payment_status(`id`);