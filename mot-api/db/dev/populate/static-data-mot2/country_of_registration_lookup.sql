LOCK TABLES `country_of_registration_lookup` WRITE;
/*!40000 ALTER TABLE `country_of_registration_lookup` DISABLE KEYS */;

INSERT INTO `country_of_registration_lookup` (`id`, `country_lookup_id`, `name`, `code`, `mot1_legacy_id`, `licensing_copy`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version` ) VALUES
('36',NULL,'Not Applicable','XNA','#A','XNA','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('35',NULL,'Not Known','XUKN','#K','XUKN','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('34',NULL,'Non EU','XNEU','#E','XNEU','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('33','33','S (SE) - Sweden','SE','S','SE','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('32','32','E (ES) - Spain','ES','E','ES','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('31','31','SLO (SI) - Slovenia','SI','SLO','SI','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('30','30','SK (SK) - Slovakia','SK','SK','SK','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('29','29','RO (RO) - Romania','RO','RO','RO','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('28','28','P (PT) - Portugal','PT','P','PT','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('27','27','PL (PL) - Poland','PL','PL','PL','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('26','26','NL (NL) - Netherlands','NL','NL','NL','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('25','25','M (MT) - Malta','MT','M','MT','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('24','24','L (LU) - Luxembourg','LU','L','LU','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('23','23','LT (LT) - Lithuania','LT','LT','LT','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('22','22','LV (LV) - Latvia','LV','LV','LV','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('21','21','I (IT) - Italy','IT','I','IT','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('20','20','IRL (IE) - Ireland','IE','IRL','IE','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('19','19','H (HU) - Hungary','HU','H','HU','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('18','18','GR (GR) - Greece','GR','GR','GR','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('17','17','GBZ (GI) - Gibraltar','GI','BGZ','GI','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('16','16','D (DE) - Germany','DE','D','DE','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('15','15','F (FR) - France','FR','F','FR','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('14','14','FIN (FI) - Finland','FI','FIN','FI','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('13','13','EST (EE) - Estonia','EE','EST','EE','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('12','12','DK (DK) - Denmark','DK','DK','DK','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('11','11','CZ (CZ) - Czech Republic','CZ','CZ','CZ','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('10','10','CY (CY) - Cyprus','CY','CY','CY','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('9','9','BG (BG) - Bulgaria','BG','BG','BG','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('8','8','B (BE) - Belgium','BE','B','BE','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('7','7','A (AT) - Austria','AT','A','AT','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('6','6','GBM (IM) - Isle of Man','GBM','GBM','IM','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('5','5','GBJ (JE) - Jersey','GBJ','GBJ','JE','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('4','4','GBG (GG) - Guernsey','GBG','GBG','GG','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('3','3','GBA (GG) - Alderney','GBA','GBA','GG','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('2','2','GB, NI (UK) - Northern Ireland','NI','NI','UK','1','2015-04-02 15:21:02.000000',NULL,NULL,'1'),
('1','1','GB, UK, ENG, CYM, SCO (UK) - Great Britain','GB','GB','UK','1','2015-04-02 15:21:02.000000',NULL,NULL,'1');

/*!40000 ALTER TABLE `country_of_registration_lookup` ENABLE KEYS */;
UNLOCK TABLES;
