LOCK TABLES `auth_for_ae_person_as_principal_map` WRITE;
/*!40000 ALTER TABLE `auth_for_ae_person_as_principal_map` DISABLE KEYS */;

INSERT INTO `auth_for_ae_person_as_principal_map` (`id`, `person_id`, `auth_for_ae_id`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('4','46','7',NULL,'1','2014-12-05 11:56:52.226991',NULL,NULL,'1','0'),
('3','45','7',NULL,'1','2014-12-05 11:56:52.226991',NULL,NULL,'1','0'),
('2','102','3',NULL,'1','2014-12-05 11:56:52.226991',NULL,'2015-04-02 15:21:05.040155','1','0'),
('1','101','3',NULL,'1','2014-12-05 11:56:52.226991',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `auth_for_ae_person_as_principal_map` ENABLE KEYS */;
UNLOCK TABLES;
