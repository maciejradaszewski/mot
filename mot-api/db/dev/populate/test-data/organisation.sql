LOCK TABLES `organisation` WRITE;
/*!40000 ALTER TABLE `organisation` DISABLE KEYS */;

INSERT INTO `organisation` (`id`, `name`, `registered_company_number`, `vat_registration_number`, `trading_name`, `company_type_id`, `organisation_type_id`, `transition_status_id`, `transition_scheduled_on`, `sites_confirmed_ready_on`, `transition_processed_on`, `first_payment_setup_on`, `first_slots_purchased_on`, `mot1_total_running_balance`, `mot1_total_slots_converted`, `mot1_total_remainder_balance`, `mot1_total_vts_slots_merged`, `mot1_total_slots_merged`, `mot1_slots_migrated_on`, `mot1_details_updated_on`, `slots_balance`, `slots_warning`, `slots_purchased`, `slots_overdraft`, `data_may_be_disclosed`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('2001','Jägermeister','ROTFLOL',NULL,'DUI Garages','1','7',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','70','0','0','0',NULL,'1','2014-12-05 11:56:52.490433',NULL,'2015-03-23 09:12:09.317167','1','0'),
('1001','Need4Speed','XS12B3',NULL,'Carbon','1','7',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','70','0','0','0',NULL,'1','2014-12-05 11:56:52.380924',NULL,'2015-03-23 09:12:09.315665','1','0'),
('13','Venture Industries AE','USA301',NULL,'Venture Industries','1','7',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'200','10','0','0','0',NULL,'1','2014-12-05 11:56:51.958683',NULL,'2015-03-23 09:12:09.313967','1','0'),
('12','Square Wheels','UK001144',NULL,'Square Wheels','2','7',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','70','0','0','0',NULL,'1','2014-12-05 11:56:51.958683',NULL,'2015-03-23 09:12:09.311487','1','0'),
('10','Crazy Wheels Inc.','UK9102',NULL,'Crazy Wheels','4','7',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'88','15','0','0','0',NULL,'1','2014-12-05 11:56:51.958683',NULL,'2015-03-23 09:12:09.309816','1','0'),
('9','Example AE Inc.','UK1283',NULL,'AE Example','1','7',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1088','15','0','0','0',NULL,'1','2014-12-05 11:56:51.958683',NULL,'2015-03-23 09:12:09.308548','1','0'),
('3','Fix Quick Ltd','UK001133',NULL,'Fix Quick','1','7',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1088','15','0','0','0',NULL,'1','2014-12-05 11:56:50.678677',NULL,'2015-03-23 09:12:09.306975','1','0'),
('2','City Fixes Ltd','UK001122',NULL,'City Fixes','1','7',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1088','70','0','0','0',NULL,'1','2014-12-05 11:56:50.678677',NULL,'2015-03-23 09:12:09.304359','1','0');

/*!40000 ALTER TABLE `organisation` ENABLE KEYS */;
UNLOCK TABLES;
