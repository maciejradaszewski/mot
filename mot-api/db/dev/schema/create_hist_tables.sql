-- ------------------------ --
-- DO NOT EDIT THIS SCRIPT  --
-- ------------------------ --

-- if any modification needed - please contact DBA (Patrick Mulvany <Patrick.Mulvany@valtech.co.uk>)

SET SESSION group_concat_max_len = 1000000;

SELECT CONCAT('-- Create history table and update trigger for ',TABLE_NAME,'
',create_hist,';

',create_tr_ai,';

',create_tr_au,';

',create_tr_ad,';

') cmd
FROM (
SELECT TABLE_NAME, CONCAT('CREATE TABLE  IF NOT EXISTS `',LEFT(TABLE_NAME,59), '_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`hist_transaction_type` CHAR(1) NOT NULL DEFAULT \'U\',
`hist_batch_number` INT UNSIGNED NOT NULL DEFAULT 0,
`',GROUP_CONCAT(CONCAT (COLUMN_NAME, '` ',COLUMN_TYPE) SEPARATOR ',
`'),',
  PRIMARY KEY (`hist_id`),
  INDEX uq_', LEFT(TABLE_NAME,59), ' (`id`,`version`),
  INDEX ix_', LEFT(TABLE_NAME,44), '_mot1_legacy_id (`mot1_legacy_id`))') AS create_hist,
CONCAT('DROP TRIGGER IF EXISTS `tr_',LEFT(TABLE_NAME,58),'_ai`;
CREATE TRIGGER `tr_',LEFT(TABLE_NAME,58),'_ai` AFTER INSERT
ON `',
TABLE_NAME, '` FOR EACH ROW
INSERT INTO  `',LEFT(TABLE_NAME,59),'_hist` (`hist_transaction_type`, `hist_batch_number`, `id`)
VALUES (\'I\', COALESCE(@batch_number,NEW.BATCH_NUMBER), NEW.`id`)') AS create_tr_ai,
CONCAT('DROP TRIGGER IF EXISTS `tr_',LEFT(TABLE_NAME,58),'_au`;
CREATE TRIGGER `tr_',LEFT(TABLE_NAME,58),'_au` AFTER UPDATE
ON `',
TABLE_NAME, '` FOR EACH ROW 
INSERT INTO  `',LEFT(TABLE_NAME,59),'_hist` (`hist_transaction_type`, `hist_batch_number`, `',GROUP_CONCAT(COLUMN_NAME SEPARATOR '`,
`'),'`) 
VALUES (\'U\', COALESCE(@batch_number, CASE WHEN NEW.BATCH_NUMBER<> OLD.BATCH_NUMBER THEN NEW.BATCH_NUMBER ELSE 0 END), OLD.`',GROUP_CONCAT(COLUMN_NAME SEPARATOR '`,
OLD.`'),'`)') AS create_tr_au,
CONCAT('DROP TRIGGER IF EXISTS `tr_',LEFT(TABLE_NAME,58),'_ad`;
CREATE TRIGGER `tr_',LEFT(TABLE_NAME,58),'_ad` AFTER DELETE
ON `',
TABLE_NAME, '` FOR EACH ROW 
INSERT INTO  `',LEFT(TABLE_NAME,59),'_hist` (`hist_transaction_type`, `hist_batch_number`, `',GROUP_CONCAT(COLUMN_NAME SEPARATOR '`,
`'),'`) 
VALUES (\'D\', COALESCE(@BATCH_NUMBER,0), OLD.`',GROUP_CONCAT(COLUMN_NAME SEPARATOR '`,
OLD.`'),'`)') AS create_tr_ad 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA=database()
AND TABLE_NAME NOT LIKE '%_hist' -- don't audit hist tables (i.e. yourself)
AND TABLE_NAME NOT LIKE '%_log' -- audit the audit is not required (but might add trigger to prevent update delete later)
AND TABLE_NAME NOT LIKE 'DATABASECHANGELOG%' -- Liquibase change log
AND TABLE_NAME NOT LIKE 'ctrl_sequence' -- audit of db sequence is not required
GROUP BY TABLE_NAME ) tmp_table
;
