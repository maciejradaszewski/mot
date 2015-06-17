LOCK TABLES `payment_type` WRITE;
/*!40000 ALTER TABLE `payment_type` DISABLE KEYS */;

INSERT INTO `payment_type` (`id`, `type_name`, `active`, `display_order`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('5','Postal Order','0','5',NULL,'2','2014-12-04 15:59:11.086290',NULL,'2015-04-02 15:21:05.369441','1','0'),
('4','Cheque','0','4',NULL,'2','2014-12-04 15:59:11.086290',NULL,'2015-04-02 15:21:05.369441','1','0'),
('3','Cash','0','3',NULL,'2','2014-12-04 15:59:11.086290',NULL,'2015-04-02 15:21:05.369441','1','0'),
('2','Direct Debit','1','2',NULL,'2','2014-12-04 15:59:11.086290',NULL,'2015-04-02 15:21:05.369441','1','0'),
('1','Card','1','1',NULL,'2','2014-12-04 15:59:11.086290',NULL,'2015-04-02 15:21:05.369441','1','0');

/*!40000 ALTER TABLE `payment_type` ENABLE KEYS */;
UNLOCK TABLES;
