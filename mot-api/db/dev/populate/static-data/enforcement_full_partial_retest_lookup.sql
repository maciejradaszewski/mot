LOCK TABLES `enforcement_full_partial_retest_lookup` WRITE;
/*!40000 ALTER TABLE `enforcement_full_partial_retest_lookup` DISABLE KEYS */;

INSERT INTO `enforcement_full_partial_retest_lookup` (`id`, `name`, `description`, `code`, `display_order`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('2','Partial','Partial',NULL,'2',NULL,'1','2014-12-04 15:59:09.708554',NULL,NULL,'1','0'),
('1','Full','Full',NULL,'1',NULL,'1','2014-12-04 15:59:09.708554',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `enforcement_full_partial_retest_lookup` ENABLE KEYS */;
UNLOCK TABLES;
