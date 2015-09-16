SET @created_by  = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data' LIMIT 1);

UPDATE `payment_status` SET `cpms_code` = 802, `last_updated_by` = @created_by WHERE `code` = 'F';
UPDATE `payment_status` SET `cpms_code` = 801, `last_updated_by` = @created_by WHERE `code` = 'S';

INSERT INTO `payment_status` (`name`, `code`, `cpms_code`, `created_by`) VALUES
  ('User Cancelled', 'C', 807, @created_by),
  ('Manually Refunded', 'R', 809, @created_by),
  ('In Progress', 'P', 800, @created_by),
  ('Abandoned', 'A', 810, @created_by);

SET @in_progress = (SELECT `id` FROM `payment_status` WHERE `code` = 'P');

UPDATE `payment` SET `status_id` = @in_progress WHERE `status_id` IS NULL;

ALTER TABLE `payment` MODIFY COLUMN `status_id` SMALLINT UNSIGNED NOT NULL;
ALTER TABLE payment_status MODIFY COLUMN `cpms_code` SMALLINT UNSIGNED NOT NULL;
