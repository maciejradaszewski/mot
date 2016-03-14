-- change the first_registration_date column in the dvla_vehicle table to be nullable
ALTER TABLE `dvla_vehicle`
CHANGE COLUMN `first_registration_date` `first_registration_date` DATE NULL ;

