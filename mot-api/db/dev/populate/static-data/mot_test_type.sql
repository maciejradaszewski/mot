LOCK TABLES `mot_test_type` WRITE;
/*!40000 ALTER TABLE `mot_test_type` DISABLE KEYS */;

INSERT INTO `mot_test_type` (`id`, `code`, `description`, `display_order`, `is_demo`, `is_slot_consuming`, `is_reinspection`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('12','EN','Non-Mot Test','12','0','0','0',NULL,'1','2014-12-04 15:59:09.613542',NULL,NULL,'1','0'),
('11','DR','Routine Demonstration Test','11','1','0','0',NULL,'1','2014-12-04 15:59:09.613542',NULL,NULL,'1','0'),
('10','DT','Demonstration Test following training','10','1','0','0',NULL,'1','2014-12-04 15:59:09.613542',NULL,NULL,'1','0'),
('9','RT','Re-Test','9','0','1','0',NULL,'1','2014-12-04 15:59:09.613542',NULL,NULL,'1','0'),
('8','OT','Other','8','0','0','1',NULL,'1','2014-12-04 15:59:09.613542',NULL,NULL,'1','0'),
('7','ES','Statutory Appeal','7','0','0','1',NULL,'1','2014-12-04 15:59:09.613542',NULL,NULL,'1','0'),
('6','EI','Inverted Appeal','6','0','0','1',NULL,'1','2014-12-04 15:59:09.613542',NULL,NULL,'1','0'),
('5','EC','MOT Compliance Survey','5','0','0','1',NULL,'1','2014-12-04 15:59:09.613542',NULL,NULL,'1','0'),
('4','ER','Targeted Reinspection','4','0','0','1',NULL,'1','2014-12-04 15:59:09.613542',NULL,NULL,'1','0'),
('3','PV','Partial Retest Repaired at VTS','3','0','1','0',NULL,'1','2014-12-04 15:59:09.613542',NULL,NULL,'1','0'),
('2','PL','Partial Retest Left VTS','2','0','1','0',NULL,'1','2014-12-04 15:59:09.613542',NULL,NULL,'1','0'),
('1','NT','Normal Test','1','0','1','0',NULL,'1','2014-12-04 15:59:09.613542',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `mot_test_type` ENABLE KEYS */;
UNLOCK TABLES;
