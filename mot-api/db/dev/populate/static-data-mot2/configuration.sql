LOCK TABLES `configuration` WRITE;
/*!40000 ALTER TABLE `configuration` DISABLE KEYS */;

INSERT INTO `configuration` (`id`, `key`, `value`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('24','salesSupplierVatNumber','989 212 656',NULL,'2','2015-04-16 16:00:21.550809',NULL,NULL,'1','0'),
('23','salesSupplierAddress','The Ellipse Padley Road, Swansea SA1 8AN',NULL,'2','2015-04-16 16:00:21.550809',NULL,NULL,'1','0'),
('22','salesSupplierName','DVSA',NULL,'2','2015-04-16 16:00:21.550809',NULL,NULL,'1','0'),
('21','salesReferenceStrPad','8',NULL,'2','2015-04-16 16:00:21.550809',NULL,NULL,'1','0'),
('20','salesReferencePrefix','MOT',NULL,'2','2015-04-16 16:00:21.550809',NULL,NULL,'1','0'),
('19','testSlotVatRate','0',NULL,'2','2015-04-16 16:00:21.550809',NULL,NULL,'1','0'),
('18','testSlotProductDescription','MOT Test slots',NULL,'2','2015-04-16 16:00:21.550809',NULL,NULL,'1','0'),
('17','testSlotProductReference','MOT_SLOTS',NULL,'2','2015-04-16 16:00:21.550809',NULL,NULL,'1','0'),
('16','testSlotCostCentre','53170,00000',NULL,'2','2015-04-16 16:00:21.550809',NULL,NULL,'1','0'),
('15','CardPaymentEndPoint','/api/payment/card',NULL,'2','2015-04-16 16:00:21.550809',NULL,NULL,'1','0'),
('14','directDebitDaysAhead','14',NULL,'2','2014-12-04 15:59:09.580269',NULL,'2015-04-02 15:21:05.297094','1','0'),
('13','daysForSlotIncrementAfterDDCollectionDate','5',NULL,'2','2014-12-04 15:59:09.580269',NULL,'2015-04-02 15:21:05.297094','1','0'),
('12','otpMaxNumberOfAttempts','3',NULL,'2','2014-12-04 15:59:09.580269',NULL,'2015-04-02 15:21:05.297094','1','0'),
('11','testerExpiryYearsBeforeInitialTraining','5',NULL,'2','2014-12-04 15:59:09.580269',NULL,'2015-04-02 15:21:05.297094','1','0'),
('10','testerExpiryYearsBeforeRefresherCourse','2',NULL,'2','2014-12-04 15:59:09.580269',NULL,'2015-04-02 15:21:05.297094','1','0'),
('9','testerExpiryMonthsBeforeDemoTest','6',NULL,'2','2014-12-04 15:59:09.580269',NULL,'2015-04-02 15:21:05.297094','1','0'),
('8','yearsBeforeFirstMotTestIsDue','3',NULL,'2','2014-12-04 15:59:09.580269',NULL,'2015-04-02 15:21:05.297094','1','0'),
('7','odometerReadingModificationWindowLengthInDays','7',NULL,'2','2014-12-04 15:59:09.580269',NULL,'2015-04-02 15:21:05.297094','1','0'),
('6','maxVisibleVehicleTestHistoryInMonths','18',NULL,'2','2014-12-04 15:59:09.580269',NULL,'2015-04-02 15:21:05.297094','1','0'),
('5','maxCalendarMthsForPostdatingExpiryDte','1',NULL,'2','2014-12-04 15:59:09.580269',NULL,'2015-04-02 15:21:05.297094','1','0'),
('4','odometerDeltaSignificantValue','25000',NULL,'2','2014-12-04 15:59:09.580269',NULL,'2015-04-02 15:21:05.297094','1','0'),
('3','testSlotMaxAmount','75000',NULL,'2','2014-12-04 15:59:09.580269',NULL,'2015-04-16 16:00:21.554113','1','0'),
('2','testSlotMinAmount','25',NULL,'2','2014-12-04 15:59:09.580269',NULL,'2015-04-02 15:21:05.297094','1','0'),
('1','testSlotPrice','2.05',NULL,'2','2014-12-04 15:59:09.580269',NULL,'2015-04-02 15:21:05.297094','1','0');

/*!40000 ALTER TABLE `configuration` ENABLE KEYS */;
UNLOCK TABLES;
