LOCK TABLES `organisation_type` WRITE;
/*!40000 ALTER TABLE `organisation_type` DISABLE KEYS */;

INSERT INTO `organisation_type` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('7','Authorised Examiner','AE',NULL,'1','2015-02-17 10:23:34.012952',NULL,'2015-04-02 15:21:05.159136','1','1'),
('6','Service Desk','SDESK',NULL,'1','2015-02-17 10:23:34.011915',NULL,'2015-04-02 15:21:05.159136','1','1'),
('5','DVSA','DVSA',NULL,'1','2015-02-17 10:23:34.010803',NULL,'2015-04-02 15:21:05.159136','1','1'),
('4','DVLA','DVLA',NULL,'1','2015-02-17 10:23:34.009691',NULL,'2015-04-02 15:21:05.159136','1','1'),
('3','Training body','TRAIN',NULL,'1','2015-02-17 10:23:34.008623',NULL,'2015-04-02 15:21:05.159136','1','1'),
('2','Testing body','TEST',NULL,'1','2015-02-17 10:23:34.002540',NULL,'2015-04-02 15:21:05.159136','1','1'),
('1','Examining body','EXAM',NULL,'1','2015-02-17 10:23:34.000422',NULL,'2015-04-02 15:21:05.159136','1','1');

/*!40000 ALTER TABLE `organisation_type` ENABLE KEYS */;
UNLOCK TABLES;
