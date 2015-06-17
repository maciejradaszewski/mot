LOCK TABLES `wheelplan_type` WRITE;
/*!40000 ALTER TABLE `wheelplan_type` DISABLE KEYS */;

INSERT INTO `wheelplan_type` (`id`, `code`, `name`, `description`, `display_order`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('10','Y','non-standard','Non-standard Wheelplan','1',NULL,'1','2014-09-27 13:40:21.711212',NULL,NULL,'1','0'),
('9','K','crawler','Tracklaying Vehicle','1',NULL,'1','2014-09-27 13:40:21.711212',NULL,NULL,'1','0'),
('8','J','multi-axle & artic','4 or more axle tractor with articulated trailer','1',NULL,'1','2014-09-27 13:40:21.711212',NULL,NULL,'1','0'),
('7','H','3-axle & artic','3 axle tractor with articulated trailer','1',NULL,'1','2014-09-27 13:40:21.711212',NULL,NULL,'1','0'),
('6','G','2-axle & artic','2 axle tractor with articulated trailer','1',NULL,'1','2014-09-27 13:40:21.711212',NULL,NULL,'1','0'),
('5','E','multi-axle rigid','4 or more “axle” rigid chassis','1',NULL,'1','2014-09-27 13:40:21.711212',NULL,NULL,'1','0'),
('4','D','3 axle rigid body','3 “axle” rigid chassis/body','1',NULL,'1','2014-09-27 13:40:21.711212',NULL,NULL,'1','0'),
('3','C','2 axle rigid body','2 “axle” rigid chassis/body (This applies to all 4-wheeled cars, taxis & light commercials)','1',NULL,'1','2014-09-27 13:40:21.711212',NULL,NULL,'1','0'),
('2','B','3-wheel','3 wheels (Tricycle)','1',NULL,'1','2014-09-27 13:40:21.711212',NULL,NULL,'1','0'),
('1','A','2-wheel','2 wheels ','1',NULL,'1','2014-09-27 13:40:21.711212',NULL,NULL,'1','0');

/*!40000 ALTER TABLE `wheelplan_type` ENABLE KEYS */;
UNLOCK TABLES;
