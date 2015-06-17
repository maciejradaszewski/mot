LOCK TABLES `equipment_make` WRITE;
/*!40000 ALTER TABLE `equipment_make` DISABLE KEYS */;

INSERT INTO `equipment_make` (`id`, `code`, `name`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('9','9','VOSA',NULL,'1','2014-12-04 15:59:10.548886',NULL,NULL,'1','0'),
('8','8','ATT',NULL,'1','2014-12-04 15:59:10.548886',NULL,NULL,'1','0'),
('7','7','FKI CRYPTON',NULL,'1','2014-12-04 15:59:10.548886',NULL,NULL,'1','0'),
('6','6','TURNKEY INSTRUMENTS',NULL,'1','2014-12-04 15:59:10.548886',NULL,NULL,'1','0'),
('5','5','BALCO',NULL,'1','2014-12-04 15:59:10.548886',NULL,NULL,'1','0'),
('4','4','AUTOSENSE',NULL,'1','2014-12-04 15:59:10.548886',NULL,NULL,'1','0'),
('3','3','ANREW',NULL,'1','2014-12-04 15:59:10.548886',NULL,NULL,'1','0'),
('2','2','ANALIZE UK',NULL,'1','2014-12-04 15:59:10.548886',NULL,NULL,'1','0'),
('1','1','ALLEN',NULL,'1','2014-12-04 15:59:10.548886',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `equipment_make` ENABLE KEYS */;
UNLOCK TABLES;
