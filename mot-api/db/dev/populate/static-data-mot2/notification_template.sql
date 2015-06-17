LOCK TABLES `notification_template` WRITE;
/*!40000 ALTER TABLE `notification_template` DISABLE KEYS */;

INSERT INTO `notification_template` (`id`, `content`, `subject`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number` ) VALUES
('13','Your Mot test on vehicle (${vinOrRegNumber}) has been ${newStatus} by ${userFullName}','Mot test was aborted',NULL,'2','2014-12-04 15:59:10.852125',NULL,'2015-04-02 15:21:05.310322','1','0'),
('12','Tester ${username} registered a vehicle for test at ${time} on ${date} at VTS: ${siteNumber}, ${address}. This test was undertaken outside the declared hours for testing and you may wish to query the reason or alter your declared opening times.','Test outside opening hours',NULL,'2','2014-12-04 15:59:10.852125',NULL,'2015-04-02 15:21:05.310322','1','0'),
('11','Your ${positionName} role association has been removed from ${siteName} (${siteOrOrganisationId}).  If you were not expecting this to happen contact the VTS.','Role Removal',NULL,'2','2014-12-04 15:59:10.852125',NULL,'2015-04-30 15:15:08.569278','1','0'),
('10','Your ${positionName} role association has been removed from ${organisationName} (${siteOrOrganisationId}).  If you were not expecting this to happen contact the AEDM of the Organisation.','Role Removal',NULL,'2','2014-12-04 15:59:10.852125',NULL,'2015-04-30 15:15:08.567049','1','0'),
('9','You have been assigned a role of ${positionName} for ${organisationName} by DVSA administration.','${positionName} role notification',NULL,'2','2014-12-04 15:59:10.852125',NULL,'2015-04-02 15:21:05.310322','1','0'),
('8','${nomineeName} has ${action} nomination for the role of ${positionName} at ${organisationName} (${organisationNumber}).','Nomination ${action}',NULL,'2','2014-12-04 15:59:10.852125',NULL,'2015-04-16 16:00:20.403643','1','0'),
('7','You have been nominated for the role of ${positionName} for ${organisationName} (${siteOrOrganisationId}) by ${nominatorName}. Please confirm nomination.','${positionName} nomination',NULL,'2','2014-12-04 15:59:10.852125',NULL,'2015-04-16 16:00:20.400359','1','0'),
('6','${nomineeName} has ${action} nomination for the role of ${positionName} at ${siteName} (${siteNumber}).','Nomination ${action}',NULL,'2','2014-12-04 15:59:10.852125',NULL,'2015-04-16 16:00:20.402104','1','0'),
('5','You have been nominated for the role of ${positionName} for ${siteName} (${siteOrOrganisationId}) by ${nominatorName}. Please confirm nomination.','${positionName} nomination',NULL,'2','2014-12-04 15:59:10.852125',NULL,'2015-04-16 16:00:20.386885','1','0'),
('4','Please contact DVSA to schedule another training course','Another training needed',NULL,'2','2014-12-04 15:59:10.852125',NULL,'2015-04-02 15:21:05.310322','1','0'),
('3','Please contact DVSA to schedule a demonstration test','Demonstration Test Needed',NULL,'2','2014-12-04 15:59:10.852125',NULL,'2015-04-02 15:21:05.310322','1','0'),
('2','Your application has been rejected. Please ring DVSA to discuss your application prior to re-applying. 
Tel: 0300123900','Application ${applicationReference} was rejected',NULL,'2','2014-12-04 15:59:10.852125',NULL,'2015-04-02 15:21:05.310322','1','0'),
('1','Please contact DVSA to schedule a 2 day DVSA Nominated Tester Training Course 
Tel: 0300123900','Application ${applicationReference} was approved',NULL,'2','2014-12-04 15:59:10.852125',NULL,'2015-04-02 15:21:05.310322','1','0');

/*!40000 ALTER TABLE `notification_template` ENABLE KEYS */;
UNLOCK TABLES;
