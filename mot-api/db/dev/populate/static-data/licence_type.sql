LOCK TABLES `licence_type` WRITE;
/*!40000 ALTER TABLE `licence_type` DISABLE KEYS */;

INSERT INTO `licence_type` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('1','Driving Licence','DRIV',NULL,'2','2014-12-04 15:59:09.746112',NULL,'2015-04-02 15:21:05.411179','1','0');

/*!40000 ALTER TABLE `licence_type` ENABLE KEYS */;
UNLOCK TABLES;
