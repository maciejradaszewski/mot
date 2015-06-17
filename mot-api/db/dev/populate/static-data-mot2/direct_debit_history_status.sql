LOCK TABLES `direct_debit_history_status` WRITE;
/*!40000 ALTER TABLE `direct_debit_history_status` DISABLE KEYS */;

INSERT INTO `direct_debit_history_status` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('4','UNKNOWN_ERROR','ER',NULL,'2','2014-12-04 15:59:11.111014',NULL,'2015-04-02 15:21:05.332340','1','0'),
('3','SLOT_LIMIT_EXCEEDED','LE',NULL,'2','2014-12-04 15:59:11.111014',NULL,'2015-04-02 15:21:05.332340','1','0'),
('2','PAYMENT_FAILURE','PF',NULL,'2','2014-12-04 15:59:11.111014',NULL,'2015-04-02 15:21:05.332340','1','0'),
('1','SUCCESSFUL','S',NULL,'2','2014-12-04 15:59:11.111014',NULL,'2015-04-02 15:21:05.332340','1','0');

/*!40000 ALTER TABLE `direct_debit_history_status` ENABLE KEYS */;
UNLOCK TABLES;
