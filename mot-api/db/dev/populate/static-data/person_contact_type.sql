LOCK TABLES `person_contact_type` WRITE;
/*!40000 ALTER TABLE `person_contact_type` DISABLE KEYS */;

INSERT INTO `person_contact_type` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('2','WORK','WORK',NULL,'1','2014-12-04 15:59:09.660822',NULL,NULL,'1','0'),
('1','PERSONAL','PRSNL',NULL,'1','2014-12-04 15:59:09.660822',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `person_contact_type` ENABLE KEYS */;
UNLOCK TABLES;
