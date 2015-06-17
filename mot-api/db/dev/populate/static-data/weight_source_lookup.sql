LOCK TABLES `weight_source_lookup` WRITE;
/*!40000 ALTER TABLE `weight_source_lookup` DISABLE KEYS */;

INSERT INTO `weight_source_lookup` (`id`, `code`, `name`, `description`, `display_order`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('9','U','unladen','Unladen weight - entered on screen for classes 5 and 5a. The entered value is stored as brake weight but is increased by number of seats times Scheme Parameter for use in efficiency calculations.','9','U','1','2015-02-17 10:58:27.000000',NULL,NULL,'1','1'),
('8','M','motorcycle','Motorcycle - as derived for classes 1 and 2 from weights of motorcycle and rider.','8','M','1','2015-02-17 10:58:27.000000',NULL,NULL,'1','1'),
('7','MISW','misw','Mass in Service Weight','7',NULL,'1','2015-02-17 10:58:27.000000',NULL,NULL,'1','1'),
('6','CALC','calculated','Calculated','6','L','1','2015-02-17 10:58:27.000000',NULL,NULL,'1','1'),
('5','NA','not-applicable','Not Applicable - equipment used did not require weight (decelerometer; gradient; roller or floor test resulting in pass on wheel locking).','5','N','1','2015-02-17 10:58:27.000000',NULL,NULL,'1','1'),
('4','DGWM','dgw-mam','Design Gross Weight mam','4',NULL,'1','2015-02-17 10:58:27.000000',NULL,NULL,'1','1'),
('3','DGW','dgw','Design Gross Weight - as entered on screen for classes 5, 5a, 7','3','D','1','2015-02-17 10:58:27.000000',NULL,NULL,'1','1'),
('2','KERB','presented','Kerbside weight - as entered on screen for classes 3, 4, 4a','2','K','1','2015-02-17 10:58:27.000000',NULL,NULL,'1','1'),
('1','VSI','vsi','VSI - as provided from VSI, for any class - presence prevents selection of any other weight','1','V','1','2015-02-17 10:58:27.000000',NULL,NULL,'1','1'),
('0','UNKN','unknown','Unknown - no test of brakes','10','X','1','2015-02-17 10:58:27.000000',NULL,'2015-03-10 17:47:34.866958','1','1');

/*!40000 ALTER TABLE `weight_source_lookup` ENABLE KEYS */;
UNLOCK TABLES;
