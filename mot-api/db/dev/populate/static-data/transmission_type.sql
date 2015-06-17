LOCK TABLES `transmission_type` WRITE;
/*!40000 ALTER TABLE `transmission_type` DISABLE KEYS */;

INSERT INTO `transmission_type` (`id`, `name`, `code`, `display_order`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('2','Manual','m','1',NULL,'1','2014-12-04 15:59:09.751629',NULL,NULL,'1','0'),
('1','Automatic','a','1',NULL,'1','2014-12-04 15:59:09.751629',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `transmission_type` ENABLE KEYS */;
UNLOCK TABLES;
