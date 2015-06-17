LOCK TABLES `visit_reason_lookup` WRITE;
/*!40000 ALTER TABLE `visit_reason_lookup` DISABLE KEYS */;

INSERT INTO `visit_reason_lookup` (`id`, `reason`, `display_order`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('2','Site approval visit','2',NULL,'1','2014-12-04 15:59:09.718364',NULL,NULL,'1','0'),
('1','Directed site visit','1',NULL,'1','2014-12-04 15:59:09.718364',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `visit_reason_lookup` ENABLE KEYS */;
UNLOCK TABLES;
