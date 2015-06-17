DELIMITER ;

alter table `vehicle`
add column `vin_reversed` varchar(30) CHARACTER SET latin1 DEFAULT NULL COMMENT 'vin reversed by trigger' AFTER `vin`,
add index `in_vehicle_vin_reversed` (`vin_reversed` ASC)
;

alter table `dvla_vehicle`
add column `vin_reversed` varchar(20) CHARACTER SET latin1 DEFAULT NULL COMMENT 'vin reversed by trigger' AFTER `vin`,
add index `in_dvla_vehicle_vin_reversed` (`vin_reversed` ASC)
;

DROP FUNCTION IF EXISTS `f_collapse_base`
;

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
                                REPLACE(UPPER(p_str), 'O','0')
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
          , '.','')
        , ' ','')
;
RETURN v_str;
END
$$

DELIMITER ;

DROP TRIGGER IF EXISTS `vehicle_bi`
;
DROP TRIGGER IF EXISTS `vehicle_bu`
;
DROP TRIGGER IF EXISTS `dvla_vehicle_bi`
;
DROP TRIGGER IF EXISTS `dvla_vehicle_bu`
;

DELIMITER  $$
CREATE TRIGGER `vehicle_bi` BEFORE INSERT ON `vehicle` FOR EACH ROW
BEGIN
  DECLARE v_vin VARCHAR(30);
  SET v_vin = f_collapse(NEW.vin);
  SET NEW.registration_collapsed=f_collapse(NEW.registration);
  SET NEW.vin_collapsed=v_vin;
  SET NEW.vin_reversed=REVERSE(NEW.vin);
  SET NEW.vin_collapsed_reversed=REVERSE(v_vin);
END
$$

CREATE TRIGGER `vehicle_bu` BEFORE UPDATE ON `vehicle` FOR EACH ROW
BEGIN
  DECLARE v_vin VARCHAR(30);
  SET v_vin = f_collapse(NEW.vin);
  SET NEW.registration_collapsed=f_collapse(NEW.registration);
  SET NEW.vin_collapsed=v_vin;
  SET NEW.vin_reversed=REVERSE(NEW.vin);
  SET NEW.vin_collapsed_reversed=REVERSE(v_vin);
END
$$

CREATE TRIGGER `dvla_vehicle_bi` BEFORE INSERT ON `dvla_vehicle` FOR EACH ROW
BEGIN
  DECLARE v_vin VARCHAR(20);
  SET v_vin = f_collapse_dvla(NEW.vin);
  SET NEW.registration_collapsed=f_collapse(NEW.registration);
  SET NEW.vin_collapsed=v_vin;
  SET NEW.vin_reversed=REVERSE(NEW.vin);
  SET NEW.vin_collapsed_reversed=REVERSE(v_vin);
END
$$

CREATE TRIGGER `dvla_vehicle_bu` BEFORE UPDATE ON `dvla_vehicle` FOR EACH ROW
BEGIN
  DECLARE v_vin VARCHAR(20);
  SET v_vin = f_collapse_dvla(NEW.vin);
  SET NEW.registration_collapsed=f_collapse(NEW.registration);
  SET NEW.vin_collapsed=v_vin;
  SET NEW.vin_reversed=REVERSE(NEW.vin);
  SET NEW.vin_collapsed_reversed=REVERSE(v_vin);
END
$$
