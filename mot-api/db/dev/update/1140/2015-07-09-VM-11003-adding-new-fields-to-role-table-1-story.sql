-- Adding to flags to address if a role is trade or/and internal

ALTER TABLE `role`
ADD COLUMN `is_internal` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'An internal role relates to a government worker, not someone who perfoms MOTs' AFTER `code`,
ADD COLUMN `is_trade` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'A trade role is associated to an MOT tester or MOT testing management' AFTER `is_internal`;
