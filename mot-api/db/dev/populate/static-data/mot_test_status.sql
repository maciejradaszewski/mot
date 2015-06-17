LOCK TABLES `mot_test_status` WRITE;
/*!40000 ALTER TABLE `mot_test_status` DISABLE KEYS */;

INSERT INTO `mot_test_status` (`id`, `name`, `description`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('7','REFUSED','Refused','R','R','1','2014-12-17 16:35:16.755018',NULL,'2015-04-02 15:21:05.157571','1','1'),
('6','PASSED','Passed','P','P','1','2014-12-17 16:35:16.755018',NULL,'2015-04-02 15:21:05.157571','1','1'),
('5','FAILED','Failed','F','F','1','2014-12-17 16:35:16.755018',NULL,'2015-04-02 15:21:05.157571','1','1'),
('4','ACTIVE','Active','A',NULL,'1','2014-12-17 16:35:16.755018',NULL,'2015-04-02 15:21:05.157571','1','1'),
('3','ABORTED_VE','Aborted by VE','ABRVE',NULL,'1','2014-12-17 16:35:16.755018',NULL,'2015-04-02 15:21:05.157571','1','1'),
('2','ABORTED','Aborted','ABR','ABR','1','2014-12-17 16:35:16.755018',NULL,'2015-04-02 15:21:05.157571','1','1'),
('1','ABANDONED','Abandoned','ABA','ABA','1','2014-12-17 16:35:16.755018',NULL,'2015-04-02 15:21:05.157571','1','1');

/*!40000 ALTER TABLE `mot_test_status` ENABLE KEYS */;
UNLOCK TABLES;
