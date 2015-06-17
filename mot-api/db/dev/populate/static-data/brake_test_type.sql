LOCK TABLES `brake_test_type` WRITE;
/*!40000 ALTER TABLE `brake_test_type` DISABLE KEYS */;

INSERT INTO `brake_test_type` (`id`, `name`, `description`, `code`, `display_order`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('5','roller','','ROLLR','5',NULL,'1','2014-12-04 15:59:09.593971',NULL,'2015-03-10 17:47:34.043287','1','0'),
('4','plate','','PLATE','4',NULL,'1','2014-12-04 15:59:09.593971',NULL,'2015-02-17 10:23:30.220580','1','0'),
('3','gradient','','GRADT','3',NULL,'1','2014-12-04 15:59:09.593971',NULL,'2015-02-17 10:23:30.219400','1','0'),
('2','floor','','FLOOR','2',NULL,'1','2014-12-04 15:59:09.593971',NULL,'2015-02-17 10:23:30.218267','1','0'),
('1','decelerometer','','DECEL','1',NULL,'1','2014-12-04 15:59:09.593971',NULL,'2015-02-17 10:23:30.216715','1','0');

/*!40000 ALTER TABLE `brake_test_type` ENABLE KEYS */;
UNLOCK TABLES;
