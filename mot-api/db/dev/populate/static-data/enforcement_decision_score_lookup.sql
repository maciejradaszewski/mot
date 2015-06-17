LOCK TABLES `enforcement_decision_score_lookup` WRITE;
/*!40000 ALTER TABLE `enforcement_decision_score_lookup` DISABLE KEYS */;

INSERT INTO `enforcement_decision_score_lookup` (`id`, `score`, `description`, `display_order`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('9','40','Risk of injury missed','9',NULL,NULL,'1','2014-12-04 15:59:09.701398',NULL,NULL,'1','0'),
('8','30','Exs. corr/wear/damage missed','8',NULL,NULL,'1','2014-12-04 15:59:09.701398',NULL,NULL,'1','0'),
('7','20','Not testable','7',NULL,NULL,'1','2014-12-04 15:59:09.701398',NULL,NULL,'1','0'),
('6','20','Other defect missed','6',NULL,NULL,'1','2014-12-04 15:59:09.701398',NULL,NULL,'1','0'),
('5','20','No defect','5',NULL,NULL,'1','2014-12-04 15:59:09.701398',NULL,NULL,'1','0'),
('4','10','Significantly wrong','4',NULL,NULL,'1','2014-12-04 15:59:09.701398',NULL,NULL,'1','0'),
('3','5','Obviously wrong','3',NULL,NULL,'1','2014-12-04 15:59:09.701398',NULL,NULL,'1','0'),
('2','0','Overruled, marginally wrong','2',NULL,NULL,'1','2014-12-04 15:59:09.701398',NULL,NULL,'1','0'),
('1',NULL,'Disregard','1',NULL,NULL,'1','2014-12-04 15:59:09.701398',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `enforcement_decision_score_lookup` ENABLE KEYS */;
UNLOCK TABLES;
