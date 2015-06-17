LOCK TABLES `vehicle_class` WRITE;
/*!40000 ALTER TABLE `vehicle_class` DISABLE KEYS */;

INSERT INTO `vehicle_class` (`id`, `name`, `code`, `vehicle_class_group_id`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('7','7','7','2',NULL,'1','2014-12-04 15:59:09.607775',NULL,'2015-02-17 10:23:34.149481','1','0'),
('5','5','5','2',NULL,'1','2014-12-04 15:59:09.607775',NULL,'2015-02-17 10:23:34.148251','1','0'),
('4','4','4','2',NULL,'1','2014-12-04 15:59:09.607775',NULL,'2015-02-17 10:23:34.146601','1','0'),
('3','3','3','2',NULL,'1','2014-12-04 15:59:09.607775',NULL,'2015-02-17 10:23:34.144415','1','0'),
('2','2','2','1',NULL,'1','2014-12-04 15:59:09.607775',NULL,'2015-02-17 10:23:34.143299','1','0'),
('1','1','1','1',NULL,'1','2014-12-04 15:59:09.607775',NULL,'2015-02-17 10:23:34.141723','1','0');

/*!40000 ALTER TABLE `vehicle_class` ENABLE KEYS */;
UNLOCK TABLES;
