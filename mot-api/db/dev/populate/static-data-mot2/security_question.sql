LOCK TABLES `security_question` WRITE;
/*!40000 ALTER TABLE `security_question` DISABLE KEYS */;

INSERT INTO `security_question` (`id`, `question_text`, `question_group`, `display_order`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('8','Who was your childhood hero?','2','4',NULL,'2','2015-02-17 10:23:28.855560',NULL,'2015-04-02 15:21:05.330338','1','0'),
('7','Where did you go on your first memorable holiday?','2','3',NULL,'2','2015-02-17 10:23:28.855560',NULL,'2015-04-02 15:21:05.330338','1','0'),
('6','Where did you go on your first school trip?','2','2',NULL,'2','2015-02-17 10:23:28.855560',NULL,'2015-04-02 15:21:05.330338','1','0'),
('5','What did you want to be when you grew up?','2','1',NULL,'2','2015-02-17 10:23:28.855560',NULL,'2015-04-02 15:21:05.330338','1','0'),
('4','What was the first concert you attended?','1','4',NULL,'2','2015-02-17 10:23:28.855560',NULL,'2015-04-02 15:21:05.330338','1','0'),
('3','What was your favourite place to visit as a child?','1','3',NULL,'2','2015-02-17 10:23:28.855560',NULL,'2015-04-02 15:21:05.330338','1','0'),
('2','What was the name of your first stuffed animal, doll or action figure?','1','2',NULL,'2','2015-02-17 10:23:28.855560',NULL,'2015-04-02 15:21:05.330338','1','0'),
('1','Who was your first kiss?','1','1',NULL,'2','2015-02-17 10:23:28.855560',NULL,'2015-04-02 15:21:05.330338','1','0');

/*!40000 ALTER TABLE `security_question` ENABLE KEYS */;
UNLOCK TABLES;
