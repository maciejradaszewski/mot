LOCK TABLES `test_slot_transaction_status` WRITE;
/*!40000 ALTER TABLE `test_slot_transaction_status` DISABLE KEYS */;

INSERT INTO `test_slot_transaction_status` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('2','COMPLETE','CO',NULL,'2','2014-12-04 15:59:11.103026',NULL,'2015-04-02 15:21:05.371501','1','0'),
('1','CREATED','CR',NULL,'2','2014-12-04 15:59:11.103026',NULL,'2015-04-02 15:21:05.371501','1','0');

/*!40000 ALTER TABLE `test_slot_transaction_status` ENABLE KEYS */;
UNLOCK TABLES;
