LOCK TABLES `reason_for_rejection_type` WRITE;
/*!40000 ALTER TABLE `reason_for_rejection_type` DISABLE KEYS */;

INSERT INTO `reason_for_rejection_type` (`id`, `name`, `code`, `description`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('6','USER ENTERED','U','User Entered','U','1','2015-02-17 10:23:30.871507',NULL,NULL,'1','0'),
('5','SYSTEM GENERATED','S','System Generated','S','1','2015-02-17 10:23:30.871507',NULL,NULL,'1','0'),
('4','NON SPECIFIC','N','Non specific Advisory','N','1','2015-02-17 10:23:30.871507',NULL,NULL,'1','0'),
('3','PRS','P','',NULL,'1','2014-12-04 15:59:09.582767',NULL,'2015-02-17 10:23:30.836003','1','0'),
('2','FAIL','F','',NULL,'1','2014-12-04 15:59:09.582767',NULL,'2015-02-17 10:23:30.834478','1','0'),
('1','ADVISORY','A','',NULL,'1','2014-12-04 15:59:09.582767',NULL,'2015-02-17 10:23:30.837157','1','0');

/*!40000 ALTER TABLE `reason_for_rejection_type` ENABLE KEYS */;
UNLOCK TABLES;
