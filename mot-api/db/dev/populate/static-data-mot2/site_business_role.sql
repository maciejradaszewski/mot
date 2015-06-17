LOCK TABLES `site_business_role` WRITE;
/*!40000 ALTER TABLE `site_business_role` DISABLE KEYS */;

INSERT INTO `site_business_role` (`id`, `role_id`, `code`, `name`, `description`, `organisation_type_id`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('3','13','SITE-ADMIN','Site admin','Site Admin',NULL,NULL,'2','2014-12-04 15:59:18.493174',NULL,'2015-04-16 16:00:20.377492','1','0'),
('2','14','SITE-MANAGER','Site manager','Site Manager',NULL,NULL,'2','2014-12-04 15:59:18.493174',NULL,'2015-04-16 16:00:20.356832','1','0'),
('1','16','TESTER','Tester','Tester',NULL,NULL,'2','2014-12-04 15:59:18.493174',NULL,'2015-04-02 15:21:05.322202','1','0');

/*!40000 ALTER TABLE `site_business_role` ENABLE KEYS */;
UNLOCK TABLES;
