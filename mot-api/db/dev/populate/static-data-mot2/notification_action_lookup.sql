LOCK TABLES `notification_action_lookup` WRITE;
/*!40000 ALTER TABLE `notification_action_lookup` DISABLE KEYS */;

INSERT INTO `notification_action_lookup` (`id`, `action`, `display_order`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('4','ORGANISATION-NOMINATION-REJECTED','4','ONREJ',NULL,'2','2014-12-04 15:59:09.674108',NULL,'2015-04-02 15:21:05.301817','1','0'),
('3','ORGANISATION-NOMINATION-ACCEPTED','3','ONACC',NULL,'2','2014-12-04 15:59:09.674108',NULL,'2015-04-02 15:21:05.301817','1','0'),
('2','SITE-NOMINATION-REJECTED','2','SNREJ',NULL,'2','2014-12-04 15:59:09.674108',NULL,'2015-04-02 15:21:05.301817','1','0'),
('1','SITE-NOMINATION-ACCEPTED','1','SNACC',NULL,'2','2014-12-04 15:59:09.674108',NULL,'2015-04-02 15:21:05.301817','1','0');

/*!40000 ALTER TABLE `notification_action_lookup` ENABLE KEYS */;
UNLOCK TABLES;
