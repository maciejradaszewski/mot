LOCK TABLES `organisation_contact_type` WRITE;
/*!40000 ALTER TABLE `organisation_contact_type` DISABLE KEYS */;

INSERT INTO `organisation_contact_type` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('2','Correspondence','CORR',NULL,'1','2014-12-04 15:59:09.650083',NULL,'2015-02-17 10:23:37.241362','1','0'),
('1','Registered Company','REGC',NULL,'1','2014-12-04 15:59:09.650083',NULL,'2015-02-17 10:23:37.239109','1','0');

/*!40000 ALTER TABLE `organisation_contact_type` ENABLE KEYS */;
UNLOCK TABLES;
