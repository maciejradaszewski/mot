LOCK TABLES `message_type` WRITE;
/*!40000 ALTER TABLE `message_type` DISABLE KEYS */;

INSERT INTO `message_type` (`id`, `name`, `code`, `expiry_period`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('5','Account reset by letter','ARL',NULL,NULL,'2','2015-04-30 15:15:09.826872',NULL,NULL,'1','0'),
('4','Username reminder by email','URE',NULL,NULL,'2','2014-12-04 15:59:19.920574',NULL,NULL,'1','1'),
('3','Password reset by email','PRE',NULL,NULL,'2','2014-12-04 15:59:19.920574',NULL,NULL,'1','1'),
('2','Username reminder by letter','URL',NULL,NULL,'2','2014-12-04 15:59:19.920574',NULL,'2015-04-02 15:21:05.351770','1','1'),
('1','Password reset by letter','PRL',NULL,NULL,'2','2014-12-04 15:59:19.920574',NULL,'2015-04-02 15:21:05.351770','1','1');

/*!40000 ALTER TABLE `message_type` ENABLE KEYS */;
UNLOCK TABLES;
