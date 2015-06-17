LOCK TABLES `auth_status` WRITE;
/*!40000 ALTER TABLE `auth_status` DISABLE KEYS */;

INSERT INTO `auth_status` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('13','Extinct','EXNCT','EX','1','2015-02-17 10:23:36.254650',NULL,NULL,'1','0'),
('12','Retracted','RTRCT','RE','1','2015-02-17 10:23:36.254650',NULL,NULL,'1','0'),
('11','Suspended','SPND','SP','1','2015-02-17 10:23:36.254650',NULL,NULL,'1','0'),
('10','Refresher Needed','RFSHN','RS','1','2015-02-17 10:23:36.254650',NULL,NULL,'1','0'),
('9','Qualified','QLFD','QF','1','2015-02-17 10:23:36.254650',NULL,NULL,'1','0'),
('8','Demo Test Needed','DMTN','DT','1','2015-02-17 10:23:36.254650',NULL,NULL,'1','0'),
('7','Initial Training Needed','ITRN','IT','1','2015-02-17 10:23:36.254650',NULL,NULL,'1','0'),
('6','Rejected','RJCTD','RJ','1','2015-02-17 10:23:36.254650',NULL,NULL,'1','0'),
('5','Surrendered','SRNDR','SR','1','2015-02-17 10:23:36.254650',NULL,NULL,'1','0'),
('4','Withdrawn','WDRWN','WD','1','2015-02-17 10:23:36.254650',NULL,NULL,'1','0'),
('3','Lapsed','LPSD','LA','1','2015-02-17 10:23:36.254650',NULL,NULL,'1','0'),
('2','Approved','APRVD','AV','1','2015-02-17 10:23:36.254650',NULL,NULL,'1','0'),
('1','Applied','APPLD','AP','1','2015-02-17 10:23:36.254650',NULL,NULL,'1','0'),
('0','Unknown','UNKN',NULL,'1','2015-02-17 10:23:36.254650',NULL,'2015-02-17 10:23:36.261846','1','0');

/*!40000 ALTER TABLE `auth_status` ENABLE KEYS */;
UNLOCK TABLES;
