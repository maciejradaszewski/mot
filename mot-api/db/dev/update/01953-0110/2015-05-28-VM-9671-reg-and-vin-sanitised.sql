-- VM-9671
-- Sanitised (collapsed) versions of VIN and Registration Number columns
-- along with triggers to fill those

DELIMITER ;

alter table `vehicle`
add column `registration_collapsed` varchar(20) CHARACTER SET latin1 DEFAULT NULL COMMENT 'registration/VRM with special characters removed by trigger' AFTER `registration`,
add column `vin_collapsed` varchar(30) CHARACTER SET latin1 DEFAULT NULL COMMENT 'vin with special characters removed by trigger' AFTER `vin`,
add column `vin_collapsed_reversed` varchar(30) CHARACTER SET latin1 DEFAULT NULL COMMENT 'vin with special characters removed and reversed by trigger' AFTER `vin_collapsed`,
add index `in_vehicle_registration_collapsed` (`registration_collapsed` ASC),
add index `in_vehicle_vin_collapsed` (`vin_collapsed` ASC),
add index `in_vehicle_vin_collapsed_reversed` (`vin_collapsed_reversed` ASC)
;

# alter table `vehicle_hist` if exists
# add column `registration_collapsed` varchar(20) CHARACTER SET latin1 DEFAULT NULL COMMENT 'registration/VRM with special characters removed by trigger' AFTER `registration`,
# add column `vin_collapsed` varchar(30) CHARACTER SET latin1 DEFAULT NULL COMMENT 'vin with special characters removed by trigger' AFTER `vin`,
# add column `vin_collapsed_reversed` varchar(30) CHARACTER SET latin1 DEFAULT NULL COMMENT 'vin with special characters removed and reversed by trigger' AFTER `vin_collapsed`,
# add index `in_vehicle_hist_registration_collapsed` (`registration_collapsed` ASC),
# add index `in_vehicle_hist_vin_collapsed` (`vin_collapsed` ASC),
# add index `in_vehicle_hist_vin_collapsed_reversed` (`vin_collapsed_reversed` ASC)
# ;

alter table `dvla_vehicle`
add column `registration_collapsed` varchar(13) CHARACTER SET latin1 DEFAULT NULL COMMENT 'registration/VRM with special characters removed by trigger' AFTER `registration`,
add column `vin_collapsed` varchar(20) CHARACTER SET latin1 DEFAULT NULL COMMENT 'vin with special characters removed by trigger' AFTER `vin`,
add column `vin_collapsed_reversed` varchar(20) CHARACTER SET latin1 DEFAULT NULL COMMENT 'vin with special characters removed and reversed by trigger' AFTER `vin_collapsed`,
add index `in_dvla_vehicle_registration_collapsed` (`registration_collapsed` ASC),
add index `in_dvla_vehicle_vin_collapsed` (`vin_collapsed` ASC),
add index `in_dvla_vehicle_vin_collapsed_reversed` (`vin_collapsed_reversed` ASC)
;

# alter table `dvla_vehicle_hist`
# add column `registration_collapsed` varchar(13) CHARACTER SET latin1 DEFAULT NULL COMMENT 'registration/VRM with special characters removed by trigger' AFTER `registration`,
# add column `vin_collapsed` varchar(20) CHARACTER SET latin1 DEFAULT NULL COMMENT 'vin with special characters removed by trigger' AFTER `vin`,
# add column `vin_collapsed_reversed` varchar(20) CHARACTER SET latin1 DEFAULT NULL COMMENT 'vin with special characters removed and reversed by trigger' AFTER `vin_collapsed`
# ;

DELIMITER $$
CREATE FUNCTION `f_collapse_base`( p_str VARCHAR(255)) RETURNS varchar(255) CHARSET latin1
DETERMINISTIC
BEGIN
  -- Standard collapse for VIN/VRM strings
  DECLARE v_str VARCHAR(255) DEFAULT '';
  SET v_str =
      REPLACE(
        REPLACE(
          REPLACE(
            REPLACE(
              REPLACE(
                REPLACE(
                  REPLACE(
                    REPLACE(
                      REPLACE(
                        REPLACE(
                          REPLACE(
                            REPLACE(
                              REPLACE(
                                UPPER(
                                  REPLACE(
                                    REPLACE(
                                      REPLACE(
                                        REPLACE(
                                          REPLACE(p_str, 'h','4')
                                        , 'b','6')
                                      , 'g','9')
                                    , 'q','9')
                                  , 'l','1')
                                  )
                                , 'O','0')
                              , 'I','1')
                            , 'Z','2')
                          , 'E','3')
                        , 'A','4')
                      , 'S','5')
                    , 'G','6')
                  , 'T','7')
                , 'L','7')
              , 'B','8')
            , '-','')
          , '/','')
        , ' ','')
;
RETURN v_str;
END
$$

CREATE FUNCTION `f_collapse`( p_str VARCHAR(255)) RETURNS varchar(255) CHARSET latin1
DETERMINISTIC
BEGIN
  -- Collapse string but strip '*' characters
  DECLARE v_str VARCHAR(255) DEFAULT '';
  SET v_str = REPLACE( f_collapse_base(p_str) , '*','')
;
RETURN v_str;
END
$$

CREATE FUNCTION `f_collapse_dvla`( p_str VARCHAR(255)) RETURNS varchar(255) CHARSET latin1
DETERMINISTIC
BEGIN
  -- Collapse string but replace '*' characters with '_' used in trigger for DVLA VINs
  DECLARE v_str VARCHAR(255) DEFAULT '';
  SET v_str = REPLACE( f_collapse_base(p_str) , '*','_')
;
RETURN v_str;
END
$$

CREATE TRIGGER `vehicle_bi` BEFORE INSERT ON `vehicle` FOR EACH ROW
BEGIN
  DECLARE v_vin VARCHAR(30);
  SET v_vin = f_collapse(NEW.vin);
  SET NEW.registration_collapsed=f_collapse(NEW.registration);
  SET NEW.vin_collapsed=v_vin;
  SET NEW.vin_collapsed_reversed=REVERSE(v_vin);
END
$$

CREATE TRIGGER `vehicle_bu` BEFORE UPDATE ON `vehicle` FOR EACH ROW
BEGIN
  DECLARE v_vin VARCHAR(30);
  SET v_vin = f_collapse(NEW.vin);
  SET NEW.registration_collapsed=f_collapse(NEW.registration);
  SET NEW.vin_collapsed=v_vin;
  SET NEW.vin_collapsed_reversed=REVERSE(v_vin);
END
$$

CREATE TRIGGER `dvla_vehicle_bi` BEFORE INSERT ON `dvla_vehicle` FOR EACH ROW
BEGIN
  DECLARE v_vin VARCHAR(20);
  SET v_vin = f_collapse_dvla(NEW.vin);
  SET NEW.registration_collapsed=f_collapse(NEW.registration);
  SET NEW.vin_collapsed=v_vin;
  SET NEW.vin_collapsed_reversed=REVERSE(v_vin);
END
$$

CREATE TRIGGER `dvla_vehicle_bu` BEFORE UPDATE ON `dvla_vehicle` FOR EACH ROW
BEGIN
  DECLARE v_vin VARCHAR(20);
  SET v_vin = f_collapse_dvla(NEW.vin);
  SET NEW.registration_collapsed=f_collapse(NEW.registration);
  SET NEW.vin_collapsed=v_vin;
  SET NEW.vin_collapsed_reversed=REVERSE(v_vin);
END
$$

-- UPDATE fields using triggers
UPDATE `vehicle` SET `registration`=`registration`;
UPDATE `dvla_vehicle` SET `registration`=`registration`;