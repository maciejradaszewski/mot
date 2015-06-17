LOCK TABLES `site_contact_type` WRITE;
/*!40000 ALTER TABLE `site_contact_type` DISABLE KEYS */;

INSERT INTO `site_contact_type` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('2','Business','BUS',NULL,'1','2014-12-08 11:05:18.275250',NULL,'2015-02-17 10:23:37.095621','1','0'),
('1','Correspondence','CORR',NULL,'1','2014-12-08 11:05:18.275250',NULL,'2015-02-17 10:23:37.093701','1','0');

/*!40000 ALTER TABLE `site_contact_type` ENABLE KEYS */;
UNLOCK TABLES;
