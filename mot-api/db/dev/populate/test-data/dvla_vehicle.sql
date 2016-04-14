LOCK TABLES `dvla_vehicle` WRITE;
/*!40000 ALTER TABLE `dvla_vehicle` DISABLE KEYS */;

INSERT INTO `dvla_vehicle` (`id`, `registration`, `registration_validation_character`, `vin`, `model_code`, `make_code`, `make_in_full`, `colour_1_code`, `colour_2_code`, `propulsion_code`, `designed_gross_weight`, `unladen_weight`, `engine_number`, `engine_capacity`, `seating_capacity`, `manufacture_date`, `first_registration_date`, `is_seriously_damaged`, `recent_v5_document_number`, `is_vehicle_new_at_first_registration`, `body_type_code`, `wheelplan_code`, `sva_emission_standard`, `ct_related_mark`, `vehicle_id`, `dvla_vehicle_id`, `eu_classification`, `mass_in_service_weight`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('3','V351JJS','9','WBADT22030GZ31234','01163','1889A',NULL,'S','S','1','0','1327','AZD 117678','1595','0','2001-09-01','2001-09-01','0','11135130322','1','h','C',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','2015-02-17 10:23:17.891334',NULL,'2015-04-02 15:21:05.391353','1','0'),
('2','G266ARX','-','VF1BA0B0525341234','0119E','1884Z',NULL,'S','S','1','0','0','1M36398','1596','0','2001-09-16','2001-09-16','0','20166243322','1','h','C',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','2015-02-17 10:23:17.891334',NULL,'2015-04-02 15:21:05.391353','1','0'),
('1','F50GGP','+','WF0BXXGAJB1R41234','01516','18801',NULL,'S','S','1','0','0','C254247','1870','0','2001-09-18','2001-09-18','0','22048502322','1','h','C',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','2015-02-17 10:23:17.891334',NULL,'2015-04-02 15:21:05.391353','1','0');

/*!40000 ALTER TABLE `dvla_vehicle` ENABLE KEYS */;
UNLOCK TABLES;
