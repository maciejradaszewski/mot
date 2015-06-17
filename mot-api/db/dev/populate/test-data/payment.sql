LOCK TABLES `payment` WRITE;
/*!40000 ALTER TABLE `payment` DISABLE KEYS */;

INSERT INTO `payment` (`id`, `amount`, `receipt_reference`, `status_id`, `type`, `created`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('5','205.00','MOT2-02-20140431-101010-59468313','1','1','2014-06-30 12:46:16.000000',NULL,'0','2014-12-05 11:56:52.698995',NULL,NULL,'1','0'),
('4','3778.15',NULL,'1','2','2014-06-30 12:46:16.000000',NULL,'0','2014-12-05 11:56:52.698995',NULL,NULL,'1','0'),
('3','748.25',NULL,'1','1','2014-06-19 12:45:38.000000',NULL,'0','2014-12-05 11:56:52.698995',NULL,NULL,'1','0'),
('2','92.25',NULL,'1','1','2014-06-18 12:44:53.000000',NULL,'0','2014-12-05 11:56:52.698995',NULL,NULL,'1','0'),
('1','51.25',NULL,'1','1','2013-12-31 12:43:10.000000',NULL,'0','2014-12-05 11:56:52.698995',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `payment` ENABLE KEYS */;
UNLOCK TABLES;
