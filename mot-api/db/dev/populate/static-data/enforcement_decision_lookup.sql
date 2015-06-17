LOCK TABLES `enforcement_decision_lookup` WRITE;
/*!40000 ALTER TABLE `enforcement_decision_lookup` DISABLE KEYS */;

INSERT INTO `enforcement_decision_lookup` (`id`, `decision`, `display_order`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('3','Incorrect decision','3',NULL,NULL,'1','2014-12-04 15:59:09.695160',NULL,NULL,'1','0'),
('2','Defect missed','2',NULL,NULL,'1','2014-12-04 15:59:09.695160',NULL,NULL,'1','0'),
('1','Not applicable','1',NULL,NULL,'1','2014-12-04 15:59:09.695160',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `enforcement_decision_lookup` ENABLE KEYS */;
UNLOCK TABLES;
