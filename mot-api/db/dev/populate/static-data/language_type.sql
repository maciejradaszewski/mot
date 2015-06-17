LOCK TABLES `language_type` WRITE;
/*!40000 ALTER TABLE `language_type` DISABLE KEYS */;

INSERT INTO `language_type` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('2','Welsh','CY',NULL,'1','2014-12-04 15:59:14.082282',NULL,NULL,'1','0'),
('1','English','EN',NULL,'1','2014-12-04 15:59:14.080478',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `language_type` ENABLE KEYS */;
UNLOCK TABLES;
