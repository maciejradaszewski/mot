LOCK TABLES `auth_for_testing_mot_at_site_status` WRITE;
/*!40000 ALTER TABLE `auth_for_testing_mot_at_site_status` DISABLE KEYS */;

INSERT INTO `auth_for_testing_mot_at_site_status` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('2','Approved','APRVD','AV','1','2015-02-17 10:23:36.331520',NULL,NULL,'1','0'),
('0','Unknown','UNKN',NULL,'1','2015-02-17 10:23:36.331520',NULL,'2015-02-17 10:23:36.332922','1','0');

/*!40000 ALTER TABLE `auth_for_testing_mot_at_site_status` ENABLE KEYS */;
UNLOCK TABLES;
