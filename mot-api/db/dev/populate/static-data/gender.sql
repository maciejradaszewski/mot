LOCK TABLES `gender` WRITE;
/*!40000 ALTER TABLE `gender` DISABLE KEYS */;

INSERT INTO `gender` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('3','Not Disclosed','U',NULL,'1','2014-12-17 14:18:02.773405',NULL,NULL,'1','1'),
('2','Female','F',NULL,'1','2014-12-17 14:18:02.773018',NULL,NULL,'1','1'),
('1','Male','M',NULL,'1','2014-12-17 14:18:02.772234',NULL,NULL,'1','1'),
('0','Unknown','UNKN',NULL,'1','2014-12-17 14:18:02.773865',NULL,'2015-03-10 17:47:34.857457','1','1');

/*!40000 ALTER TABLE `gender` ENABLE KEYS */;
UNLOCK TABLES;
