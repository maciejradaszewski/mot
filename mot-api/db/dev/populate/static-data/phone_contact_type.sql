LOCK TABLES `phone_contact_type` WRITE;
/*!40000 ALTER TABLE `phone_contact_type` DISABLE KEYS */;

INSERT INTO `phone_contact_type` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('3','Fax','FAX',NULL,'1','2015-02-17 10:23:36.870540',NULL,NULL,'1','1'),
('2','Business','BUS',NULL,'1','2015-02-17 10:23:36.866286',NULL,NULL,'1','1'),
('1','Personal','PERS',NULL,'1','2015-02-17 10:23:36.864271',NULL,NULL,'1','1');

/*!40000 ALTER TABLE `phone_contact_type` ENABLE KEYS */;
UNLOCK TABLES;
