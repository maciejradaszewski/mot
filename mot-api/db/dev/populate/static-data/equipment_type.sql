LOCK TABLES `equipment_type` WRITE;
/*!40000 ALTER TABLE `equipment_type` DISABLE KEYS */;

INSERT INTO `equipment_type` (`id`, `code`, `name`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('17','TE','Tow Socket Tester',NULL,'1','2014-12-04 15:59:10.552837',NULL,NULL,'1','0'),
('16','WF','Weighing Facility',NULL,'1','2014-12-04 15:59:10.552837',NULL,NULL,'1','0'),
('15','PD','Wheel Play Detector',NULL,'1','2014-12-04 15:59:10.552837',NULL,NULL,'1','0'),
('14','AP','Brake Pedal Applicator',NULL,'1','2014-12-04 15:59:10.552837',NULL,NULL,'1','0'),
('13','YA','Tyre depth Gauge',NULL,'1','2014-12-04 15:59:10.552837',NULL,NULL,'1','0'),
('12','TA','Temperature Measuring Device',NULL,'1','2014-12-04 15:59:10.552837',NULL,NULL,'1','0'),
('11','HS','Headlamps, Aiming Screen',NULL,'1','2014-12-04 15:59:10.552837',NULL,NULL,'1','0'),
('10','HB','Headlamps, Beam Tester',NULL,'1','2014-12-04 15:59:10.552837',NULL,NULL,'1','0'),
('9','FA','EGA, Pre 1996 Spec',NULL,'1','2014-12-04 15:59:10.552837',NULL,NULL,'1','0'),
('8','EA','EGA, 1996 Spec',NULL,'1','2014-12-04 15:59:10.552837',NULL,NULL,'1','0'),
('7','DT','DSM + Temp Measurement',NULL,'1','2014-12-04 15:59:10.552837',NULL,NULL,'1','0'),
('6','DA','DSM',NULL,'1','2014-12-04 15:59:10.552837',NULL,NULL,'1','0'),
('5','BD','Brakes, Decelerometer',NULL,'1','2014-12-04 15:59:10.552837',NULL,NULL,'1','0'),
('4','BL','Brakes, Plate Tester',NULL,'1','2014-12-04 15:59:10.552837',NULL,NULL,'1','0'),
('3','BU','Brakes, Pull Tester',NULL,'1','2014-12-04 15:59:10.552837',NULL,NULL,'1','0'),
('2','BR','Brakes, Roller Tester',NULL,'1','2014-12-04 15:59:10.552837',NULL,NULL,'1','0'),
('1','BG','Brakes, Gradient Tester',NULL,'1','2014-12-04 15:59:10.552837',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `equipment_type` ENABLE KEYS */;
UNLOCK TABLES;
