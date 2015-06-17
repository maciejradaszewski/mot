LOCK TABLES `jasper_template_type` WRITE;
/*!40000 ALTER TABLE `jasper_template_type` DISABLE KEYS */;

INSERT INTO `jasper_template_type` (`id`, `name`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('16','MOT-Advisory-Notice-Dual',NULL,'2','2014-12-04 15:59:09.731788',NULL,'2015-04-02 15:21:05.347707','1','0'),
('15','MOT-Fail-Certificate-Dual',NULL,'2','2014-12-04 15:59:09.731788',NULL,'2015-04-02 15:21:05.347707','1','0'),
('14','MOT-Pass-Certificate-Dual',NULL,'2','2014-12-04 15:59:09.731788',NULL,'2015-04-02 15:21:05.347707','1','0'),
('13','MOT-Inverted-Appeal',NULL,'2','2014-12-04 15:59:09.731788',NULL,'2015-04-02 15:21:05.347707','1','0'),
('12','MOT-Statutory-Appeal',NULL,'2','2014-12-04 15:59:09.731788',NULL,'2015-04-02 15:21:05.347707','1','0'),
('11','MOT-Advisory-Notice',NULL,'2','2014-12-04 15:59:09.731788',NULL,'2015-04-02 15:21:05.347707','1','0'),
('10','MOT-Fail-Certificate',NULL,'2','2014-12-04 15:59:09.731788',NULL,'2015-04-02 15:21:05.347707','1','0'),
('9','MCS MOT Scheme Advisory Notice',NULL,'2','2014-12-04 15:59:09.731788',NULL,'2015-04-02 15:21:05.347707','1','0'),
('8','MCS Reinspection - AWL',NULL,'2','2014-12-04 15:59:09.731788',NULL,'2015-04-02 15:21:05.347707','1','0'),
('7','MCS Reinspection - DAR',NULL,'2','2014-12-04 15:59:09.731788',NULL,'2015-04-02 15:21:05.347707','1','0'),
('6','MCS Reinspection - NFA',NULL,'2','2014-12-04 15:59:09.731788',NULL,'2015-04-02 15:21:05.347707','1','0'),
('5','MOT Scheme Advisory Notice',NULL,'2','2014-12-04 15:59:09.731788',NULL,'2015-04-02 15:21:05.347707','1','0'),
('4','Reinspection - AWL',NULL,'2','2014-12-04 15:59:09.731788',NULL,'2015-04-02 15:21:05.347707','1','0'),
('3','MOT-Pass-Certificate',NULL,'2','2014-12-04 15:59:09.731788',NULL,'2015-04-02 15:21:05.347707','1','0'),
('2','Reinspection - DAR',NULL,'2','2014-12-04 15:59:09.731788',NULL,'2015-04-02 15:21:05.347707','1','0'),
('1','Reinspection - NFA',NULL,'2','2014-12-04 15:59:09.731788',NULL,'2015-04-02 15:21:05.347707','1','0');

/*!40000 ALTER TABLE `jasper_template_type` ENABLE KEYS */;
UNLOCK TABLES;
