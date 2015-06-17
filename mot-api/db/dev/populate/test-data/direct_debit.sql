LOCK TABLES `direct_debit` WRITE;
/*!40000 ALTER TABLE `direct_debit` DISABLE KEYS */;

INSERT INTO `direct_debit` (`id`, `organisation_id`, `person_id`, `status_id`, `mandate_id`, `slots`, `setup_date`, `next_collection_date`, `last_increment_date`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('2','10','29','3','1234567','250','2014-12-05 11:56:52','2014-11-30',NULL,NULL,'0','2014-12-05 11:56:52.709121',NULL,'2014-12-05 11:56:55.229631','1','0'),
('1','9','29','2','123456789','100','2014-12-05 11:56:52','2014-11-30',NULL,NULL,'0','2014-12-05 11:56:52.709121',NULL,'2014-12-05 11:56:55.231507','1','0');

/*!40000 ALTER TABLE `direct_debit` ENABLE KEYS */;
UNLOCK TABLES;
