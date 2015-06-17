LOCK TABLES `auth_for_ae_status` WRITE;
/*!40000 ALTER TABLE `auth_for_ae_status` DISABLE KEYS */;

INSERT INTO `auth_for_ae_status` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('12','Retracted','RTRCT','RE','1','2015-02-17 10:23:36.056626',NULL,NULL,'1','0'),
('6','Rejected','RJCTD','RJ','1','2015-02-17 10:23:36.056626',NULL,NULL,'1','0'),
('5','Surrendered','SRNDR','SR','1','2015-02-17 10:23:36.056626',NULL,NULL,'1','0'),
('4','Withdrawn','WDRWN','WD','1','2015-02-17 10:23:36.056626',NULL,NULL,'1','0'),
('3','Lapsed','LPSD','LA','1','2015-02-17 10:23:36.056626',NULL,NULL,'1','0'),
('2','Approved','APRVD','AV','1','2015-02-17 10:23:36.056626',NULL,NULL,'1','0'),
('1','Applied','APPLD','AP','1','2015-02-17 10:23:36.056626',NULL,NULL,'1','0'),
('0','Unknown','UNKN',NULL,'1','2015-02-17 10:23:36.056626',NULL,'2015-02-17 10:23:36.058298','1','0');

/*!40000 ALTER TABLE `auth_for_ae_status` ENABLE KEYS */;
UNLOCK TABLES;
