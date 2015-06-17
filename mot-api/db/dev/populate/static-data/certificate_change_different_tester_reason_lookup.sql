LOCK TABLES `certificate_change_different_tester_reason_lookup` WRITE;
/*!40000 ALTER TABLE `certificate_change_different_tester_reason_lookup` DISABLE KEYS */;

INSERT INTO `certificate_change_different_tester_reason_lookup` (`id`, `code`, `description`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('5','LEFT','Original NT Unavailable - Left VTS',NULL,'1','2014-12-04 15:59:09.629036',NULL,NULL,'1','0'),
('4','WOEL','Original NT Unavailable - working elsewhere',NULL,'1','2014-12-04 15:59:09.629036',NULL,NULL,'1','0'),
('3','UKNO','Original NT Unavailable - Reason unknown',NULL,'1','2014-12-04 15:59:09.629036',NULL,NULL,'1','0'),
('2','ILLN','Original NT Unavailable - Illness',NULL,'1','2014-12-04 15:59:09.629036',NULL,NULL,'1','0'),
('1','HOLY','Original NT Unavailable - Holiday',NULL,'1','2014-12-04 15:59:09.629036',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `certificate_change_different_tester_reason_lookup` ENABLE KEYS */;
UNLOCK TABLES;
