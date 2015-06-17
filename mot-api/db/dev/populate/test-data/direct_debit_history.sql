LOCK TABLES `direct_debit_history` WRITE;
/*!40000 ALTER TABLE `direct_debit_history` DISABLE KEYS */;

INSERT INTO `direct_debit_history` (`id`, `direct_debit_id`, `transaction_id`, `status_id`, `increment_date`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('2','1',NULL,'3','2014-10-05 00:00:00',NULL,'0','2014-12-05 11:56:52.713262',NULL,NULL,'1','0'),
('1','1',NULL,'2','2014-11-05 00:00:00',NULL,'0','2014-12-05 11:56:52.713262',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `direct_debit_history` ENABLE KEYS */;
UNLOCK TABLES;
