LOCK TABLES `payment_status` WRITE;
/*!40000 ALTER TABLE `payment_status` DISABLE KEYS */;

INSERT INTO `payment_status` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('2','FAILURE','F',NULL,'2','2014-12-04 15:59:11.094738',NULL,'2015-04-02 15:21:05.363634','1','0'),
('1','SUCCESS','S',NULL,'2','2014-12-04 15:59:11.094738',NULL,'2015-04-02 15:21:05.363634','1','0');

/*!40000 ALTER TABLE `payment_status` ENABLE KEYS */;
UNLOCK TABLES;
