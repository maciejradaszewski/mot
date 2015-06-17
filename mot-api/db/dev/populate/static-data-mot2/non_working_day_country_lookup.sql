LOCK TABLES `non_working_day_country_lookup` WRITE;
/*!40000 ALTER TABLE `non_working_day_country_lookup` DISABLE KEYS */;

INSERT INTO `non_working_day_country_lookup` (`id`, `country_lookup_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version` ) VALUES
('3','259','2','2015-03-23 09:12:10.182159',NULL,'2015-04-02 15:21:05.354212','1'),
('2','260','2','2015-03-23 09:12:10.182159',NULL,'2015-04-02 15:21:05.354212','1'),
('1','258','2','2015-03-23 09:12:10.182159',NULL,'2015-04-02 15:21:05.354212','1');

/*!40000 ALTER TABLE `non_working_day_country_lookup` ENABLE KEYS */;
UNLOCK TABLES;
