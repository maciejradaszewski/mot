LOCK TABLES `emergency_reason_lookup` WRITE;
/*!40000 ALTER TABLE `emergency_reason_lookup` DISABLE KEYS */;

INSERT INTO `emergency_reason_lookup` (`id`, `name`, `code`, `description`, `display_order`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('4','Other','OT','Other issue described in free-text comment ','4',NULL,'1','2015-02-17 10:23:13.124147','1','2014-12-04 15:59:19.749173','1','0'),
('3','Payment issue','PI','Payment issue','3',NULL,'1','2015-02-17 10:23:13.124147','1','2014-12-04 15:59:19.749173','1','0'),
('2','Communication problem','CP','Communication problem event','2',NULL,'1','2015-02-17 10:23:13.124147','1','2014-12-04 15:59:19.749173','1','0'),
('1','System outage','SO','System outage event','1',NULL,'1','2015-02-17 10:23:13.124147','1','2014-12-04 15:59:19.749173','1','0');

/*!40000 ALTER TABLE `emergency_reason_lookup` ENABLE KEYS */;
UNLOCK TABLES;
