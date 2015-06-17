LOCK TABLES `enforcement_site_assessment` WRITE;
/*!40000 ALTER TABLE `enforcement_site_assessment` DISABLE KEYS */;

INSERT INTO `enforcement_site_assessment` (`id`, `site_id`, `site_assessment_score`, `authorisation_for_authorised_examiner_id`, `ae_representative_name`, `ae_representative_position`, `person_id`, `visit_outcome_id`, `advisory_issued`, `visit_date`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('1','1','31.40','1','Eric','Chief Boss','5','1','1','2011-09-19 18:23:28.000000',NULL,'101','2010-12-25 12:34:56.000000','1','2015-04-02 15:21:05.038362','1','0');

/*!40000 ALTER TABLE `enforcement_site_assessment` ENABLE KEYS */;
UNLOCK TABLES;
