LOCK TABLES `qualification_type` WRITE;
/*!40000 ALTER TABLE `qualification_type` DISABLE KEYS */;

INSERT INTO `qualification_type` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('1','tester-application','',NULL,'1','2014-12-04 15:59:09.682028',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `qualification_type` ENABLE KEYS */;
UNLOCK TABLES;
