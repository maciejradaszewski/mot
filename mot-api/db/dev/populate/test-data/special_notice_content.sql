LOCK TABLES `special_notice_content` WRITE;
/*!40000 ALTER TABLE `special_notice_content` DISABLE KEYS */;

INSERT INTO `special_notice_content` (`id`, `title`, `issue_number`, `issue_year`, `issue_date`, `expiry_date`, `internal_publish_date`, `external_publish_date`, `notice_text`, `acknowledge_within`, `is_published`, `is_deleted`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('2','You shall not pass','1','2015','2015-03-27 12:04:56.000000','2015-04-24 12:04:56.000000','2015-04-20 12:04:56.000000','2015-04-23 12:04:56.000000','Wanna be an Active Tester?',NULL,'1','0',NULL,'2','2015-04-30 15:15:09.794629',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `special_notice_content` ENABLE KEYS */;
UNLOCK TABLES;
