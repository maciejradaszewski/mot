LOCK TABLES `equipment_model_status` WRITE;
/*!40000 ALTER TABLE `equipment_model_status` DISABLE KEYS */;

INSERT INTO `equipment_model_status` (`id`, `name`, `code`, `description`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('3','Withdrawn','WDRWN','The equipment is obsolete',NULL,'1','2014-12-04 15:59:09.000000',NULL,'2015-04-02 15:21:02.427286','1','0'),
('2','Not Installable','NINST','The equipment cannot be added to a VTS, but it\'s still valid (ATM used only for migration data)',NULL,'1','2014-12-04 15:59:09.000000',NULL,'2015-04-02 15:21:02.425557','1','0'),
('1','Approved','APRVD','The equipment can be added to a VTS',NULL,'1','2014-12-04 15:59:09.000000',NULL,'2015-04-02 15:21:02.424116','1','0');

/*!40000 ALTER TABLE `equipment_model_status` ENABLE KEYS */;
UNLOCK TABLES;
