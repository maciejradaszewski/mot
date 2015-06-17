LOCK TABLES `enforcement_decision_category_lookup` WRITE;
/*!40000 ALTER TABLE `enforcement_decision_category_lookup` DISABLE KEYS */;

INSERT INTO `enforcement_decision_category_lookup` (`id`, `category`, `display_order`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('4','Inspection notice','4',NULL,NULL,'1','2014-12-04 15:59:09.691502',NULL,NULL,'1','0'),
('3','Delayed','3',NULL,NULL,'1','2014-12-04 15:59:09.691502',NULL,NULL,'1','0'),
('2','Immediate','2',NULL,NULL,'1','2014-12-04 15:59:09.691502',NULL,NULL,'1','0'),
('1','Not applicable','1',NULL,NULL,'1','2014-12-04 15:59:09.691502',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `enforcement_decision_category_lookup` ENABLE KEYS */;
UNLOCK TABLES;
