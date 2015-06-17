LOCK TABLES `equipment_status` WRITE;
/*!40000 ALTER TABLE `equipment_status` DISABLE KEYS */;

INSERT INTO `equipment_status` (`id`, `name`, `code`, `description`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('3','Repair Needed','REPRN','Not used in system ATM, kept for migration purposes',NULL,'1','2014-12-04 15:59:09.000000',NULL,'2015-04-02 15:21:02.383766','1','0'),
('2','Withdrawn','WDRWN','VTS had this equipment but it\'s not longer there',NULL,'1','2014-12-04 15:59:09.000000',NULL,'2015-04-02 15:21:02.378293','1','0'),
('1','Active','ACTIV','This is a working piece of equipment that s VTS uses',NULL,'1','2014-12-04 15:59:09.000000',NULL,'2015-04-02 15:21:02.376676','1','0');

/*!40000 ALTER TABLE `equipment_status` ENABLE KEYS */;
UNLOCK TABLES;
