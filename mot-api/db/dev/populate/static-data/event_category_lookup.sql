LOCK TABLES `event_category_lookup` WRITE;
/*!40000 ALTER TABLE `event_category_lookup` DISABLE KEYS */;

INSERT INTO `event_category_lookup` (`id`, `code`, `name`, `description`, `display_order`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('3','NT','NT Events','Events that relate to a Tester','3',NULL,'1','2015-02-17 10:23:29.282600',NULL,'2015-04-02 15:21:05.104495','1','1'),
('2','VTS','VTS Event','Events that relate to a VTS Site','2',NULL,'1','2015-02-17 10:23:29.278474',NULL,'2015-04-02 15:21:05.104495','1','1'),
('1','AE','AE Event','Events that relate to an AE','1',NULL,'1','2015-02-17 10:23:29.276346',NULL,'2015-04-02 15:21:05.104495','1','1');

/*!40000 ALTER TABLE `event_category_lookup` ENABLE KEYS */;
UNLOCK TABLES;
