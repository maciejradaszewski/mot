LOCK TABLES `auth_for_testing_mot_status` WRITE;
/*!40000 ALTER TABLE `auth_for_testing_mot_status` DISABLE KEYS */;

INSERT INTO `auth_for_testing_mot_status` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('11','Suspended','SPND','SP','1','2015-02-17 10:23:36.602667',NULL,NULL,'1','0'),
('10','Refresher Needed','RFSHN','RS','1','2015-02-17 10:23:36.602667',NULL,NULL,'1','0'),
('9','Qualified','QLFD','QF','1','2015-02-17 10:23:36.602667',NULL,NULL,'1','0'),
('8','Demo Test Needed','DMTN','DT','1','2015-02-17 10:23:36.602667',NULL,NULL,'1','0'),
('7','Initial Training Needed','ITRN','IT','1','2015-02-17 10:23:36.602667',NULL,NULL,'1','0'),
('0','Unknown','UNKN',NULL,'1','2015-02-17 10:23:36.602667',NULL,'2015-02-17 10:23:36.609517','1','0');

/*!40000 ALTER TABLE `auth_for_testing_mot_status` ENABLE KEYS */;
UNLOCK TABLES;
