LOCK TABLES `enforcement_condition_appointment_lookup` WRITE;
/*!40000 ALTER TABLE `enforcement_condition_appointment_lookup` DISABLE KEYS */;

INSERT INTO `enforcement_condition_appointment_lookup` (`id`, `description`, `display_order`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('7','November 2009 requirements (all Classes)','7',NULL,NULL,'1','2014-12-04 15:59:09.727340',NULL,NULL,'1','0'),
('6','2004 requirements (all Classes)','6',NULL,NULL,'1','2014-12-04 15:59:09.727340',NULL,NULL,'1','0'),
('5','Pre July 1986 requirements (Ref. RfA for a VTS)','5',NULL,NULL,'1','2014-12-04 15:59:09.727340',NULL,NULL,'1','0'),
('4','Post July 1986 requirements (for Classes 3 and 4)','4',NULL,NULL,'1','2014-12-04 15:59:09.727340',NULL,NULL,'1','0'),
('3','August 1990 requirements (for Class 4)','3',NULL,NULL,'1','2014-12-04 15:59:09.727340',NULL,NULL,'1','0'),
('2','September 1995 requirements (for Classes 3 and 4)','2',NULL,NULL,'1','2014-12-04 15:59:09.727340',NULL,NULL,'1','0'),
('1','June 2000 requirements (all Classes)','1',NULL,NULL,'1','2014-12-04 15:59:09.727340',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `enforcement_condition_appointment_lookup` ENABLE KEYS */;
UNLOCK TABLES;
