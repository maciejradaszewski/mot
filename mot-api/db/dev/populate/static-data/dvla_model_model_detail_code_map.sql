LOCK TABLES `dvla_model_model_detail_code_map` WRITE;
/*!40000 ALTER TABLE `dvla_model_model_detail_code_map` DISABLE KEYS */;

INSERT INTO `dvla_model_model_detail_code_map` (`id`, `dvla_make_code`, `dvla_model_code`, `make_id`, `model_id`, `model_detail_id`, `vsi_code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('1','188A9','01D93','100176','106782',NULL,NULL,NULL,'1','2015-04-15 18:38:25.420392',NULL,NULL,'1','1');

/*!40000 ALTER TABLE `dvla_model_model_detail_code_map` ENABLE KEYS */;
UNLOCK TABLES;
