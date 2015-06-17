LOCK TABLES `empty_vrm_reason_lookup` WRITE;
/*!40000 ALTER TABLE `empty_vrm_reason_lookup` DISABLE KEYS */;

INSERT INTO `empty_vrm_reason_lookup` (`id`, `code`, `name`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('2','NOTR','Not required',NULL,'2','2015-04-30 15:15:08.898573',NULL,NULL,'1','0'),
('1','MISS','Missing',NULL,'2','2015-04-30 15:15:08.898573',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `empty_vrm_reason_lookup` ENABLE KEYS */;
UNLOCK TABLES;
