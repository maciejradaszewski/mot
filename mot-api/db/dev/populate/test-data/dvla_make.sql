LOCK TABLES `dvla_make` WRITE;
/*!40000 ALTER TABLE `dvla_make` DISABLE KEYS */;

INSERT INTO `dvla_make` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('100019','RENAULT','1889A',NULL,'1','2015-04-02 15:21:04.178605',NULL,NULL,'1','0'),
('100016','HYUNDAI','1884Z',NULL,'1','2015-04-02 15:21:04.178605',NULL,NULL,'1','0'),
('100015','AUDI','18801',NULL,'1','2015-04-02 15:21:04.178605',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `dvla_make` ENABLE KEYS */;
UNLOCK TABLES;
