LOCK TABLES `qualification` WRITE;
/*!40000 ALTER TABLE `qualification` DISABLE KEYS */;

INSERT INTO `qualification` (`id`, `name`, `description`, `qualification_type_id`, `awarded_by_organisation_id`, `country_lookup_id`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('15','ATA Light Vehicle, Diagnostic Technician',NULL,'1','7','1',NULL,'1','2014-12-04 15:59:09.683180',NULL,'2015-02-17 10:23:32.122449','1','0'),
('14','IMI Level National Diploma in Vehicle Maintenance and Repair (LV or HV) VRQ (Level 3)',NULL,'1','6','1',NULL,'1','2014-12-04 15:59:09.683180',NULL,'2015-02-17 10:23:32.122449','1','0'),
('13','Vehicle Technician - Vehicle Maintenance and Repair (LV or HV) Level 3',NULL,'1','5','1',NULL,'1','2014-12-04 15:59:09.683180',NULL,'2015-02-17 10:23:32.122449','1','0'),
('12','Vehicle Mechanical and Electronic Systems - Maintenance and Repair (LV or HV) Level 3',NULL,'1','5','1',NULL,'1','2014-12-04 15:59:09.683180',NULL,'2015-02-17 10:23:32.122449','1','0'),
('11','National Certificate in Vehicle Mechanics and Systems, Part 3',NULL,'1','4','1',NULL,'1','2014-12-04 15:59:09.683180',NULL,'2015-02-17 10:23:32.122449','1','0'),
('10','Motor Vehicle Engineering Studies, National Certificate or ONC',NULL,'1','3','1',NULL,'1','2014-12-04 15:59:09.683180',NULL,'2015-02-17 10:23:32.122449','1','0'),
('9','Automotive Qualification NVQ Level 3',NULL,'1','2','1',NULL,'1','2014-12-04 15:59:09.683180',NULL,'2015-02-17 10:23:32.122449','1','0'),
('8','Motor Vehicle Technician\'s Certificate - full Part 1',NULL,'1','2','1',NULL,'1','2014-12-04 15:59:09.683180',NULL,'2015-02-17 10:23:32.122449','1','0'),
('7','Heavy Vehicle Mechanics Craft Studies - full part 2 or 3',NULL,'1','2','1',NULL,'1','2014-12-04 15:59:09.683180',NULL,'2015-02-17 10:23:32.122449','1','0'),
('6','Light Vehicle Mechanics Craft Studies - full part 2 or 3',NULL,'1','2','1',NULL,'1','2014-12-04 15:59:09.683180',NULL,'2015-02-17 10:23:32.122449','1','0'),
('5','Motor Vehicle Craft Studies (pre 381 syllabus) - full part 2',NULL,'1','2','1',NULL,'1','2014-12-04 15:59:09.683180',NULL,'2015-02-17 10:23:32.122449','1','0'),
('4','Motor Vehicle Craft Studies, 381 - full part 2 or 3',NULL,'1','2','1',NULL,'1','2014-12-04 15:59:09.683180',NULL,'2015-02-17 10:23:32.122449','1','0'),
('3','Motor Vehicle Craft Studies, Modular - part 3 (requires 3 modules)',NULL,'1','2','1',NULL,'1','2014-12-04 15:59:09.683180',NULL,'2015-02-17 10:23:32.122449','1','0'),
('2','Repair and Servicing of Road Vehicles, 383 - full level 2 or 3',NULL,'1','2','1',NULL,'1','2014-12-04 15:59:09.683180',NULL,'2015-02-17 10:23:32.122449','1','0'),
('1','In the specialism of Vehicle Maintenance and Electronic Systems',NULL,'1','1','1',NULL,'1','2014-12-04 15:59:09.683180',NULL,'2015-02-17 10:23:32.122449','1','0');

/*!40000 ALTER TABLE `qualification` ENABLE KEYS */;
UNLOCK TABLES;
