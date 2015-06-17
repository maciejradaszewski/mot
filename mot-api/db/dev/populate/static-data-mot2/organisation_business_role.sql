LOCK TABLES `organisation_business_role` WRITE;
/*!40000 ALTER TABLE `organisation_business_role` DISABLE KEYS */;

INSERT INTO `organisation_business_role` (`id`, `name`, `description`, `code`, `organisation_type_id`, `role_id`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('4','DVSA-SCHEME-MANAGEMENT','DVSA Scheme Management','DSM',NULL,'10',NULL,'2','2014-12-04 15:59:18.474723',NULL,'2015-04-02 15:21:05.317711','1','0'),
('3','AUTHORISED-EXAMINER-PRINCIPAL','Authorised Examiner Principal','AEP',NULL,NULL,NULL,'2','2014-12-04 15:59:18.474723',NULL,'2015-04-02 15:21:05.317711','1','0'),
('2','AUTHORISED-EXAMINER-DELEGATE','Authorised Examiner Delegate','AED',NULL,'4',NULL,'2','2014-12-04 15:59:18.474723',NULL,'2015-04-02 15:21:05.317711','1','0'),
('1','AUTHORISED-EXAMINER-DESIGNATED-MANAGER','Authorised Examiner Designated Manager','AEDM',NULL,'5',NULL,'2','2014-12-04 15:59:18.474723',NULL,'2015-04-02 15:21:05.317711','1','0');

/*!40000 ALTER TABLE `organisation_business_role` ENABLE KEYS */;
UNLOCK TABLES;
