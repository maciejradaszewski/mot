LOCK TABLES `special_notice` WRITE;
/*!40000 ALTER TABLE `special_notice` DISABLE KEYS */;

INSERT INTO `special_notice` (`id`, `username`, `person_id`, `special_notice_content_id`, `is_acknowledged`, `is_deleted`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('2150','inactivetester',NULL,'2','0','0',NULL,'2','2015-04-30 15:15:09.800178',NULL,NULL,'1','0'),
('2149','odsnTester',NULL,'2','0','0',NULL,'2','2015-04-30 15:15:09.800178',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `special_notice` ENABLE KEYS */;
UNLOCK TABLES;
