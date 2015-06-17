LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;

INSERT INTO `comment` (`id`, `comment`, `author_person_id`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('2','three','2100',NULL,'1','2014-03-28 14:27:57.000000',NULL,'2015-02-17 10:23:38.983172','1','0'),
('1','one','2100',NULL,'1','2014-03-28 14:27:57.000000',NULL,'2015-02-17 10:23:38.983172','1','0');

/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;
