LOCK TABLES `emergency_log` WRITE;
/*!40000 ALTER TABLE `emergency_log` DISABLE KEYS */;

INSERT INTO `emergency_log` (`id`, `number`, `description`, `start_date`, `end_date`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('4','12345B','Current test outage 2','2015-02-17','2015-02-17',NULL,'1','2015-02-17 10:23:28.865366','1','2015-02-17 10:23:28.865366','1','0'),
('3','12345A','Historic Test outage 1','2014-01-01','2014-01-01',NULL,'1','2015-02-17 10:23:28.865366','1','2015-02-17 10:23:28.865366','1','0');

/*!40000 ALTER TABLE `emergency_log` ENABLE KEYS */;
UNLOCK TABLES;
