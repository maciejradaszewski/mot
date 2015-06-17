-- change the make_code column in the dvla_vehicle table to be nullable
ALTER TABLE
  `dvla_vehicle`
CHANGE COLUMN `make_code`
  `make_code`
  VARCHAR(5) CHARACTER SET latin1
  DEFAULT NULL
  COMMENT 'should be char 2 and data should be retrieved from `dvla_make`, not `make`' ;
