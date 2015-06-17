LOCK TABLES `direct_debit_status` WRITE;
/*!40000 ALTER TABLE `direct_debit_status` DISABLE KEYS */;

INSERT INTO `direct_debit_status` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('3','SUSPENDED','S',NULL,'2','2014-12-04 15:59:13.531339',NULL,'2015-04-02 15:21:05.340007','1','0'),
('2','ACTIVE','A',NULL,'2','2014-12-04 15:59:11.107744',NULL,'2015-04-02 15:21:05.340007','1','0'),
('1','CREATED','C',NULL,'2','2014-12-04 15:59:11.107744',NULL,'2015-04-02 15:21:05.340007','1','0');

/*!40000 ALTER TABLE `direct_debit_status` ENABLE KEYS */;
UNLOCK TABLES;
