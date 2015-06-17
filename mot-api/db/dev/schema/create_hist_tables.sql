-- ------------------------ --
-- DO NOT EDIT THIS SCRIPT  --
-- ------------------------ --

-- if any modification needed - please contact DBA (Patrick Mulvany <Patrick.Mulvany@valtech.co.uk>)

SET SESSION group_concat_max_len = 1000000;

SET @TARGET_SCHEMA='#SCHEMA#';
SET @SOURCE_SCHEMA='#SCHEMA#';

SELECT CONCAT('CREATE SCHEMA IF NOT EXISTS `',@TARGET_SCHEMA,'` DEFAULT CHARACTER SET utf8 ;
USE `',@TARGET_SCHEMA,'` ;') cmd
UNION ALL
SELECT CONCAT('-- Create history table and update trigger for ',@SOURCE_SCHEMA,'.',TABLE_NAME,'
',create_hist,';

',create_tr_au,';

',create_tr_ad,';

') cmd
FROM (
SELECT TABLE_NAME, CONCAT('CREATE TABLE `',@TARGET_SCHEMA,'`.`',LEFT(TABLE_NAME,59), '_hist` (
`hist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
`hist_timestamp` TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
`',GROUP_CONCAT(CONCAT (COLUMN_NAME, '` ',COLUMN_TYPE) SEPARATOR ',
`'),',
  PRIMARY KEY (`hist_id`),
  INDEX uq_', LEFT(TABLE_NAME,59), ' (`id`,`version`))') AS create_hist,
CONCAT('CREATE TRIGGER `tr_',LEFT(TABLE_NAME,58),'_au` AFTER UPDATE 
ON `',@SOURCE_SCHEMA,'`.`',
TABLE_NAME, '` FOR EACH ROW 
INSERT INTO  `',@TARGET_SCHEMA,'`.`',LEFT(TABLE_NAME,59),'_hist` (`',GROUP_CONCAT(COLUMN_NAME SEPARATOR '`,
`'),'`) 
VALUES (OLD.`',GROUP_CONCAT(COLUMN_NAME SEPARATOR '`,
OLD.`'),'`)') AS create_tr_au,
CONCAT('CREATE TRIGGER `tr_',LEFT(TABLE_NAME,58),'_ad` AFTER DELETE 
ON `',@SOURCE_SCHEMA,'`.`',
TABLE_NAME, '` FOR EACH ROW 
INSERT INTO  `',@TARGET_SCHEMA,'`.`',LEFT(TABLE_NAME,59),'_hist` (`',GROUP_CONCAT(COLUMN_NAME SEPARATOR '`,
`'),'`) 
VALUES (OLD.`',GROUP_CONCAT(COLUMN_NAME SEPARATOR '`,
OLD.`'),'`)') AS create_tr_ad 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA=@SOURCE_SCHEMA
AND TABLE_NAME NOT LIKE '%_hist'
GROUP BY TABLE_NAME ) tmp_table
;