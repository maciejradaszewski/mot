LOCK TABLES `facility_type` WRITE;
/*!40000 ALTER TABLE `facility_type` DISABLE KEYS */;

INSERT INTO `facility_type` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('3','Two Person Test Lane','TPTL',NULL,'1','2014-12-04 15:59:09.760717',NULL,NULL,'1','0'),
('2','One Person Test Lane','OPTL',NULL,'1','2014-12-04 15:59:09.760717',NULL,NULL,'1','0'),
('1','Automated Test Lane','ATL',NULL,'1','2014-12-04 15:59:09.760717',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `facility_type` ENABLE KEYS */;
UNLOCK TABLES;
