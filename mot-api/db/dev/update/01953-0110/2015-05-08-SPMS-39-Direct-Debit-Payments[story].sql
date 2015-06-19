-- liquibase formatted sql

-- changeset peleodiase:20150430125800

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    (SELECT `id` FROM role WHERE `code` = 'CRON'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-INCREMENT-BALANCE'),
    2
  )
 ON DUPLICATE KEY UPDATE `created_by` = VALUES(`created_by`)
  ;


ALTER TABLE `direct_debit_status` ADD (`cpms_code` varchar(5) NOT NULL);
ALTER TABLE `direct_debit_status` ADD INDEX `ix_cpms_code` (`cpms_code`);
ALTER TABLE `direct_debit` ADD INDEX `ix_direct_debit_status_id_is_active_next_collection_date` (`status_id`,`is_active`,`next_collection_date`);

UPDATE `direct_debit_status` SET `cpms_code` = 431 WHERE `code` = 'C';
UPDATE `direct_debit_status` SET `cpms_code` = 432 WHERE `code` = 'A';
UPDATE `direct_debit_status` SET `cpms_code` = 433 WHERE `code` = 'S';
UPDATE `direct_debit_status` SET `cpms_code` = 437 WHERE `code` = 'SBT';
UPDATE `direct_debit_status` SET `cpms_code` = 436 WHERE `code` = 'F';

INSERT INTO `direct_debit_status` (`name`, `code`, `cpms_code`, `created_by`) VALUES
  (
    'RE-ACTIVATED', 'R', 435, 2
  );

