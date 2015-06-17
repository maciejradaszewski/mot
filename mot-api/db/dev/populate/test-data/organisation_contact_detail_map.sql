LOCK TABLES `organisation_contact_detail_map` WRITE;
/*!40000 ALTER TABLE `organisation_contact_detail_map` DISABLE KEYS */;

INSERT INTO `organisation_contact_detail_map` (`id`, `organisation_id`, `contact_detail_id`, `organisation_contact_type_id`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('3','13','14','1',NULL,'1','2014-12-05 11:56:52.230322',NULL,NULL,'1','0'),
('1','9','9','1',NULL,'1','2014-12-05 11:56:52.230322',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `organisation_contact_detail_map` ENABLE KEYS */;
UNLOCK TABLES;
