LOCK TABLES `dvla_model` WRITE;
/*!40000 ALTER TABLE `dvla_model` DISABLE KEYS */;

INSERT INTO `dvla_model` (`id`, `code`, `name`, `make_code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number`) VALUES
('104257','01516','A4','18801',NULL,'1','2015-04-02 15:21:04.204841',NULL,NULL,'1','0'),
('106782','01163','CLIO','1889A',NULL,'1','2015-04-02 15:21:04.204841',NULL,NULL,'1','0'),
('105513','0119E','I30','1884Z',NULL,'1','2015-04-02 15:21:04.204841',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `dvla_model` ENABLE KEYS */;
UNLOCK TABLES;
