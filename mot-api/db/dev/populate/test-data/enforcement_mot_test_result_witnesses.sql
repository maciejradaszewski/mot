LOCK TABLES `enforcement_mot_test_result_witnesses` WRITE;
/*!40000 ALTER TABLE `enforcement_mot_test_result_witnesses` DISABLE KEYS */;

INSERT INTO `enforcement_mot_test_result_witnesses` (`id`, `name`, `position`, `enforcement_mot_test_result_id`, `type`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('1','Witness 1 Name','Witness 1 Position','1',NULL,NULL,'0','2015-02-17 10:23:18.001563',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `enforcement_mot_test_result_witnesses` ENABLE KEYS */;
UNLOCK TABLES;
