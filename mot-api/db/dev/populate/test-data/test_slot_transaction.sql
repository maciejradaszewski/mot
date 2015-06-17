LOCK TABLES `test_slot_transaction` WRITE;
/*!40000 ALTER TABLE `test_slot_transaction` DISABLE KEYS */;

INSERT INTO `test_slot_transaction` (`id`, `slots`, `slots_after`, `status_id`, `payment_id`, `state`, `sales_reference`, `organisation_id`, `completed_on`, `created`, `created_by_username`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('5','100','725','2','5','123456','MOT-20131231-784309AB','9','2015-03-25 12:43:21.000000','2015-03-25 12:40:10.000000','aedm',NULL,'1','2015-03-25 11:56:52.706010',NULL,NULL,'1','0'),
('4','1843','0','2','4','1234569','','9','2014-06-30 12:46:30.000000','2014-06-30 12:46:16.000000','aedm',NULL,'1','2014-12-05 11:56:52.706010',NULL,NULL,'1','0'),
('3','365','0','2','3','1234568','','9','2014-06-19 12:45:48.000000','2014-06-19 12:45:38.000000','aedm',NULL,'1','2014-12-05 11:56:52.706010',NULL,NULL,'1','0'),
('2','45','0','2','2','1234567','','9','2014-06-18 12:45:05.000000','2014-06-18 12:44:53.000000','aedm',NULL,'1','2014-12-05 11:56:52.706010',NULL,NULL,'1','0'),
('1','25','0','2','1','123456','','9','2013-12-31 12:43:21.000000','2013-12-31 12:43:10.000000','aedm',NULL,'1','2014-12-05 11:56:52.706010',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `test_slot_transaction` ENABLE KEYS */;
UNLOCK TABLES;
