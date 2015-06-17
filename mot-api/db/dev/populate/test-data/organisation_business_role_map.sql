LOCK TABLES `organisation_business_role_map` WRITE;
/*!40000 ALTER TABLE `organisation_business_role_map` DISABLE KEYS */;

INSERT INTO `organisation_business_role_map` (`id`, `organisation_id`, `business_role_id`, `person_id`, `status_id`, `status_changed_on`, `valid_from`, `expiry_date`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('32','10','1','2105','1',NULL,NULL,NULL,NULL,'1','2014-12-05 11:57:01.930424',NULL,NULL,'1','0'),
('15','2001','1','2142','1',NULL,NULL,NULL,NULL,'1','2014-12-05 11:57:01.434162',NULL,NULL,'1','0'),
('14','10','1','2141','1',NULL,NULL,NULL,NULL,'1','2014-12-05 11:57:01.434162',NULL,NULL,'1','0'),
('13','2001','1','2140','1',NULL,NULL,NULL,NULL,'1','2014-12-05 11:57:01.434162',NULL,NULL,'1','0'),
('12','10','1','2139','1',NULL,NULL,NULL,NULL,'1','2014-12-05 11:57:01.434162',NULL,NULL,'1','0'),
('11','13','1','41','1',NULL,NULL,NULL,NULL,'1','2014-12-05 11:57:01.434162',NULL,NULL,'1','0'),
('10','12','1','29','1',NULL,NULL,NULL,NULL,'1','2014-12-05 11:57:01.434162',NULL,NULL,'1','0'),
('9','10','1','29','1',NULL,NULL,NULL,NULL,'1','2014-12-05 11:57:01.434162',NULL,NULL,'1','0'),
('8','9','1','29','1',NULL,NULL,NULL,NULL,'1','2014-12-05 11:57:01.434162',NULL,NULL,'1','0'),
('7','3','1','101','1',NULL,NULL,NULL,NULL,'1','2014-12-05 11:57:01.434162',NULL,NULL,'1','0'),
('6','2','1','101','1',NULL,NULL,NULL,NULL,'1','2014-12-05 11:57:01.434162',NULL,NULL,'1','0'),
('5','13','2','44','1',NULL,NULL,NULL,NULL,'1','2014-12-05 11:57:01.434162',NULL,NULL,'1','0'),
('4','2','2','16','1',NULL,NULL,NULL,NULL,'1','2014-12-05 11:57:01.434162',NULL,NULL,'1','0'),
('3','9','2','31','1',NULL,NULL,NULL,NULL,'1','2014-12-05 11:57:01.434162',NULL,NULL,'1','0'),
('2','9','2','30','1',NULL,NULL,NULL,NULL,'1','2014-12-05 11:57:01.434162',NULL,NULL,'1','0'),
('1','9','2','29','1',NULL,NULL,NULL,NULL,'1','2014-12-05 11:57:01.434162',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `organisation_business_role_map` ENABLE KEYS */;
UNLOCK TABLES;
