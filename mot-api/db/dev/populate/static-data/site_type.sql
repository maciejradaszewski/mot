LOCK TABLES `site_type` WRITE;
/*!40000 ALTER TABLE `site_type` DISABLE KEYS */;

INSERT INTO `site_type` (`id`, `code`, `name`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('10','OFFST','Offsite',NULL,'1','2014-12-04 15:59:09.626637',NULL,'2015-04-02 15:21:02.921195','1','0'),
('9','CRSVN','Course Venue',NULL,'1','2014-12-04 15:59:09.626637',NULL,'2015-04-02 15:21:02.919651','1','0'),
('7','HO','Head Office',NULL,'1','2014-12-04 15:59:09.626637',NULL,'2015-04-02 15:21:02.906182','1','0'),
('6','SRVDK','Service Desk',NULL,'1','2014-12-04 15:59:09.626637',NULL,'2015-04-02 15:21:02.918381','1','0'),
('5','GVTS','Goods Vehicle Testing Station',NULL,'1','2014-12-04 15:59:09.626637',NULL,'2015-04-02 15:21:02.916533','1','0'),
('4','VRO','Vehicle Records Office',NULL,'1','2014-12-04 15:59:09.626637',NULL,'2015-04-02 15:21:02.915054','1','0'),
('3','VTS','Vehicle Testing Station',NULL,'1','2014-12-04 15:59:09.626637',NULL,'2015-04-02 15:21:02.913388','1','0'),
('2','CTC','Contracted Training Centre',NULL,'1','2014-12-04 15:59:09.626637',NULL,'2015-04-02 15:21:02.911943','1','0'),
('1','AO','Area Office',NULL,'1','2014-12-04 15:59:09.626637',NULL,'2015-04-02 15:21:02.909374','1','0');

/*!40000 ALTER TABLE `site_type` ENABLE KEYS */;
UNLOCK TABLES;
