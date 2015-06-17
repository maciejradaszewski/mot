LOCK TABLES `transition_status` WRITE;
/*!40000 ALTER TABLE `transition_status` DISABLE KEYS */;

INSERT INTO `transition_status` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('9','Live','LIVE',NULL,'1','2015-02-17 10:23:37.836146',NULL,'2015-04-02 15:21:05.176200','1','0'),
('8','In transition','INT',NULL,'1','2015-02-17 10:23:37.836146',NULL,'2015-04-02 15:21:05.176200','1','0'),
('7','Revalidated','REV',NULL,'1','2015-02-17 10:23:37.836146',NULL,'2015-04-02 15:21:05.176200','1','0'),
('6','Submitted','SUB',NULL,'1','2015-02-17 10:23:37.836146',NULL,'2015-04-02 15:21:05.176200','1','0'),
('5','Validated','VALID',NULL,'1','2015-02-17 10:23:37.836146',NULL,'2015-04-02 15:21:05.176200','1','0'),
('4','Ready','READY',NULL,'1','2015-02-17 10:23:37.836146',NULL,'2015-04-02 15:21:05.176200','1','0'),
('3','Held','HELD',NULL,'1','2015-02-17 10:23:37.836146',NULL,'2015-04-02 15:21:05.176200','1','0'),
('2','Initial contact made','ICM',NULL,'1','2015-02-17 10:23:37.836146',NULL,'2015-04-02 15:21:05.176200','1','0'),
('1','Not Started','NS',NULL,'1','2015-02-17 10:23:37.836146',NULL,'2015-04-02 15:21:05.176200','1','0'),
('0','Unknown','UNKN',NULL,'1','2015-02-17 10:23:37.836146',NULL,'2015-04-02 15:21:05.176200','1','0');

/*!40000 ALTER TABLE `transition_status` ENABLE KEYS */;
UNLOCK TABLES;
