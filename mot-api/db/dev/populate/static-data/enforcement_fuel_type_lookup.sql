LOCK TABLES `enforcement_fuel_type_lookup` WRITE;
/*!40000 ALTER TABLE `enforcement_fuel_type_lookup` DISABLE KEYS */;

INSERT INTO `enforcement_fuel_type_lookup` (`id`, `description`, `display_order`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('3','Both','3',NULL,'1','2014-12-04 15:59:09.722791',NULL,NULL,'1','0'),
('2','Diesel','2',NULL,'1','2014-12-04 15:59:09.722791',NULL,NULL,'1','0'),
('1','Catalyst','1',NULL,'1','2014-12-04 15:59:09.722791',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `enforcement_fuel_type_lookup` ENABLE KEYS */;
UNLOCK TABLES;
