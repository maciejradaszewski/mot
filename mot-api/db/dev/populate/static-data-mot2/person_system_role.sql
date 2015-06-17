LOCK TABLES `person_system_role` WRITE;
/*!40000 ALTER TABLE `person_system_role` DISABLE KEYS */;

INSERT INTO `person_system_role` (`id`, `name`, `full_name`, `short_name`, `role_id`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('11','DVSA-AREA-OFFICE-2','DVSA Area Admin 2','DAA2','28',NULL,'2','2015-02-17 10:23:33.848929','1','2015-04-02 15:21:05.319348','1','0'),
('10','DVLA-OPERATIVE','DVLA Operative','DO','27',NULL,'2','2014-12-04 15:59:18.468848',NULL,'2015-04-02 15:21:05.319348','1','0'),
('9','CRON','Cron User','CRON','7',NULL,'2','2014-12-04 15:59:18.468848',NULL,'2015-04-02 15:21:05.319348','1','0'),
('8','CUSTOMER-SERVICE-CENTRE-OPERATIVE','Customer Service Operative','CSO','25',NULL,'2','2014-12-04 15:59:18.468848',NULL,'2015-04-02 15:21:05.319348','1','0'),
('7','CUSTOMER-SERVICE-MANAGER','Customer Service Manager','CSM','24',NULL,'2','2014-12-04 15:59:18.468848',NULL,'2015-04-02 15:21:05.319348','1','0'),
('6','FINANCE','Finance','F','26',NULL,'2','2014-12-04 15:59:18.468848',NULL,'2015-04-02 15:21:05.319348','1','0'),
('5','DVSA-AREA-OFFICE-1','DVSA Area Admin','DAA','9',NULL,'2','2014-12-04 15:59:18.468848',NULL,'2015-04-02 15:21:05.319348','1','0'),
('4','DVSA-SCHEME-USER','DVSA Scheme User','DSU','11',NULL,'2','2014-12-04 15:59:18.468848',NULL,'2015-04-02 15:21:05.319348','1','0'),
('3','DVSA-SCHEME-MANAGEMENT','DVSA Scheme Management','DSM','10',NULL,'2','2014-12-04 15:59:18.468848',NULL,'2015-04-02 15:21:05.319348','1','0'),
('2','VEHICLE-EXAMINER','Vehicle Examiner','VE','23',NULL,'2','2014-12-04 15:59:18.468848',NULL,'2015-04-02 15:21:05.319348','1','0'),
('1','USER','User','User','22',NULL,'2','2014-12-04 15:59:18.468848',NULL,'2015-04-02 15:21:05.319348','1','0');

/*!40000 ALTER TABLE `person_system_role` ENABLE KEYS */;
UNLOCK TABLES;
