LOCK TABLES `vehicle_class_group` WRITE;
/*!40000 ALTER TABLE `vehicle_class_group` DISABLE KEYS */;

INSERT INTO `vehicle_class_group` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('2','Cars etc','B','B','1','2015-02-17 10:23:34.089716',NULL,NULL,'1','0'),
('1','Bikes','A','A','1','2015-02-17 10:23:34.089716',NULL,NULL,'1','0'),
('0','Unknown','UNKN',NULL,'1','2015-02-17 10:23:34.089716',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `vehicle_class_group` ENABLE KEYS */;
UNLOCK TABLES;
