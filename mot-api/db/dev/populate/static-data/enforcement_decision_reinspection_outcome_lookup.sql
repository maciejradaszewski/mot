LOCK TABLES `enforcement_decision_reinspection_outcome_lookup` WRITE;
/*!40000 ALTER TABLE `enforcement_decision_reinspection_outcome_lookup` DISABLE KEYS */;

INSERT INTO `enforcement_decision_reinspection_outcome_lookup` (`id`, `decision`, `display_order`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('4','Other - enter details in section C','4',NULL,NULL,'1','2014-12-04 15:59:09.704780',NULL,NULL,'1','0'),
('3','Result incorrect','3',NULL,NULL,'1','2014-12-04 15:59:09.704780',NULL,NULL,'1','0'),
('2','Result correct but advisory warranted','2',NULL,NULL,'1','2014-12-04 15:59:09.704780',NULL,NULL,'1','0'),
('1','Agreed fully with test result','1',NULL,NULL,'1','2014-12-04 15:59:09.704780',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `enforcement_decision_reinspection_outcome_lookup` ENABLE KEYS */;
UNLOCK TABLES;
