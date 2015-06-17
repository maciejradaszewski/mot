LOCK TABLES `organisation_site_status` WRITE;
/*!40000 ALTER TABLE `organisation_site_status` DISABLE KEYS */;

INSERT INTO `organisation_site_status` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('8','Extinct','EX','EX','1','2015-02-23 16:37:41.370294',NULL,NULL,'1','1'),
('7','Retracted','RE','RE','1','2015-02-23 16:37:41.369853',NULL,NULL,'1','1'),
('6','Suspended','SP','SP','1','2015-02-23 16:37:41.369413',NULL,NULL,'1','1'),
('5','Rejected','RJ','RJ','1','2015-02-23 16:37:41.368975',NULL,NULL,'1','1'),
('4','Surrendered','SR','SR','1','2015-02-23 16:37:41.368493',NULL,NULL,'1','1'),
('3','Withdrawn','WD','WD','1','2015-02-23 16:37:41.367987',NULL,NULL,'1','1'),
('2','Active','AC','AC','1','2015-02-23 16:37:41.350469',NULL,NULL,'1','1'),
('1','Applied','AP','AP','1','2015-02-23 16:37:41.349575',NULL,NULL,'1','1'),
('0','Unknown','UNKN',NULL,'1','2015-02-23 16:37:41.370686',NULL,NULL,'1','1');

/*!40000 ALTER TABLE `organisation_site_status` ENABLE KEYS */;
UNLOCK TABLES;
