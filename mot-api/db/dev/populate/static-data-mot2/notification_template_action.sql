LOCK TABLES `notification_template_action` WRITE;
/*!40000 ALTER TABLE `notification_template_action` DISABLE KEYS */;

INSERT INTO `notification_template_action` (`id`, `notification_template_id`, `action_id`, `label`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('4','7','4','Reject',NULL,'2','2014-12-04 15:59:10.860304',NULL,'2015-04-02 15:21:05.315493','1','0'),
('3','7','3','Accept',NULL,'2','2014-12-04 15:59:10.860304',NULL,'2015-04-02 15:21:05.315493','1','0'),
('2','5','2','Reject',NULL,'2','2014-12-04 15:59:10.860304',NULL,'2015-04-02 15:21:05.315493','1','0'),
('1','5','1','Accept',NULL,'2','2014-12-04 15:59:10.860304',NULL,'2015-04-02 15:21:05.315493','1','0');

/*!40000 ALTER TABLE `notification_template_action` ENABLE KEYS */;
UNLOCK TABLES;
