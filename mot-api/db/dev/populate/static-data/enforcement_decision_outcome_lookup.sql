LOCK TABLES `enforcement_decision_outcome_lookup` WRITE;
/*!40000 ALTER TABLE `enforcement_decision_outcome_lookup` DISABLE KEYS */;

INSERT INTO `enforcement_decision_outcome_lookup` (`id`, `outcome`, `display_order`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('3','Disciplinary action report','3',NULL,NULL,'1','2014-12-04 15:59:09.697840',NULL,NULL,'1','0'),
('2','Advisory warning letter','2',NULL,NULL,'1','2014-12-04 15:59:09.697840',NULL,NULL,'1','0'),
('1','No further action','1',NULL,NULL,'1','2014-12-04 15:59:09.697840',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `enforcement_decision_outcome_lookup` ENABLE KEYS */;
UNLOCK TABLES;
