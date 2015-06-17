LOCK TABLES `enforcement_visit_outcome_lookup` WRITE;
/*!40000 ALTER TABLE `enforcement_visit_outcome_lookup` DISABLE KEYS */;

INSERT INTO `enforcement_visit_outcome_lookup` (`id`, `description`, `display_order`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('3','Abandoned','3',NULL,NULL,'1','2014-12-04 15:59:09.712074',NULL,NULL,'1','0'),
('2','Shortcomings found','2',NULL,NULL,'1','2014-12-04 15:59:09.712074',NULL,NULL,'1','0'),
('1','Satisfactory','1',NULL,NULL,'1','2014-12-04 15:59:09.712074',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `enforcement_visit_outcome_lookup` ENABLE KEYS */;
UNLOCK TABLES;
