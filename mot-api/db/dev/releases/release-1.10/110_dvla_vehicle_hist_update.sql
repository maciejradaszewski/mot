ALTER TABLE `dvla_vehicle_hist`
  CHANGE COLUMN `make_code` `make_code` VARCHAR(5) CHARACTER SET 'latin1' NULL DEFAULT NULL,
  CHANGE COLUMN `model_code` `model_code` VARCHAR(5) CHARACTER SET 'latin1' NULL DEFAULT NULL,
  CHANGE COLUMN `colour_1_code` `colour_1_code` VARCHAR(1) CHARACTER SET 'latin1' NULL DEFAULT NULL,
  CHANGE COLUMN `colour_2_code` `colour_2_code` VARCHAR(1) CHARACTER SET 'latin1' NULL DEFAULT NULL,
  CHANGE COLUMN `propulsion_code` `propulsion_code` VARCHAR(2) CHARACTER SET 'latin1' NULL DEFAULT NULL,
  CHANGE COLUMN `body_type_code` `body_type_code` VARCHAR(2) CHARACTER SET 'latin1' NULL DEFAULT NULL;
