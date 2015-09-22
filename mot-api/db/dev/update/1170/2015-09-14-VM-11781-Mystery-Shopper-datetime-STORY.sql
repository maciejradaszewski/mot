ALTER TABLE `incognito_vehicle`
MODIFY COLUMN `start_date` DATETIME,
MODIFY COLUMN `end_date` DATETIME;

ALTER TABLE `incognito_vehicle_hist`
MODIFY COLUMN `start_date` DATETIME,
MODIFY COLUMN `end_date` DATETIME;