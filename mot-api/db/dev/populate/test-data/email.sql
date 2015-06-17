LOCK TABLES `email` WRITE;
/*!40000 ALTER TABLE `email` DISABLE KEYS */;

INSERT INTO `email` (`id`, `contact_detail_id`, `email`, `is_primary`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('25','25011','dummy@example.com','1',NULL,'1','2014-12-05 11:57:01.809029',NULL,NULL,'1','0'),
('24','25010','foo@bar.com','1',NULL,'1','2014-12-05 11:57:01.771897',NULL,NULL,'1','0'),
('23','25009','foo@bar.com','1',NULL,'1','2014-12-05 11:57:01.684000',NULL,NULL,'1','0'),
('22','25008','foo@bar.com','1',NULL,'1','2014-12-05 11:57:01.654616',NULL,NULL,'1','0'),
('21','25007','foo@bar.com','1',NULL,'1','2014-12-05 11:57:01.627614',NULL,NULL,'1','0'),
('20','25006','foo@bar.com','1',NULL,'1','2014-12-05 11:57:01.602926',NULL,NULL,'1','0'),
('19','25005','dummy@email.com','1',NULL,'1','2014-12-05 11:57:00.961361',NULL,NULL,'1','0'),
('18','25004','dummy@example.com','1',NULL,'1','2014-12-05 11:56:59.447620',NULL,NULL,'1','0'),
('17','25003','dummy@example.com','1',NULL,'1','2014-12-05 11:56:59.422008',NULL,NULL,'1','0'),
('16','25002','dummy@email.com','1',NULL,'1','2014-12-05 11:56:55.969151',NULL,NULL,'1','0'),
('15','25001','dummy@email.com','1',NULL,'1','2014-12-05 11:56:55.926162',NULL,NULL,'1','0'),
('14','14','rusty@venture-industries.com','1',NULL,'1','2014-12-05 11:56:52.249932',NULL,NULL,'1','0'),
('13','13','boy.genius@conjectural-technologies.com','1',NULL,'1','2014-12-05 11:56:52.249932',NULL,NULL,'1','0'),
('12','12','pete.white@conjectural-technologies.com','1',NULL,'1','2014-12-05 11:56:52.249932',NULL,NULL,'1','0'),
('11','11','chop.shop@isis.com','1',NULL,'1','2014-12-05 11:56:52.249932',NULL,NULL,'1','0'),
('10','10','rural.lion@isis.com','1',NULL,'1','2014-12-05 11:56:52.249932',NULL,NULL,'1','0'),
('9','9','central@isis.com','1',NULL,'1','2014-12-05 11:56:52.249932',NULL,NULL,'1','0'),
('8','8','dummy@email.com','1',NULL,'1','2014-12-05 11:56:52.249932',NULL,NULL,'1','0'),
('7','7','dummy@email.com','1',NULL,'1','2014-12-05 11:56:52.249932',NULL,NULL,'1','0'),
('6','6','dummy@email.com','1',NULL,'1','2014-12-05 11:56:52.249932',NULL,NULL,'1','0'),
('5','5','dummy@email.com','1',NULL,'1','2014-12-05 11:56:52.249932',NULL,NULL,'1','0'),
('4','4','dummy@email.com','1',NULL,'1','2014-12-05 11:56:52.249932',NULL,NULL,'1','0'),
('3','3','dummy@email.com','1',NULL,'1','2014-12-05 11:56:52.249932',NULL,NULL,'1','0'),
('2','2','dummy@email.com','1',NULL,'1','2014-12-05 11:56:52.249932',NULL,NULL,'1','0'),
('1','1','dummy@email.com','1',NULL,'1','2014-12-05 11:56:52.249932',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `email` ENABLE KEYS */;
UNLOCK TABLES;
