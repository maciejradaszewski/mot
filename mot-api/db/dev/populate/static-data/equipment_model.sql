LOCK TABLES `equipment_model` WRITE;
/*!40000 ALTER TABLE `equipment_model` DISABLE KEYS */;

INSERT INTO `equipment_model` (`id`, `code`, `name`, `equipment_identification_number`, `equipment_make_id`, `equipment_type_id`, `software_version`, `certified`, `last_used_date`, `last_installable_date`, `equipment_model_status_id`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('11','AN/12','SPID 1 987 009 H30A','EINHB9999999999999','8','15',NULL,NULL,NULL,NULL,'1',NULL,'1','2014-12-04 15:59:10.556318',NULL,NULL,'1','0'),
('10','AN/11','1 987 009 A16A','EINHB14963A0212236','8','14',NULL,'2007-04-21',NULL,NULL,'1',NULL,'1','2014-12-04 15:59:10.556318',NULL,NULL,'1','0'),
('9','VO/01','VOSA Brakes Pull',NULL,'9','3',NULL,NULL,NULL,NULL,'1',NULL,'1','2014-12-04 15:59:10.556318',NULL,NULL,'1','0'),
('8','BA/02','BT 5060','EINHB14963A0212756','5','4',NULL,'1997-03-08',NULL,NULL,'1',NULL,'1','2014-12-04 15:59:10.556318',NULL,NULL,'1','0'),
('7','FB/01','3600','EINHB14963A0212986','7','2',NULL,NULL,NULL,NULL,'1',NULL,'1','2014-12-04 15:59:10.556318',NULL,NULL,'1','0'),
('6','TH/01','Brakesafe','EINHB14963A0212456','6','5','2.3v, 3.0v','1667-01-17',NULL,NULL,'1',NULL,'1','2014-12-04 15:59:10.556318',NULL,NULL,'1','0'),
('5','AJ/01','FICS 4000 ID UK',NULL,'4','7','11.07v',NULL,NULL,NULL,'1',NULL,'1','2014-12-04 15:59:10.556318',NULL,NULL,'1','0'),
('4','AE/01','MK V','EINHB14963A0212116','3','13',NULL,'2013-01-17',NULL,NULL,'1',NULL,'1','2014-12-04 15:59:10.556318',NULL,NULL,'1','0'),
('3','AD/02','Dieseltemp II',NULL,'2','12','1.07v, 1.13v, 1.45','2013-01-17',NULL,NULL,'1',NULL,'1','2014-12-04 15:59:10.556318',NULL,NULL,'1','0'),
('2','AC/02','42-902-20',NULL,'1','9',NULL,'2014-02-28',NULL,NULL,'1',NULL,'1','2014-12-04 15:59:10.556318',NULL,NULL,'1','0'),
('1','AC/01','50-01Y','EINHB14963A0212006','1','8','','2010-11-10',NULL,NULL,'1',NULL,'1','2014-12-04 15:59:10.556318',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `equipment_model` ENABLE KEYS */;
UNLOCK TABLES;
