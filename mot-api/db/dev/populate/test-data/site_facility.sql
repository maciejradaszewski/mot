LOCK TABLES `site_facility` WRITE;
/*!40000 ALTER TABLE `site_facility` DISABLE KEYS */;

INSERT INTO `site_facility` (`id`, `site_id`, `facility_type_id`, `name`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('10','17','1','Automated Line D',NULL,'1','2014-12-05 11:56:52.308083',NULL,NULL,'1','0'),
('9','17','1','Automated Line C',NULL,'1','2014-12-05 11:56:52.308083',NULL,NULL,'1','0'),
('8','17','1','Automated Line A',NULL,'1','2014-12-05 11:56:52.308083',NULL,NULL,'1','0'),
('7','3','3','mot line D',NULL,'1','2014-12-05 11:56:52.308083',NULL,NULL,'1','0'),
('6','3','3','mot line C',NULL,'1','2014-12-05 11:56:52.308083',NULL,NULL,'1','0'),
('5','3','2','mot line B',NULL,'1','2014-12-05 11:56:52.308083',NULL,NULL,'1','0'),
('4','3','1','mot line A',NULL,'1','2014-12-05 11:56:52.308083',NULL,NULL,'1','0'),
('3','2','2','mot line 1',NULL,'1','2014-12-05 11:56:52.308083',NULL,NULL,'1','0'),
('2','1','3','line b',NULL,'1','2014-12-05 11:56:52.308083',NULL,NULL,'1','0'),
('1','1','1','line a',NULL,'1','2014-12-05 11:56:52.308083',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `site_facility` ENABLE KEYS */;
UNLOCK TABLES;
