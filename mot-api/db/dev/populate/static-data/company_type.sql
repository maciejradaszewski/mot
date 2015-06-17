LOCK TABLES `company_type` WRITE;
/*!40000 ALTER TABLE `company_type` DISABLE KEYS */;

INSERT INTO `company_type` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('5','Public Authority','PA','B','1','2015-02-17 10:23:34.022817',NULL,NULL,'1','1'),
('4','Limited Liability Partnership','LLP',NULL,'1','2015-02-17 10:23:34.021770',NULL,NULL,'1','1'),
('3','Sole Trader','ST','S','1','2015-02-17 10:23:34.020657',NULL,NULL,'1','1'),
('2','Partnership','P','P','1','2015-02-17 10:23:34.016529',NULL,NULL,'1','1'),
('1','Registered Company','RC','C','1','2015-02-17 10:23:34.014050',NULL,NULL,'1','1');

/*!40000 ALTER TABLE `company_type` ENABLE KEYS */;
UNLOCK TABLES;
