-- Adding to flags to address if a role is trade or/and internal

ALTER TABLE `role`
ADD COLUMN `is_internal` TINYINT(4) UNSIGNED NULL DEFAULT 0 AFTER `code`,
ADD COLUMN `is_trade` TINYINT(4) UNSIGNED NULL DEFAULT 0 AFTER `is_internal`;
