LOCK TABLES `title` WRITE;
/*!40000 ALTER TABLE `title` DISABLE KEYS */;

INSERT INTO `title` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('13','Chamberlain','CHAMB',NULL,'1','2014-12-17 14:18:02.759151',NULL,NULL,'1','1'),
('12','Baroness','BRNSS',NULL,'1','2014-12-17 14:18:02.758737',NULL,NULL,'1','1'),
('11','Baron','BARON',NULL,'1','2014-12-17 14:18:02.758271',NULL,NULL,'1','1'),
('10','Lady','LADY',NULL,'1','2014-12-17 14:18:02.757868',NULL,NULL,'1','1'),
('9','Lord','LORD',NULL,'1','2014-12-17 14:18:02.757461',NULL,NULL,'1','1'),
('8','Sir','SIR',NULL,'1','2014-12-17 14:18:02.757049',NULL,NULL,'1','1'),
('7','Professor','PROF',NULL,'1','2014-12-17 14:18:02.756640',NULL,NULL,'1','1'),
('6','Reverend','REV',NULL,'1','2014-12-17 14:18:02.756193',NULL,NULL,'1','1'),
('5','Dr','DR',NULL,'1','2014-12-17 14:18:02.755745',NULL,NULL,'1','1'),
('4','Ms','MS',NULL,'1','2014-12-17 14:18:02.755197',NULL,NULL,'1','1'),
('3','Miss','MISS',NULL,'1','2014-12-17 14:18:02.754449',NULL,NULL,'1','1'),
('2','Mrs','MRS',NULL,'1','2014-12-17 14:18:02.753876',NULL,NULL,'1','1'),
('1','Mr','MR',NULL,'1','2014-12-17 14:18:02.752892',NULL,NULL,'1','1'),
('0','Unknown','UNKN',NULL,'1','2014-12-17 14:18:02.759611',NULL,'2015-03-10 17:47:34.861501','1','1');

/*!40000 ALTER TABLE `title` ENABLE KEYS */;
UNLOCK TABLES;
