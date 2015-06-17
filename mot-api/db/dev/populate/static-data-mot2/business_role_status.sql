LOCK TABLES `business_role_status` WRITE;
/*!40000 ALTER TABLE `business_role_status` DISABLE KEYS */;

INSERT INTO `business_role_status` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('7','Removed','RE',NULL,'2','2014-12-04 15:59:18.461094',NULL,'2015-04-02 15:21:05.289561','1','0'),
('6','Rejected','RJ',NULL,'2','2014-12-04 15:59:18.461094',NULL,'2015-04-02 15:21:05.289561','1','0'),
('5','Accepted','ACC',NULL,'2','2014-12-04 15:59:18.461094',NULL,'2015-04-02 15:21:05.289561','1','0'),
('4','Pending','PEND',NULL,'2','2014-12-04 15:59:18.461094',NULL,'2015-04-02 15:21:05.289561','1','0'),
('3','Disqualified','DI',NULL,'2','2014-12-04 15:59:18.461094',NULL,'2015-04-02 15:21:05.289561','1','0'),
('2','Inactive','IN',NULL,'2','2014-12-04 15:59:18.461094',NULL,'2015-04-02 15:21:05.289561','1','0'),
('1','Active','AC',NULL,'2','2014-12-04 15:59:18.461094',NULL,'2015-04-02 15:21:05.289561','1','0');

/*!40000 ALTER TABLE `business_role_status` ENABLE KEYS */;
UNLOCK TABLES;
