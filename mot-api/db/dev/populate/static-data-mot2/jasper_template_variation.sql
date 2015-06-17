LOCK TABLES `jasper_template_variation` WRITE;
/*!40000 ALTER TABLE `jasper_template_variation` DISABLE KEYS */;

INSERT INTO `jasper_template_variation` (`id`, `template_id`, `name`, `jasper_report_name`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('3','11','Wales','MOT/VT32WVE.pdf',NULL,'2','2014-12-05 11:56:52.733493',NULL,'2015-04-02 15:21:05.349918','1','0'),
('2','10','Wales','MOT/VT30W.pdf',NULL,'2','2014-12-05 11:56:52.733493',NULL,'2015-04-02 15:21:05.349918','1','0'),
('1','3','Wales','MOT/VT20W.pdf',NULL,'2','2014-12-05 11:56:52.733493',NULL,'2015-04-02 15:21:05.349918','1','0');

/*!40000 ALTER TABLE `jasper_template_variation` ENABLE KEYS */;
UNLOCK TABLES;
