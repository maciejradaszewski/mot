LOCK TABLES `jasper_document` WRITE;
/*!40000 ALTER TABLE `jasper_document` DISABLE KEYS */;

INSERT INTO `jasper_document` (`id`, `template_id`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('7','3',NULL,'1','0000-00-00 00:00:00.000000',NULL,NULL,'1','0'),
('6','13',NULL,'1','0000-00-00 00:00:00.000000',NULL,NULL,'1','0'),
('5','12',NULL,'1','0000-00-00 00:00:00.000000',NULL,NULL,'1','0'),
('4','5',NULL,'1','0000-00-00 00:00:00.000000',NULL,NULL,'1','0'),
('3','4',NULL,'1','0000-00-00 00:00:00.000000',NULL,NULL,'1','0'),
('2','2',NULL,'1','0000-00-00 00:00:00.000000',NULL,NULL,'1','0'),
('1','1',NULL,'1','0000-00-00 00:00:00.000000',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `jasper_document` ENABLE KEYS */;
UNLOCK TABLES;
