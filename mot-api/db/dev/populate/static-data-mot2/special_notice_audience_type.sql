LOCK TABLES `special_notice_audience_type` WRITE;
/*!40000 ALTER TABLE `special_notice_audience_type` DISABLE KEYS */;

INSERT INTO `special_notice_audience_type` (`id`, `code`, `name`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('3','test','tester audience',NULL,'2','2014-12-04 15:59:13.000000',NULL,'2015-04-02 15:21:05.328903','1','0'),
('2','vts','vts audience',NULL,'2','2014-12-04 15:59:13.000000',NULL,'2015-04-02 15:21:05.328903','1','0'),
('1','dvsa','dvsa audience',NULL,'2','2014-12-04 15:59:13.000000',NULL,'2015-04-02 15:21:05.328903','1','0');

/*!40000 ALTER TABLE `special_notice_audience_type` ENABLE KEYS */;
UNLOCK TABLES;
