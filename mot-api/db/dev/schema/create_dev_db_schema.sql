-- MySQL dump 10.13  Distrib 5.6.22, for Linux (x86_64)
--
-- Host: localhost    Database: mot
-- ------------------------------------------------------
-- Server version	5.6.22-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `address`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `address_line_1` varchar(50) DEFAULT NULL COMMENT 'SAON (secondary addressable object name) i.e. Flat or Unit NumberLegacy data my composite with PAON',
  `address_line_2` varchar(50) DEFAULT NULL COMMENT 'PAON (primary addressable object name) i.e. House name or number',
  `address_line_3` varchar(50) DEFAULT NULL,
  `address_line_4` varchar(50) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL,
  `town` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `address_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `address_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000018 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `app_auth_site_evidence_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_auth_site_evidence_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_for_auth_testing_mot_at_site_id` int(10) unsigned NOT NULL,
  `evidence_id` int(10) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_map_app_auth_site_evidence_1` (`app_for_auth_testing_mot_at_site_id`),
  KEY `fk_map_app_auth_site_evidence_2` (`evidence_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `app_auth_site_evidence_map_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `app_auth_site_evidence_map_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_map_application_authorisation_site_evidence_1` FOREIGN KEY (`app_for_auth_testing_mot_at_site_id`) REFERENCES `app_for_auth_testing_mot_at_site` (`id`),
  CONSTRAINT `fk_map_application_authorisation_site_evidence_2` FOREIGN KEY (`evidence_id`) REFERENCES `evidence` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `app_for_auth_for_ae`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_for_auth_for_ae` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `application_id` int(10) unsigned NOT NULL,
  `auth_for_ae_id` int(10) unsigned NOT NULL,
  `principle_person_id` int(10) unsigned NOT NULL COMMENT 'Principle Person at time of authorisation',
  `designated_manager_person_id` int(10) unsigned NOT NULL,
  `status_id` smallint(5) unsigned NOT NULL,
  `status_changed_on` datetime(6) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_app_for_auth_for_ae_1` (`application_id`),
  KEY `fk_app_for_auth_for_ae_auth` (`auth_for_ae_id`),
  KEY `fk_app_for_auth_for_ae_status` (`status_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `fk_app_for_auth_for_ae_aep` (`principle_person_id`),
  KEY `fk_app_for_auth_for_ae_aedm` (`designated_manager_person_id`),
  CONSTRAINT `app_for_auth_for_ae_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `app_for_auth_for_ae_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_app_for_auth_for_ae_1` FOREIGN KEY (`application_id`) REFERENCES `application` (`id`),
  CONSTRAINT `fk_app_for_auth_for_ae_aedm` FOREIGN KEY (`designated_manager_person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_app_for_auth_for_ae_aep` FOREIGN KEY (`principle_person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_app_for_auth_for_ae_auth` FOREIGN KEY (`auth_for_ae_id`) REFERENCES `auth_for_ae` (`id`),
  CONSTRAINT `fk_app_for_auth_for_ae_status` FOREIGN KEY (`status_id`) REFERENCES `app_status` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Applications for an organisation wanting to become an Authorised Examiner';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `app_for_auth_testing_mot`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_for_auth_testing_mot` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `application_id` int(10) unsigned NOT NULL,
  `authorisation_for_testing_mot_id` int(10) unsigned NOT NULL,
  `status_id` smallint(5) unsigned NOT NULL,
  `status_changed_on` datetime(6) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_app_for_auth_testing_mot_app` (`application_id`),
  KEY `fk_app_for_auth_testing_mot_auth` (`authorisation_for_testing_mot_id`),
  KEY `fk_app_for_auth_testing_mot_status` (`status_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `app_for_auth_testing_mot_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `app_for_auth_testing_mot_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_app_for_auth_testing_mot_app` FOREIGN KEY (`application_id`) REFERENCES `application` (`id`),
  CONSTRAINT `fk_app_for_auth_testing_mot_auth` FOREIGN KEY (`authorisation_for_testing_mot_id`) REFERENCES `auth_for_testing_mot` (`id`),
  CONSTRAINT `fk_app_for_auth_testing_mot_status` FOREIGN KEY (`status_id`) REFERENCES `app_status` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `app_for_auth_testing_mot_at_site`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_for_auth_testing_mot_at_site` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `application_id` int(10) unsigned DEFAULT NULL COMMENT 'MOT1 data may not be able to map back to application for first stage migration',
  `site_id` int(10) unsigned DEFAULT NULL,
  `status_id` smallint(5) unsigned NOT NULL,
  `status_changed_on` datetime(6) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_app_for_auth_testing_mot_at_site_1` (`application_id`),
  KEY `fk_app_for_auth_testing_mot_at_site_3` (`status_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `app_for_auth_testing_mot_at_site_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `app_for_auth_testing_mot_at_site_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_app_for_auth_testing_mot_at_site_1` FOREIGN KEY (`application_id`) REFERENCES `application` (`id`),
  CONSTRAINT `fk_app_for_auth_testing_mot_at_site_3` FOREIGN KEY (`status_id`) REFERENCES `app_status` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `app_status`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_status` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_app_status_code` (`code`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `app_status_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `app_status_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Status of an appplication in the system (APPLIED, APPROVED IN PRINCIPLE, APPROVED, REFERRED, REJECTED)';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `app_to_auth_testing_mot_at_site_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_to_auth_testing_mot_at_site_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_for_auth_testing_mot_at_site_id` int(10) unsigned NOT NULL,
  `authorisation_testing_mot_at_site_id` int(10) unsigned NOT NULL,
  `status_id` smallint(5) unsigned NOT NULL,
  `status_changed_on` datetime(6) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_app_to_auth_testing_mot_at_site_map_afatmas` (`app_for_auth_testing_mot_at_site_id`),
  KEY `fk_app_to_auth_testing_mot_at_site_map_atmas` (`authorisation_testing_mot_at_site_id`),
  KEY `fk_app_to_auth_testing_mot_at_site_map_status` (`status_id`),
  KEY `fk_d9acacc8-375f-11e4-9d59-485d60c531e30` (`created_by`),
  KEY `fk_d9acae94-375f-11e4-9d59-485d60c531e30` (`last_updated_by`),
  CONSTRAINT `fk_app_to_auth_testing_mot_at_site_map_afatmas` FOREIGN KEY (`app_for_auth_testing_mot_at_site_id`) REFERENCES `app_for_auth_testing_mot_at_site` (`id`),
  CONSTRAINT `fk_app_to_auth_testing_mot_at_site_map_atmas` FOREIGN KEY (`authorisation_testing_mot_at_site_id`) REFERENCES `auth_for_testing_mot_at_site` (`id`),
  CONSTRAINT `fk_app_to_auth_testing_mot_at_site_map_status` FOREIGN KEY (`status_id`) REFERENCES `app_status` (`id`),
  CONSTRAINT `fk_d9acacc8-375f-11e4-9d59-485d60c531e30` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_d9acae94-375f-11e4-9d59-485d60c531e30` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `application_reference` varchar(15) NOT NULL,
  `person_id` int(10) unsigned NOT NULL COMMENT 'Applying Person',
  `status_id` smallint(5) unsigned NOT NULL,
  `status_changed_on` datetime(6) NOT NULL,
  `locked_by` int(10) unsigned DEFAULT NULL COMMENT 'Person currently editing record',
  `locked_on` datetime(6) DEFAULT NULL,
  `submitted_on` datetime(6) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_application_app_status` (`status_id`),
  KEY `fk_application_locked_by` (`locked_by`),
  KEY `fk_application_person` (`person_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `application_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `application_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_application_app_status` FOREIGN KEY (`status_id`) REFERENCES `app_status` (`id`),
  CONSTRAINT `fk_application_locked_by` FOREIGN KEY (`locked_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_application_person` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='An application made by a person for a form of authorisation in the system';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `approval_condition_appointment_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `approval_condition_appointment_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_condition_approval_id` int(10) unsigned NOT NULL,
  `condition_appointment_id` smallint(5) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_site_condition_approval_condition_appointment` (`site_condition_approval_id`,`condition_appointment_id`),
  KEY `ix_approval_condition_appointment_map_site_condition_approval_id` (`site_condition_approval_id`),
  KEY `ix_approval_condition_appointment_map_condition_appointment_id` (`condition_appointment_id`),
  CONSTRAINT `fk_enforcement_condition_appointment_lookup_id` FOREIGN KEY (`condition_appointment_id`) REFERENCES `enforcement_condition_appointment_lookup` (`id`),
  CONSTRAINT `fk_site_condition_approval_id` FOREIGN KEY (`site_condition_approval_id`) REFERENCES `site_condition_approval` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `assembly`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assembly` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(10) CHARACTER SET latin1 NOT NULL,
  `assembly_type_id` smallint(5) unsigned DEFAULT NULL,
  `parent_assembly_id` int(10) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_assembly_code` (`code`),
  KEY `fk_assembly_assembly_type` (`assembly_type_id`),
  KEY `fk_assembly_parent_assembly_id` (`parent_assembly_id`),
  KEY `fk_assembly_person_created_by` (`created_by`),
  KEY `fk_assembly_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_assembly_assembly_type` FOREIGN KEY (`assembly_type_id`) REFERENCES `assembly_type` (`id`),
  CONSTRAINT `fk_assembly_parent_assembly_id` FOREIGN KEY (`parent_assembly_id`) REFERENCES `assembly` (`id`),
  CONSTRAINT `fk_assembly_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_assembly_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='A was of grouping entities in the system i.e. Area, Zone, MEP also Area Offices';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `assembly_role_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assembly_role_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_assembly_role_type_code` (`code`),
  KEY `fk_assembly_role_type_person_created_by` (`created_by`),
  KEY `fk_assembly_role_type_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_assembly_role_type_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_assembly_role_type_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `assembly_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assembly_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_assembly_type_person_created_by` (`created_by`),
  KEY `fk_assembly_type_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_assembly_type_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_assembly_type_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Classification of a assembly (group i.e. Area, MEP etc)';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `auth_for_ae`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_for_ae` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ae_ref` varchar(12) DEFAULT NULL,
  `organisation_id` int(10) unsigned NOT NULL,
  `ao_site_id` int(10) unsigned DEFAULT NULL,
  `status_id` smallint(5) unsigned NOT NULL,
  `status_changed_on` datetime(6) NOT NULL,
  `valid_from` datetime(6) DEFAULT NULL COMMENT 'Authorisation valid from',
  `expiry_date` datetime(6) DEFAULT NULL COMMENT 'Authorisation expiry date (normally for approval in principle)',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_auth_for_ae_person_created_by` (`created_by`),
  KEY `fk_auth_for_ae_person_last_updated_by` (`last_updated_by`),
  KEY `fk_auth_for_ae_status_id` (`status_id`),
  KEY `fk_auth_for_ae_organisation_id` (`organisation_id`),
  KEY `fk_auth_for_ae_ao_site_id` (`ao_site_id`),
  CONSTRAINT `fk_auth_for_ae_ao_site_id` FOREIGN KEY (`ao_site_id`) REFERENCES `site` (`id`),
  CONSTRAINT `fk_auth_for_ae_organisation_id` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`),
  CONSTRAINT `fk_auth_for_ae_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_auth_for_ae_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_auth_for_ae_status_id` FOREIGN KEY (`status_id`) REFERENCES `auth_for_ae_status` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2002 DEFAULT CHARSET=utf8 COMMENT='Record of the authorisation of an organisation to be an Authorised Examiner';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `auth_for_ae_person_as_principal_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_for_ae_person_as_principal_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `person_id` int(10) unsigned NOT NULL,
  `auth_for_ae_id` int(11) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_auth_for_ae_person_map_person_id` (`person_id`),
  KEY `fk_auth_for_ae_person_map_auth_for_ae_id` (`auth_for_ae_id`),
  KEY `fk_auth_for_ae_person_map_person_created_by` (`created_by`),
  KEY `fk_auth_for_ae_person_map_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_auth_for_ae_person_map_authorised_examiner` FOREIGN KEY (`auth_for_ae_id`) REFERENCES `auth_for_ae` (`id`),
  CONSTRAINT `fk_auth_for_ae_person_map_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_auth_for_ae_person_map_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_auth_for_ae_person_map_person_id` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `auth_for_ae_status`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_for_ae_status` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_auth_for_ae_status_code` (`code`),
  KEY `fk_auth_for_ae_status_person_created_by` (`created_by`),
  KEY `fk_auth_for_ae_status_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_auth_for_ae_status_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_auth_for_ae_status_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='Normalisation for the status of an authorisation for an organisation to be an Authorised Examiner.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `auth_for_testing_mot`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_for_testing_mot` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `person_id` int(10) unsigned NOT NULL,
  `vehicle_class_id` smallint(5) unsigned NOT NULL,
  `status_id` smallint(5) unsigned NOT NULL,
  `status_changed_on` datetime(6) NOT NULL,
  `valid_from` datetime(6) DEFAULT NULL COMMENT 'Authorisation valid from',
  `expiry_date` datetime(6) DEFAULT NULL COMMENT 'Authorisation expiry date',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `person_id` (`person_id`,`vehicle_class_id`),
  KEY `fk_auth_for_testing_mot_status_id` (`status_id`),
  KEY `fk_auth_for_testing_mot_vehicle_class_id` (`vehicle_class_id`),
  KEY `fk_auth_for_testing_mot_person_created_by` (`created_by`),
  KEY `fk_auth_for_testing_mot_person_last_updated_by` (`last_updated_by`),
  KEY `fk_auth_for_testing_mot_person_id` (`person_id`),
  CONSTRAINT `fk_auth_for_testing_mot_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_auth_for_testing_mot_person_id` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_auth_for_testing_mot_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_auth_for_testing_mot_status_id` FOREIGN KEY (`status_id`) REFERENCES `auth_for_testing_mot_status` (`id`),
  CONSTRAINT `fk_auth_for_testing_mot_vehicle_class_id` FOREIGN KEY (`vehicle_class_id`) REFERENCES `vehicle_class` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=620 DEFAULT CHARSET=utf8 COMMENT='Record for the authorisation of a person to conduct an MOT on a particular vehicle class.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `auth_for_testing_mot_at_site`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_for_testing_mot_at_site` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL,
  `vehicle_class_id` smallint(5) unsigned NOT NULL,
  `status_id` smallint(5) unsigned NOT NULL,
  `status_changed_on` datetime(6) NOT NULL,
  `valid_from` datetime(6) DEFAULT NULL COMMENT 'Authorisation valid from',
  `expiry_date` datetime(6) DEFAULT NULL COMMENT 'Authorisation expiry date ',
  `fuel_type_id` smallint(5) unsigned DEFAULT NULL COMMENT 'Currently unused, pending decision on whether is connected to vehicle type',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fuel_type_id` (`fuel_type_id`),
  KEY `fk_auth_for_testing_mot_at_site_auth_status_id` (`status_id`),
  KEY `fk_auth_for_testing_mot_at_site_site_id` (`site_id`),
  KEY `fk_auth_site_vehicle_class_id` (`vehicle_class_id`),
  KEY `fk_auth_for_testing_mot_at_site_person_created_by` (`created_by`),
  KEY `fk_auth_for_testing_mot_at_site_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_auth_for_testing_mot_at_site_auth_status_id` FOREIGN KEY (`status_id`) REFERENCES `auth_for_testing_mot_at_site_status` (`id`),
  CONSTRAINT `fk_auth_for_testing_mot_at_site_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_auth_for_testing_mot_at_site_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_auth_for_testing_mot_at_site_site_id` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
  CONSTRAINT `fk_auth_site_vehicle_class_id` FOREIGN KEY (`vehicle_class_id`) REFERENCES `vehicle_class` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COMMENT='Record of the authorisation for a site to conduct an MOT';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `auth_for_testing_mot_at_site_status`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_for_testing_mot_at_site_status` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_auth_for_testing_mot_at_site_status_code` (`code`),
  KEY `fk_auth_for_testing_mot_at_site_status_person_created_by` (`created_by`),
  KEY `fk_auth_for_testing_mot_at_site_status_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_auth_for_testing_mot_at_site_status_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_auth_for_testing_mot_at_site_status_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Normalisation for the status of an authorisation for a site to conduct an MOT.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `auth_for_testing_mot_role_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_for_testing_mot_role_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vehicle_class_id` smallint(5) unsigned NOT NULL,
  `auth_status_id` smallint(5) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_auth_for_testing_mot_role_map_person_created_by` (`created_by`),
  KEY `fk_auth_for_testing_mot_role_map_person_last_updated_by` (`last_updated_by`),
  KEY `fk_auth_for_testing_mot_role_map_vehicle_class_id` (`vehicle_class_id`),
  KEY `fk_auth_for_testing_mot_role_map_auth_for_testing_mot_status_id` (`auth_status_id`),
  KEY `fk_auth_for_testing_mot_role_map_role_id` (`role_id`),
  CONSTRAINT `fk_auth_for_testing_mot_role_map_auth_for_testing_mot_status_id` FOREIGN KEY (`auth_status_id`) REFERENCES `auth_for_testing_mot_status` (`id`),
  CONSTRAINT `fk_auth_for_testing_mot_role_map_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_auth_for_testing_mot_role_map_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_auth_for_testing_mot_role_map_role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`),
  CONSTRAINT `fk_auth_for_testing_mot_role_map_vehicle_class_id` FOREIGN KEY (`vehicle_class_id`) REFERENCES `vehicle_class` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `auth_for_testing_mot_status`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_for_testing_mot_status` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_auth_for_testing_mot_status_code` (`code`),
  KEY `fk_auth_for_testing_mot_status_person_created_by` (`created_by`),
  KEY `fk_auth_for_testing_mot_status_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_auth_for_testing_mot_status_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_auth_for_testing_mot_status_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='Normalisation for the status of an authorisation for a person to conduct an MOT.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `auth_status`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_status` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_auth_status_code` (`code`),
  KEY `fk_auth_status_person_created_by` (`created_by`),
  KEY `fk_auth_status_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_auth_status_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_auth_status_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='Normalisation for the status of an authorisation for a person to conduct an MOT.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `body_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `body_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `display_order` smallint(5) unsigned NOT NULL DEFAULT '1',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_body_type_code` (`code`),
  UNIQUE KEY `uk_body_type_display_order` (`display_order`),
  KEY `fk_body_type_created_by_person_id` (`created_by`),
  KEY `fk_body_type_last_updated_by_person_id` (`last_updated_by`),
  CONSTRAINT `fk_body_type_created_by_person_id` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_body_type_last_updated_by_person_id` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8 COMMENT='Will be removed - normalisation of unused data';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brake_test_result_class_1_2`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brake_test_result_class_1_2` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mot_test_id` bigint(20) unsigned NOT NULL,
  `brake_test_type_id` smallint(5) unsigned NOT NULL,
  `vehicle_weight_front` smallint(6) unsigned DEFAULT NULL,
  `vehicle_weight_rear` smallint(6) unsigned DEFAULT NULL,
  `rider_weight` smallint(6) unsigned DEFAULT NULL,
  `sidecar_weight` smallint(6) unsigned DEFAULT NULL,
  `control_1_effort_front` int(11) DEFAULT NULL,
  `control_1_effort_rear` int(11) DEFAULT NULL,
  `control_1_effort_sidecar` int(11) DEFAULT NULL,
  `control_2_effort_front` int(11) DEFAULT NULL,
  `control_2_effort_rear` int(11) DEFAULT NULL,
  `control_2_effort_sidecar` int(11) DEFAULT NULL,
  `control_1_lock_front` tinyint(4) DEFAULT NULL,
  `control_1_lock_rear` tinyint(4) DEFAULT NULL,
  `control_2_lock_front` tinyint(4) DEFAULT NULL,
  `control_2_lock_rear` tinyint(4) DEFAULT NULL,
  `control_1_brake_efficiency` smallint(5) unsigned DEFAULT NULL,
  `control_2_brake_efficiency` smallint(5) unsigned DEFAULT NULL,
  `gradient_control_1_below_minimum` tinyint(4) DEFAULT NULL,
  `gradient_control_2_below_minimum` tinyint(4) DEFAULT NULL,
  `control_1_efficiency_pass` tinyint(4) NOT NULL,
  `control_2_efficiency_pass` tinyint(4) DEFAULT NULL,
  `general_pass` tinyint(4) NOT NULL,
  `is_latest` tinyint(4) unsigned NOT NULL DEFAULT '1',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `fk_brake_test_result_class_1_2_mot_test_id` (`mot_test_id`),
  KEY `fk_brake_test_result_class_1_2_brake_test_type_id` (`brake_test_type_id`),
  CONSTRAINT `brake_test_result_class_1_2_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `brake_test_result_class_1_2_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_brake_test_result_class_1_2_brake_test_type_id` FOREIGN KEY (`brake_test_type_id`) REFERENCES `brake_test_type` (`id`),
  CONSTRAINT `fk_brake_test_result_class_1_2_mot_test_id` FOREIGN KEY (`mot_test_id`) REFERENCES `mot_test` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Record of brake test inputs and results for vehicle classes 1 and 2';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brake_test_result_class_3_and_above`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brake_test_result_class_3_and_above` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mot_test_id` bigint(20) unsigned NOT NULL,
  `service_brake_1_test_type_id` smallint(5) unsigned NOT NULL,
  `service_brake_2_test_type_id` smallint(5) unsigned DEFAULT NULL,
  `parking_brake_test_type_id` smallint(5) unsigned NOT NULL,
  `service_brake_total_axles_applied_to` tinyint(4) DEFAULT NULL,
  `parking_brake_total_axles_applied_to` tinyint(4) DEFAULT NULL,
  `service_brake_1_data_id` bigint(20) unsigned DEFAULT NULL,
  `service_brake_2_data_id` bigint(20) unsigned DEFAULT NULL,
  `parking_brake_effort_nearside` int(11) DEFAULT NULL,
  `parking_brake_effort_offside` int(11) DEFAULT NULL,
  `parking_brake_effort_secondary_nearside` int(11) DEFAULT NULL,
  `parking_brake_effort_secondary_offside` int(11) DEFAULT NULL,
  `parking_brake_effort_single` int(11) DEFAULT NULL,
  `parking_brake_lock_nearside` tinyint(4) DEFAULT NULL,
  `parking_brake_lock_offside` tinyint(4) DEFAULT NULL,
  `parking_brake_lock_secondary_nearside` tinyint(4) DEFAULT NULL,
  `parking_brake_lock_secondary_offside` tinyint(4) DEFAULT NULL,
  `parking_brake_lock_single` tinyint(4) DEFAULT NULL,
  `service_brake_is_single_line` tinyint(4) NOT NULL,
  `is_single_in_front` tinyint(4) unsigned DEFAULT NULL,
  `is_commercial_vehicle` tinyint(4) DEFAULT NULL,
  `vehicle_weight` int(10) unsigned DEFAULT NULL COMMENT 'in Kg',
  `weight_type_id` smallint(5) unsigned DEFAULT NULL,
  `weight_is_unladen` tinyint(4) DEFAULT NULL,
  `service_brake_1_efficiency` smallint(5) unsigned DEFAULT NULL,
  `service_brake_2_efficiency` smallint(5) unsigned DEFAULT NULL,
  `parking_brake_efficiency` smallint(5) unsigned DEFAULT NULL,
  `service_brake_1_efficiency_pass` tinyint(4) DEFAULT NULL,
  `service_brake_2_efficiency_pass` tinyint(4) DEFAULT NULL,
  `parking_brake_efficiency_pass` tinyint(4) DEFAULT NULL,
  `parking_brake_imbalance` tinyint(4) DEFAULT NULL,
  `parking_brake_secondary_imbalance` tinyint(4) DEFAULT NULL,
  `parking_brake_imbalance_pass` tinyint(4) DEFAULT NULL,
  `general_pass` tinyint(4) NOT NULL,
  `is_latest` tinyint(4) unsigned NOT NULL DEFAULT '1',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `service_brake_1_result` (`service_brake_1_data_id`),
  KEY `service_brake_2_result` (`service_brake_2_data_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `fk_brake_test_result_class_3_and_above_mot_test_id` (`mot_test_id`),
  KEY `fk_brake_test_result_class_3_above_service_brake_1_test_type_id` (`service_brake_1_test_type_id`),
  KEY `fk_brake_test_result_class_3_above_service_brake_2_test_type_id` (`service_brake_2_test_type_id`),
  KEY `fk_brake_test_result_class_3_above_parking_brake_test_type_id` (`parking_brake_test_type_id`),
  KEY `fk_brake_test_result_class_3_and_above_weight_type_id` (`weight_type_id`),
  CONSTRAINT `brake_test_result_class_3_and_above_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `brake_test_result_class_3_and_above_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_brake_test_result_class_3_above_parking_brake_test_type_id` FOREIGN KEY (`parking_brake_test_type_id`) REFERENCES `brake_test_type` (`id`),
  CONSTRAINT `fk_brake_test_result_class_3_above_service_brake_1_test_type_id` FOREIGN KEY (`service_brake_1_test_type_id`) REFERENCES `brake_test_type` (`id`),
  CONSTRAINT `fk_brake_test_result_class_3_above_service_brake_2_test_type_id` FOREIGN KEY (`service_brake_2_test_type_id`) REFERENCES `brake_test_type` (`id`),
  CONSTRAINT `fk_brake_test_result_class_3_and_above_mot_test_id` FOREIGN KEY (`mot_test_id`) REFERENCES `mot_test` (`id`),
  CONSTRAINT `fk_brake_test_result_class_3_and_above_weight_type_id` FOREIGN KEY (`weight_type_id`) REFERENCES `weight_source_lookup` (`id`),
  CONSTRAINT `fk_brake_test_result_service_brake_data_service_brake_1` FOREIGN KEY (`service_brake_1_data_id`) REFERENCES `brake_test_result_service_brake_data` (`id`),
  CONSTRAINT `fk_brake_test_result_service_brake_data_service_brake_2` FOREIGN KEY (`service_brake_2_data_id`) REFERENCES `brake_test_result_service_brake_data` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Record of brake test inputs and results for vehicle classes 3 to 7';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brake_test_result_service_brake_data`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brake_test_result_service_brake_data` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `effort_nearside_axle1` int(11) DEFAULT NULL,
  `effort_offside_axle1` int(11) DEFAULT NULL,
  `effort_nearside_axle2` int(11) DEFAULT NULL,
  `effort_offside_axle2` int(11) DEFAULT NULL,
  `effort_nearside_axle3` int(11) DEFAULT NULL,
  `effort_offside_axle3` int(11) DEFAULT NULL,
  `effort_single` int(11) DEFAULT NULL,
  `lock_nearside_axle1` tinyint(4) DEFAULT NULL,
  `lock_offside_axle1` tinyint(4) DEFAULT NULL,
  `lock_nearside_axle2` tinyint(4) DEFAULT NULL,
  `lock_offside_axle2` tinyint(4) DEFAULT NULL,
  `lock_nearside_axle3` tinyint(4) DEFAULT NULL,
  `lock_offside_axle3` tinyint(4) DEFAULT NULL,
  `lock_single` tinyint(4) DEFAULT NULL,
  `imbalance_axle1` tinyint(4) DEFAULT NULL,
  `imbalance_axle2` tinyint(4) DEFAULT NULL,
  `imbalance_axle3` tinyint(4) DEFAULT NULL,
  `imbalance_pass` tinyint(4) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `brake_test_result_service_brake_data_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `brake_test_result_service_brake_data_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Record of service brake inputs and results';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brake_test_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brake_test_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `display_order` smallint(5) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_brake_test_type_name` (`name`),
  UNIQUE KEY `uk_brake_test_type_code` (`code`),
  UNIQUE KEY `uk_brake_test_type_display_order` (`display_order`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `brake_test_type_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `brake_test_type_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Normalisation of types of brake test';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `business_role_status`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `business_role_status` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `business_role_status_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `business_role_status_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='Status of a business role in the system';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `business_rule`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `business_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `definition` text,
  `business_rule_type_id` smallint(5) unsigned NOT NULL,
  `comparison` varchar(2) DEFAULT NULL,
  `date_value` date DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_rfr_business_rule_rfr_rule_type1` (`business_rule_type_id`),
  KEY `fk_business_rule_created` (`created_by`),
  KEY `fk_business_rule_updated` (`last_updated_by`),
  CONSTRAINT `fk_business_rule_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_business_rule_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_rfr_business_rule_rfr_rule_type1` FOREIGN KEY (`business_rule_type_id`) REFERENCES `business_rule_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='These are for filtering the RFR/Category to make them more appropriate for the vehicle';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `business_rule_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `business_rule_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_business_rule_type_created` (`created_by`),
  KEY `fk_business_rule_type_updated` (`last_updated_by`),
  CONSTRAINT `fk_business_rule_type_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_business_rule_type_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Simple - only filter by class, Parameter - filter by class and first used date, Complex - any other rule';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `card_payment_token_usage`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `card_payment_token_usage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `payment_id` int(10) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `fk_card_payment_token_usage_payment` (`payment_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `card_payment_token_usage_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `card_payment_token_usage_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_card_payment_token_usage_payment` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Duplicate of test_slot_transaction?';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `censor_blacklist`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `censor_blacklist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phrase` varchar(100) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `phrase` (`phrase`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `censor_blacklist_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `censor_blacklist_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=343 DEFAULT CHARSET=utf8 COMMENT='List of blacklisted words which the system can use to filter input to the system';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `certificate_change_different_tester_reason_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `certificate_change_different_tester_reason_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) NOT NULL,
  `description` varchar(100) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `certificate_change_different_tester_reason_lookup_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `certificate_change_different_tester_reason_lookup_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Normalisation of why a certificate replacement is being performed by a different tester';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `certificate_replacement`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `certificate_replacement` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `mot_test_id` bigint(20) unsigned NOT NULL,
  `mot_test_version` int(10) unsigned NOT NULL COMMENT 'version of test to identify record in history table if not current record',
  `different_tester_reason_id` smallint(5) unsigned DEFAULT NULL COMMENT 'TODO: should be NOT NULL according to SDM',
  `document_id` bigint(20) unsigned DEFAULT NULL,
  `certificate_status_id` smallint(5) unsigned DEFAULT NULL COMMENT 'TODO: should be NOT NULL according to SDM, but just for now we keep it default null',
  `tester_person_id` int(10) unsigned DEFAULT NULL COMMENT 'Person who entered/completed the test/certificate reissue data',
  `reason` text,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_certificate_replacement_mot_test_id` (`mot_test_id`),
  KEY `fk_certificate_replacement_person_created_by` (`created_by`),
  KEY `fk_certificate_replacement_person_last_updated_by` (`last_updated_by`),
  KEY `fk_certificate_replacement_person_different_tester_reason_id` (`different_tester_reason_id`),
  KEY `fk_certificate_replacement_certificate_status_id` (`certificate_status_id`),
  KEY `fk_certificate_replacement_document_id` (`document_id`),
  KEY `fk_certificate_replacement_person_tester_person_id` (`tester_person_id`),
  CONSTRAINT `fk_certificate_replacement_certificate_status_id` FOREIGN KEY (`certificate_status_id`) REFERENCES `certificate_status` (`id`),
  CONSTRAINT `fk_certificate_replacement_document_id` FOREIGN KEY (`document_id`) REFERENCES `jasper_document` (`id`),
  CONSTRAINT `fk_certificate_replacement_mot_test_id` FOREIGN KEY (`mot_test_id`) REFERENCES `mot_test` (`id`),
  CONSTRAINT `fk_certificate_replacement_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_certificate_replacement_person_different_tester_reason_id` FOREIGN KEY (`different_tester_reason_id`) REFERENCES `certificate_change_different_tester_reason_lookup` (`id`),
  CONSTRAINT `fk_certificate_replacement_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_certificate_replacement_person_tester_person_id` FOREIGN KEY (`tester_person_id`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `certificate_status`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `certificate_status` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `display_order` smallint(5) unsigned NOT NULL DEFAULT '1',
  `code` varchar(5) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_certificate_status_created` (`created_by`),
  KEY `fk_certificate_status_updated` (`last_updated_by`),
  CONSTRAINT `fk_certificate_status_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_certificate_status_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Normalisation of the MOT test status';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `colour_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `colour_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `display_order` smallint(5) unsigned NOT NULL DEFAULT '1',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `colour_lookup_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `colour_lookup_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='DVLA vehicle colours';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comment`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment` text NOT NULL,
  `author_person_id` int(10) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `fk_comment_person_id` (`author_person_id`),
  CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_comment_person_id` FOREIGN KEY (`author_person_id`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Records comments made about various entities in the system';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `company_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_company_type_code` (`code`),
  KEY `fk_company_type_person_created_by` (`created_by`),
  KEY `fk_company_type_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_company_type_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_company_type_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Normalisation of an organisations legal status';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `configuration`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `configuration` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(50) NOT NULL,
  `value` varchar(50) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `config_key` (`key`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `configuration_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `configuration_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='Configurable values within the system that can be changed without a redeployment';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contact_detail`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `for_attention_of` varchar(50) DEFAULT NULL,
  `address_id` int(10) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_contact_detail_address` (`address_id`),
  KEY `fk_contact_detail_person_created_by` (`created_by`),
  KEY `fk_contact_detail_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_contact_detail_address` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`),
  CONSTRAINT `fk_contact_detail_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_contact_detail_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25012 DEFAULT CHARSET=utf8 COMMENT='Container object to link all the contact elements, such as address, phone and email.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contact_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `code` varchar(5) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_contact_type_created` (`created_by`),
  KEY `fk_contact_type_updated` (`last_updated_by`),
  CONSTRAINT `fk_contact_type_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_contact_type_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `content_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_content_type_created` (`created_by`),
  KEY `fk_content_type_updated` (`last_updated_by`),
  CONSTRAINT `fk_content_type_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_content_type_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `conviction`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conviction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `application_id` int(10) unsigned NOT NULL,
  `reference` varchar(100) NOT NULL,
  `date_time` date NOT NULL,
  `court` varchar(50) NOT NULL,
  `offence` varchar(50) NOT NULL,
  `creation_time` datetime(6) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `fk_conviction_application` (`application_id`),
  CONSTRAINT `conviction_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `conviction_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_conviction_application` FOREIGN KEY (`application_id`) REFERENCES `application` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Conviction held by a person. Used in the application process.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `country_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  `display_order` smallint(5) unsigned DEFAULT NULL,
  `iso_code` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_country_lookup_code` (`code`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `fk_country_lookup_person_created_by` (`created_by`),
  KEY `fk_country_lookup_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_country_lookup_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_country_lookup_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=261 DEFAULT CHARSET=utf8 COMMENT='Normalisation of the country of registration for a vehicle and certificate replacement records';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `country_of_registration_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country_of_registration_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `country_lookup_id` smallint(5) unsigned DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `licensing_copy` varchar(5) CHARACTER SET latin1 NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_country_of_registration_lookup_country_lookup_id` (`country_lookup_id`),
  KEY `fk_country_of_registration_lookup_person_created_by` (`created_by`),
  KEY `fk_country_of_registration_lookup_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_country_of_registration_lookup_country_lookup_id` FOREIGN KEY (`country_lookup_id`) REFERENCES `country_lookup` (`id`) ON DELETE NO ACTION ON UPDATE SET NULL,
  CONSTRAINT `fk_country_of_registration_lookup_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_country_of_registration_lookup_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `direct_debit`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `direct_debit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` int(10) unsigned NOT NULL,
  `person_id` int(10) unsigned NOT NULL,
  `status_id` smallint(5) unsigned NOT NULL,
  `mandate_id` varchar(50) DEFAULT NULL,
  `slots` smallint(5) unsigned NOT NULL,
  `setup_date` datetime NOT NULL,
  `next_collection_date` date NOT NULL,
  `last_increment_date` date DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mandate_id` (`mandate_id`),
  KEY `fk_direct_debit_organisation` (`organisation_id`),
  KEY `fk_direct_debit_person` (`person_id`),
  KEY `fk_direct_debit_status` (`status_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `direct_debit_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `direct_debit_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_direct_debit_organisation` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`),
  CONSTRAINT `fk_direct_debit_person` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_direct_debit_status` FOREIGN KEY (`status_id`) REFERENCES `direct_debit_status` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Direct debit mandates of organisations wanting to purchase slots regularly';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `direct_debit_history`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `direct_debit_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `direct_debit_id` int(10) unsigned NOT NULL,
  `transaction_id` int(10) unsigned DEFAULT NULL,
  `status_id` smallint(5) unsigned NOT NULL,
  `increment_date` datetime NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `direct_debit_id` (`direct_debit_id`),
  KEY `status_id` (`status_id`),
  KEY `transaction_id` (`transaction_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `direct_debit_history_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `direct_debit_history_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_direct_debit_history_dd_id` FOREIGN KEY (`direct_debit_id`) REFERENCES `direct_debit` (`id`),
  CONSTRAINT `fk_direct_debit_history_status` FOREIGN KEY (`status_id`) REFERENCES `direct_debit_history_status` (`id`),
  CONSTRAINT `fk_direct_debit_history_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `test_slot_transaction` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `direct_debit_history_status`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `direct_debit_history_status` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `direct_debit_history_status_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `direct_debit_history_status_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `direct_debit_status`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `direct_debit_status` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `direct_debit_status_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `direct_debit_status_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Normalisation of status of direct debit mandate';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dvla_make`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dvla_make` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_dvla_make_code` (`code`),
  KEY `fk_dvla_make_person_created_by` (`created_by`),
  KEY `fk_dvla_make_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_dvla_make_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_dvla_make_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='DVLA vehicle makes';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dvla_model`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dvla_model` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT 'Sometimes not provided during DVLA batch import process',
  `make_code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_dvla_model_make_model_code` (`make_code`,`code`),
  KEY `fk_dvla_model_person_created_by` (`created_by`),
  KEY `fk_dvla_model_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_dvla_model_dvla_make_code` FOREIGN KEY (`make_code`) REFERENCES `dvla_make` (`code`),
  CONSTRAINT `fk_dvla_model_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_dvla_model_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='DVLA vehicle models';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dvla_model_model_detail_code_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dvla_model_model_detail_code_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dvla_make_code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `dvla_model_code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `make_id` int(10) unsigned NOT NULL,
  `model_id` int(10) unsigned DEFAULT NULL,
  `model_detail_id` int(10) unsigned DEFAULT NULL,
  `vsi_code` varchar(10) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_dvla_model_model_detail_code_map_make` (`make_id`),
  KEY `fk_dvla_model_model_detail_code_map_model` (`model_id`),
  KEY `fk_dvla_model_model_detail_code_map_model_detail` (`model_detail_id`),
  KEY `fk_dvla_model_model_detail_code_map_created` (`created_by`),
  KEY `fk_dvla_model_model_detail_code_map_updated` (`last_updated_by`),
  KEY `fk_dvla_model_model_detail_code_map_dvla_model` (`dvla_make_code`,`dvla_model_code`),
  CONSTRAINT `fk_dvla_model_model_detail_code_map_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_dvla_model_model_detail_code_map_dvla_make_code` FOREIGN KEY (`dvla_make_code`) REFERENCES `dvla_make` (`code`),
  CONSTRAINT `fk_dvla_model_model_detail_code_map_dvla_model_code` FOREIGN KEY (`dvla_make_code`, `dvla_model_code`) REFERENCES `dvla_model` (`make_code`, `code`),
  CONSTRAINT `fk_dvla_model_model_detail_code_map_make` FOREIGN KEY (`make_id`) REFERENCES `make` (`id`),
  CONSTRAINT `fk_dvla_model_model_detail_code_map_model` FOREIGN KEY (`model_id`) REFERENCES `model` (`id`),
  CONSTRAINT `fk_dvla_model_model_detail_code_map_model_detail` FOREIGN KEY (`model_detail_id`) REFERENCES `model_detail` (`id`),
  CONSTRAINT `fk_dvla_model_model_detail_code_map_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dvla_vehicle`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dvla_vehicle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `registration` varchar(7) CHARACTER SET latin1 DEFAULT NULL,
  `registration_validation_character` varchar(1) DEFAULT NULL,
  `vin` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `model_code` varchar(5) NOT NULL COMMENT 'should be char 3 and data should be retrieved from `dvla_model`, not `model`',
  `make_code` varchar(5) NOT NULL COMMENT 'should be char 2 and data should be retrieved from `dvla_make`, not `make`',
  `make_in_full` varchar(20) DEFAULT NULL COMMENT 'Make Full Text populated when the make id and model id are null',
  `colour_1_code` varchar(1) DEFAULT NULL,
  `colour_2_code` varchar(1) DEFAULT NULL,
  `propulsion_code` varchar(2) DEFAULT NULL,
  `designed_gross_weight` int(11) DEFAULT NULL,
  `unladen_weight` int(11) DEFAULT NULL,
  `engine_number` varchar(20) DEFAULT NULL,
  `engine_capacity` int(11) DEFAULT NULL,
  `seating_capacity` smallint(5) unsigned DEFAULT NULL,
  `manufacture_date` date DEFAULT NULL,
  `first_registration_date` date NOT NULL,
  `is_seriously_damaged` tinyint(4) DEFAULT NULL,
  `recent_v5_document_number` varchar(11) DEFAULT NULL,
  `is_vehicle_new_at_first_registration` tinyint(4) DEFAULT NULL,
  `body_type_code` varchar(2) DEFAULT NULL,
  `wheelplan_code` varchar(1) CHARACTER SET latin1 DEFAULT NULL,
  `sva_emission_standard` varchar(6) DEFAULT NULL,
  `ct_related_mark` varchar(13) DEFAULT NULL,
  `vehicle_id` int(10) unsigned DEFAULT NULL,
  `dvla_vehicle_id` int(9) unsigned DEFAULT NULL,
  `eu_classification` varchar(2) DEFAULT NULL,
  `mass_in_service_weight` int(9) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `vehicle_id` (`vehicle_id`),
  KEY `fk_dvla_vehicle_colour_1` (`colour_1_code`),
  KEY `fk_dvla_vehicle_colour_2` (`colour_2_code`),
  KEY `fk_dvla_vehicle_created` (`created_by`),
  KEY `fk_dvla_vehicle_updated` (`last_updated_by`),
  KEY `ix_dvla_vehicle_dvla_vehicle_id` (`dvla_vehicle_id`),
  KEY `ix_dvla_vehicle_registration` (`registration`),
  KEY `ix_dvla_vehicle_vin` (`vin`),
  CONSTRAINT `fk_dvla_vehicle_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_dvla_vehicle_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_dvla_vehicle_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Vehicle data imported from the DVLA';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dvla_vehicle_import_change_log`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dvla_vehicle_import_change_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vehicle_id` int(10) unsigned NOT NULL,
  `vehicle_class_id` smallint(5) unsigned NOT NULL,
  `main_colour_code` varchar(5) NOT NULL COMMENT 'TODO:  CHARACTER SET latin1 ',
  `secondary_colour_code` varchar(5) DEFAULT NULL COMMENT 'TODO:  CHARACTER SET latin1 ',
  `fuel_type_code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `person_id` int(10) unsigned NOT NULL,
  `imported` datetime(6) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_dvla_vehicle_import_change_log_vehicle_class_id` (`vehicle_class_id`),
  KEY `fk_dvla_vehicle_import_change_log_vehicle_id` (`vehicle_id`),
  KEY `fk_dvla_vehicle_import_change_log_main_colour` (`main_colour_code`),
  KEY `fk_dvla_vehicle_import_change_log_secondary_colour` (`secondary_colour_code`),
  KEY `fk_dvla_vehicle_import_change_log_person_id` (`person_id`),
  KEY `fk_dvla_vehicle_import_change_log_person_created_by` (`created_by`),
  KEY `fk_dvla_vehicle_import_change_log_person_last_updated_by` (`last_updated_by`),
  KEY `fk_dvla_vehicle_import_change_log_fuel_type_code` (`fuel_type_code`),
  CONSTRAINT `fk_dvla_vehicle_import_change_log_fuel_type_code` FOREIGN KEY (`fuel_type_code`) REFERENCES `fuel_type` (`code`),
  CONSTRAINT `fk_dvla_vehicle_import_change_log_main_colour` FOREIGN KEY (`main_colour_code`) REFERENCES `colour_lookup` (`code`),
  CONSTRAINT `fk_dvla_vehicle_import_change_log_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_dvla_vehicle_import_change_log_person_id` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_dvla_vehicle_import_change_log_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_dvla_vehicle_import_change_log_secondary_colour` FOREIGN KEY (`secondary_colour_code`) REFERENCES `colour_lookup` (`code`),
  CONSTRAINT `fk_dvla_vehicle_import_change_log_vehicle_class_id` FOREIGN KEY (`vehicle_class_id`) REFERENCES `vehicle_class` (`id`),
  CONSTRAINT `fk_dvla_vehicle_import_change_log_vehicle_id` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Log of changes made to DVLA vehicle data by tester at start of test';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `email`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contact_detail_id` int(10) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_primary` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_email_contact_detail_id` (`contact_detail_id`),
  KEY `fk_email_person_created_by` (`created_by`),
  KEY `fk_email_last_person_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_email_contact_detail_id` FOREIGN KEY (`contact_detail_id`) REFERENCES `contact_detail` (`id`),
  CONSTRAINT `fk_email_last_person_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_email_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='Representation of a email address';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `emergency_log`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emergency_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(10) NOT NULL COMMENT 'The emergency log code',
  `description` varchar(250) DEFAULT NULL COMMENT 'Full text description to explain reason for the code',
  `start_date` date DEFAULT NULL COMMENT 'Starting date for the contingency code',
  `end_date` date DEFAULT NULL COMMENT 'Ending date for using this code',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_emergency_log_created` (`created_by`),
  KEY `fk_emergency_log_updated` (`last_updated_by`),
  CONSTRAINT `fk_emergency_log_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_emergency_log_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Log of emergency outages and the codes used';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `emergency_reason_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emergency_reason_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT 'Short name displayed to users in the GUI',
  `code` varchar(5) NOT NULL COMMENT 'Internal code assigned to contingency testing reasons',
  `description` varchar(50) DEFAULT NULL COMMENT 'Longer descriptive text for the reason code',
  `display_order` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT 'Preferred display order',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `fk_emergency_reason_lookup_created` (`created_by`),
  KEY `fk_emergency_reason_lookup_last_updated` (`last_updated_by`),
  CONSTRAINT `fk_emergency_reason_lookup_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_emergency_reason_lookup_last_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Codes and descriptions for reasons due to which contingency testing has to take place';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empty_vin_reason_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empty_vin_reason_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `name` varchar(50) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_empty_vin_reason_lookup_created` (`created_by`),
  KEY `fk_empty_vin_reason_lookup_updated` (`last_updated_by`),
  CONSTRAINT `fk_empty_vin_reason_lookup_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_empty_vin_reason_lookup_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empty_vrm_reason_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empty_vrm_reason_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `name` varchar(50) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_empty_vrm_reason_lookup_created` (`created_by`),
  KEY `fk_empty_vrm_reason_lookup_updated` (`last_updated_by`),
  CONSTRAINT `fk_empty_vrm_reason_lookup_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_empty_vrm_reason_lookup_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `enforcement_condition_appointment_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enforcement_condition_appointment_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(50) DEFAULT NULL,
  `display_order` smallint(5) unsigned NOT NULL DEFAULT '1',
  `code` varchar(5) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `enforcement_condition_appointment_lookup_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `enforcement_condition_appointment_lookup_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='Descriptions of conditions of approval for recording a new Site by the VE';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `enforcement_decision_category_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enforcement_decision_category_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(50) DEFAULT NULL COMMENT 'The outcome category description',
  `display_order` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT 'Preferred display order',
  `code` varchar(5) DEFAULT NULL COMMENT 'Internal representation code',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `enforcement_decision_category_lookup_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `enforcement_decision_category_lookup_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Enumerates the decisions a VE can make at the RFR level when comparing a VE Test to a Testers Test';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `enforcement_decision_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enforcement_decision_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `decision` varchar(100) NOT NULL COMMENT 'Plain text description of the decision',
  `display_order` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT 'Preferred display order',
  `code` varchar(5) DEFAULT NULL COMMENT 'Internal representation code',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `enforcement_decision_lookup_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `enforcement_decision_lookup_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Comparison screen decision results';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `enforcement_decision_outcome_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enforcement_decision_outcome_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `outcome` varchar(50) DEFAULT NULL COMMENT 'Plain text description of the outcome result',
  `display_order` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT 'Preferred display order',
  `code` varchar(5) DEFAULT NULL COMMENT 'Internal representation code',
  `mot1_legacy_id` varchar(80) DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `enforcement_decision_outcome_lookup_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `enforcement_decision_outcome_lookup_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Enforcement decision outcome reasons';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `enforcement_decision_reinspection_outcome_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enforcement_decision_reinspection_outcome_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `decision` varchar(50) DEFAULT NULL COMMENT 'Textual description of the outcome value',
  `display_order` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT 'Preferred display order',
  `code` varchar(5) DEFAULT NULL COMMENT 'Internal representation code',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `enforcement_decision_reinspection_outcome_lookup_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `enforcement_decision_reinspection_outcome_lookup_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='List of approved reinspection outcomes';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `enforcement_decision_score_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enforcement_decision_score_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `score` smallint(5) unsigned DEFAULT NULL COMMENT 'The numerical value of this score',
  `description` varchar(50) NOT NULL COMMENT 'The descriptive text for the score',
  `display_order` smallint(5) unsigned DEFAULT NULL COMMENT 'Preferred display order',
  `code` varchar(5) DEFAULT NULL COMMENT 'Internal representation code',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `enforcement_decision_score_lookup_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `enforcement_decision_score_lookup_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='Comparison screen decision score selections';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `enforcement_fuel_type_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enforcement_fuel_type_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(50) DEFAULT NULL COMMENT 'Descriptive text for the fuel type',
  `display_order` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT 'Preferred display order',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `enforcement_fuel_type_lookup_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `enforcement_fuel_type_lookup_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Permissible fuel types for which a Site is approved';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `enforcement_full_partial_retest_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enforcement_full_partial_retest_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT 'Printable name for the test type',
  `description` varchar(50) DEFAULT NULL COMMENT 'Text description for the test type',
  `code` varchar(5) DEFAULT NULL COMMENT 'Internal representation code',
  `display_order` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT 'Preferred display order',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `enforcement_full_partial_retest_lookup_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `enforcement_full_partial_retest_lookup_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Record whether the VEs Test is full or partial';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `enforcement_mot_demo_test`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enforcement_mot_demo_test` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mot_test_id` bigint(20) unsigned NOT NULL COMMENT 'The MOT test demo test identifier',
  `result_id` tinyint(4) NOT NULL COMMENT 'The result of the demonstration test',
  `is_satisfactory` tinyint(4) NOT NULL DEFAULT '0',
  `comment_id` bigint(20) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_enforcement_mot_demo_test_comment` (`comment_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `fk_enforcement_mot_demo_test_mot_test_id` (`mot_test_id`),
  CONSTRAINT `enforcement_mot_demo_test_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `enforcement_mot_demo_test_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_enforcement_mot_demo_test_comment` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`),
  CONSTRAINT `fk_enforcement_mot_demo_test_mot_test_id` FOREIGN KEY (`mot_test_id`) REFERENCES `mot_test` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Demonstration tests, not for public viewing';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `enforcement_mot_test_differences`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enforcement_mot_test_differences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `enforcement_mot_test_result_id` int(10) unsigned NOT NULL,
  `rfr_id` int(10) unsigned DEFAULT NULL COMMENT 'denormalisation',
  `mot_test_id` bigint(20) unsigned NOT NULL COMMENT 'denormalisation',
  `mot_test_rfr_map_id` bigint(20) unsigned NOT NULL,
  `mot_test_type_id` smallint(5) unsigned NOT NULL COMMENT 'denormalisation',
  `enforcement_decision_score_lookup_id` smallint(5) unsigned NOT NULL,
  `enforcement_decision_lookup_id` smallint(5) unsigned NOT NULL,
  `enforcement_decision_category_lookup_id` smallint(5) unsigned NOT NULL,
  `comment_id` bigint(20) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_enforcement_mot_test_result` (`enforcement_mot_test_result_id`),
  KEY `fk_test_item_selector_rfr` (`rfr_id`),
  KEY `fk_mot_test_rfr_map` (`mot_test_rfr_map_id`),
  KEY `fk_test_type` (`mot_test_type_id`),
  KEY `fk_enforcement_decision_score_lookup` (`enforcement_decision_score_lookup_id`),
  KEY `fk_enforcement_decision_lookup` (`enforcement_decision_lookup_id`),
  KEY `fk_enforcement_decision_category_lookup` (`enforcement_decision_category_lookup_id`),
  KEY `comment_id` (`comment_id`),
  KEY `fk_enforcement_mot_test_difference_mot_test_id` (`mot_test_id`),
  KEY `fk_enforcement_mot_test_differences_rfr_id` (`rfr_id`),
  CONSTRAINT `fk_comment_enforcement_mot_test_differences` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`),
  CONSTRAINT `fk_enforcement_decision_category_lookup` FOREIGN KEY (`enforcement_decision_category_lookup_id`) REFERENCES `enforcement_decision_category_lookup` (`id`),
  CONSTRAINT `fk_enforcement_decision_lookup` FOREIGN KEY (`enforcement_decision_lookup_id`) REFERENCES `enforcement_decision_lookup` (`id`),
  CONSTRAINT `fk_enforcement_decision_score_lookup` FOREIGN KEY (`enforcement_decision_score_lookup_id`) REFERENCES `enforcement_decision_score_lookup` (`id`),
  CONSTRAINT `fk_enforcement_mot_test_difference_mot_test_id` FOREIGN KEY (`mot_test_id`) REFERENCES `mot_test` (`id`),
  CONSTRAINT `fk_enforcement_mot_test_differences_rfr_id` FOREIGN KEY (`rfr_id`) REFERENCES `reason_for_rejection` (`id`),
  CONSTRAINT `fk_enforcement_mot_test_result` FOREIGN KEY (`enforcement_mot_test_result_id`) REFERENCES `enforcement_mot_test_result` (`id`),
  CONSTRAINT `fk_mot_test_type_id` FOREIGN KEY (`mot_test_type_id`) REFERENCES `mot_test_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Differences between tests: the comparison process output';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `enforcement_mot_test_result`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enforcement_mot_test_result` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `re_inspection_mot_test_id` bigint(20) unsigned NOT NULL COMMENT 'Re-inspection MOT Test Id',
  `mot_test_id` bigint(20) unsigned NOT NULL COMMENT 'MOT Test Id',
  `total_score` smallint(5) unsigned NOT NULL COMMENT 'Total points awarded on completion of the test',
  `enforcement_decision_outcome_lookup_id` smallint(5) unsigned NOT NULL COMMENT 'The awarded outcome decision',
  `comment_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Link to free text comment made (optional)',
  `step` varchar(100) DEFAULT NULL COMMENT 'Application internal state: transition group operation',
  `enforcement_decision_reinspection_outcome_lookup_id` smallint(5) unsigned DEFAULT NULL,
  `awl_advice_given` varchar(255) DEFAULT NULL COMMENT 'Advisory warning letter: contents of the advisory warning letter text field',
  `awl_immediate_attention` varchar(255) DEFAULT NULL COMMENT 'Advisory warning letter: contents of the immediate attention text field',
  `awl_reply_comments` varchar(255) DEFAULT NULL COMMENT 'Advisory warning letter: comments from the VE who performed the test',
  `awl_name_a_ere` varchar(255) DEFAULT NULL COMMENT 'Advisory warning letter: Name of the AE of the Site the test took place at',
  `awl_mot_roles` varchar(255) DEFAULT NULL COMMENT 'Advisory warning letter: Roles',
  `awl_position_vts` varchar(255) DEFAULT NULL,
  `awl_user_id` varchar(255) DEFAULT NULL,
  `complainant_name` varchar(255) DEFAULT NULL COMMENT 'Who raised the need for the test',
  `complaint_detail` varchar(255) DEFAULT NULL COMMENT 'Details about the complainant',
  `repairs_detail` varchar(255) DEFAULT NULL,
  `complainant_address` varchar(255) DEFAULT NULL,
  `complainant_postcode` varchar(255) DEFAULT NULL,
  `complainant_phone_number` varchar(255) DEFAULT NULL,
  `ve_completed` varchar(255) DEFAULT NULL,
  `agree_vehicle_to_certificate` varchar(255) DEFAULT NULL,
  `input_agree_vehicle_to_certificate` varchar(255) DEFAULT NULL,
  `agree_vehicle_to_fail` varchar(255) DEFAULT NULL,
  `input_agree_vehicle_to_fail` varchar(255) DEFAULT NULL,
  `vehicle_switch` varchar(255) DEFAULT NULL,
  `input_vehicle_switch` varchar(255) DEFAULT NULL,
  `switch_police_status_report` varchar(255) DEFAULT NULL,
  `input_switch_detail_report` varchar(255) DEFAULT NULL,
  `switch_vehicle_result` varchar(255) DEFAULT NULL,
  `input_switch_police_status_report` varchar(255) DEFAULT NULL,
  `promote_sale_interest` varchar(255) DEFAULT NULL,
  `input_promote_sale_interest` varchar(255) DEFAULT NULL,
  `vehicle_defects` varchar(255) DEFAULT NULL,
  `reason_of_defects` varchar(255) DEFAULT NULL,
  `items_discussed` varchar(255) DEFAULT NULL,
  `concluding_remarks_tester` varchar(255) DEFAULT NULL,
  `concluding_remarks_ae` varchar(4000) DEFAULT NULL,
  `concluding_remarks_recommendation` varchar(4000) DEFAULT NULL,
  `concluding_remarks_name` varchar(200) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `enforcement_decision_outcome_lookup_id` (`enforcement_decision_outcome_lookup_id`),
  KEY `comment_id` (`comment_id`),
  KEY `fk_enforcement_decision_reinspection_outcome_lookup` (`enforcement_decision_reinspection_outcome_lookup_id`),
  KEY `fk_enforcement_mot_test_result_mot_test_id` (`mot_test_id`),
  KEY `fk_enforcement_mot_test_result_re_inspection_mot_test_id` (`re_inspection_mot_test_id`),
  CONSTRAINT `fk_comment` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`),
  CONSTRAINT `fk_enforcement_decision_outcome_lookup` FOREIGN KEY (`enforcement_decision_outcome_lookup_id`) REFERENCES `enforcement_decision_outcome_lookup` (`id`),
  CONSTRAINT `fk_enforcement_decision_reinspection_outcome_lookup` FOREIGN KEY (`enforcement_decision_reinspection_outcome_lookup_id`) REFERENCES `enforcement_decision_reinspection_outcome_lookup` (`id`),
  CONSTRAINT `fk_enforcement_mot_test_result_mot_test_id` FOREIGN KEY (`mot_test_id`) REFERENCES `mot_test` (`id`),
  CONSTRAINT `fk_enforcement_mot_test_result_re_inspection_mot_test_id` FOREIGN KEY (`re_inspection_mot_test_id`) REFERENCES `mot_test` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `enforcement_mot_test_result_witnesses`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enforcement_mot_test_result_witnesses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT 'Name of the witness',
  `position` varchar(255) DEFAULT NULL COMMENT 'Recognised position of the witness',
  `enforcement_mot_test_result_id` int(10) unsigned NOT NULL,
  `type` varchar(20) DEFAULT NULL COMMENT 'The type of the witness',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `enforcement_mot_test_result_id` (`enforcement_mot_test_result_id`),
  CONSTRAINT `enforcement_mot_test_result_witnesses_ibfk_1` FOREIGN KEY (`enforcement_mot_test_result_id`) REFERENCES `enforcement_mot_test_result` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Records witnesses to a test';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `enforcement_site_assessment`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enforcement_site_assessment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL COMMENT 'The site that was inspected / assessed',
  `site_assessment_score` decimal(9,2) DEFAULT NULL COMMENT 'The final asessment score',
  `authorisation_for_authorised_examiner_id` int(11) unsigned DEFAULT NULL,
  `ae_representative_name` varchar(100) DEFAULT NULL COMMENT 'Free text AE or AEs representatives name',
  `ae_representative_position` varchar(100) NOT NULL COMMENT 'Free text AE or AEs representative position in the organisation',
  `person_id` int(10) unsigned NOT NULL COMMENT 'Reference to the tester who was present during the on-site Site assessment',
  `visit_outcome_id` smallint(5) unsigned DEFAULT '1' COMMENT 'The selected outcome action of the visit',
  `advisory_issued` tinyint(4) DEFAULT '1' COMMENT '1 if an advisory warning letter was issued',
  `visit_date` datetime(6) NOT NULL COMMENT 'The date the visit took place',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `vts` (`site_id`),
  KEY `person` (`person_id`),
  KEY `authorised_examiner_id` (`authorisation_for_authorised_examiner_id`),
  KEY `visit_outcome_id` (`visit_outcome_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `enforcement_site_assessment_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `enforcement_site_assessment_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_enforcement_site_assessment_authorised_examiner_id` FOREIGN KEY (`authorisation_for_authorised_examiner_id`) REFERENCES `auth_for_ae` (`id`),
  CONSTRAINT `fk_enforcement_site_assessment_tester` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_enforcement_site_assessment_vehicle_station_id` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
  CONSTRAINT `fk_enforcement_visit_outcome_id` FOREIGN KEY (`visit_outcome_id`) REFERENCES `enforcement_visit_outcome_lookup` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Result table for a site assessment visit scoring';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `enforcement_visit_outcome_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enforcement_visit_outcome_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(50) DEFAULT NULL COMMENT 'Text description of the outcome',
  `display_order` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT 'Preferred display order',
  `code` varchar(5) DEFAULT NULL COMMENT 'Internal representation code',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `enforcement_visit_outcome_lookup_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `enforcement_visit_outcome_lookup_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Normalisation of Enforcement site visit outcomes';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `equipment`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `equipment_model_id` int(10) unsigned NOT NULL,
  `site_id` int(10) unsigned NOT NULL,
  `serial_number` varchar(50) NOT NULL,
  `date_added` datetime(6) NOT NULL,
  `date_removed` datetime(6) DEFAULT NULL,
  `equipment_status_id` smallint(5) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `bk_equipment` (`equipment_model_id`,`site_id`,`serial_number`),
  KEY `equipment_model_id` (`equipment_model_id`),
  KEY `site_id` (`site_id`),
  KEY `equipment_status_id` (`equipment_status_id`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `equipment_ibfk_1` FOREIGN KEY (`equipment_model_id`) REFERENCES `equipment_model` (`id`),
  CONSTRAINT `equipment_ibfk_2` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
  CONSTRAINT `equipment_ibfk_3` FOREIGN KEY (`equipment_status_id`) REFERENCES `equipment_status` (`id`),
  CONSTRAINT `equipment_ibfk_4` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `equipment_ibfk_5` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='An instance of a piece of equipment at a site';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `equipment_make`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipment_make` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `bk_equipment_make` (`code`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `equipment_make_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `equipment_make_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='List of different makes of equipment.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `equipment_model`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipment_model` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(8) NOT NULL,
  `name` varchar(100) NOT NULL,
  `equipment_identification_number` varchar(25) DEFAULT NULL COMMENT 'Identifier given by DVSA',
  `equipment_make_id` int(10) unsigned NOT NULL,
  `equipment_type_id` int(10) unsigned NOT NULL,
  `software_version` varchar(20) DEFAULT NULL,
  `certified` date DEFAULT NULL,
  `last_used_date` datetime(6) DEFAULT NULL COMMENT 'After this DATE the status should be changed to WITHDRAWN, column currently for migration only.',
  `last_installable_date` datetime(6) DEFAULT NULL COMMENT 'After this DATE the status should be changed to NOT_INSTALLABLE, column currently for migration only.',
  `equipment_model_status_id` smallint(5) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `bk_equipment_model` (`code`,`equipment_identification_number`,`equipment_make_id`,`equipment_type_id`),
  KEY `equipment_make_id` (`equipment_make_id`),
  KEY `equipment_type_id` (`equipment_type_id`),
  KEY `equipment_model_status_id` (`equipment_model_status_id`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `equipment_model_ibfk_1` FOREIGN KEY (`equipment_make_id`) REFERENCES `equipment_make` (`id`),
  CONSTRAINT `equipment_model_ibfk_2` FOREIGN KEY (`equipment_type_id`) REFERENCES `equipment_type` (`id`),
  CONSTRAINT `equipment_model_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `equipment_model_ibfk_4` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `equipment_model_ibfk_5` FOREIGN KEY (`equipment_model_status_id`) REFERENCES `equipment_model_status` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `equipment_model_status`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipment_model_status` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `description` varchar(100) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_equipment_model_status_code` (`code`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `equipment_model_status_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `equipment_model_status_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Normalisation of the equipment model status';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `equipment_model_vehicle_class_link`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipment_model_vehicle_class_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vehicle_class_id` smallint(5) unsigned NOT NULL,
  `equipment_model_id` int(10) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `bk_equipment_model_vehicle_class_map` (`equipment_model_id`,`vehicle_class_id`),
  KEY `fk_equipment_model_vehicle_class_map_vehicle_class_id` (`vehicle_class_id`),
  KEY `equipment_model_id` (`equipment_model_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `equipment_model_vehicle_class_map_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `equipment_model_vehicle_class_map_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_equipment_model_vehicle_class_map_equipment_model_id` FOREIGN KEY (`equipment_model_id`) REFERENCES `equipment_model` (`id`),
  CONSTRAINT `fk_equipment_model_vehicle_class_map_vehicle_class_id` FOREIGN KEY (`vehicle_class_id`) REFERENCES `vehicle_class` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COMMENT='Used to link a piece of equipment to the vehicle class(es) it is valid for';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `equipment_status`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipment_status` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `description` varchar(100) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_equipment_status_code` (`code`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `equipment_status_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `equipment_status_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Normalisation of a piece of equipments status';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `equipment_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipment_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `bk_equipment_type` (`code`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `equipment_type_ibfk_1` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `equipment_type_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `event`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_type_id` smallint(5) unsigned NOT NULL,
  `event_outcome_id` smallint(5) unsigned DEFAULT NULL,
  `description` varchar(100) NOT NULL COMMENT 'Mandatory short description',
  `comment_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Optional long description',
  `is_manual_event` tinyint(4) NOT NULL DEFAULT '0',
  `event_date` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `ix_event_type_id` (`event_type_id`),
  KEY `ix_event_outcome_id` (`event_outcome_id`),
  KEY `ix_comment_id` (`comment_id`),
  KEY `ix_created_by` (`created_by`),
  KEY `ix_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_event_comment_id_comment_id` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`),
  CONSTRAINT `fk_event_created_by_person_id` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_event_event_outcome_id_event_outcome_id` FOREIGN KEY (`event_outcome_id`) REFERENCES `event_outcome_lookup` (`id`),
  CONSTRAINT `fk_event_event_type_id_event_type_id` FOREIGN KEY (`event_type_id`) REFERENCES `event_type_lookup` (`id`),
  CONSTRAINT `fk_event_last_updated_by_person_id` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COMMENT='System and manual events';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `event_category_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_category_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL,
  `display_order` smallint(5) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_code` (`code`),
  UNIQUE KEY `uk_display_order` (`display_order`),
  KEY `ix_created_by` (`created_by`),
  KEY `ix_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_event_category_lookup_created_by_person_id` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_event_category_lookup_last_updated_by_person_id` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Category of events';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `event_organisation_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_organisation_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL,
  `organisation_id` int(10) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `ix_event_organisation_map_event_id` (`event_id`),
  KEY `ix_event_organisation_map_organisation_id` (`organisation_id`),
  KEY `ix_event_organisation_map_created_by` (`created_by`),
  KEY `ix_event_organisation_map_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_event_org_map_created_by_person_id` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_event_org_map_event_id_event_id` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `fk_event_org_map_last_updated_by_person_id` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_event_org_map_org_id_org_id` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='Map table to join event and organisation';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `event_outcome_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_outcome_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `display_order` smallint(5) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_code` (`code`),
  UNIQUE KEY `uk_display_order` (`display_order`),
  KEY `ix_created_by` (`created_by`),
  KEY `ix_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_event_outcome_lookup_created_by_person_id` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_event_outcome_lookup_last_updated_by_person_id` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 COMMENT='Event outcomes';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `event_person_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_person_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL,
  `person_id` int(10) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `ix_event_person_map_event_id` (`event_id`),
  KEY `ix_event_person_map_person_id` (`person_id`),
  KEY `ix_event_person_map_created_by` (`created_by`),
  KEY `ix_event_person_map_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_event_person_map_created_by_person_id` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_event_person_map_event_id_event_id` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `fk_event_person_map_last_updated_by_person_id` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_event_person_map_person_id_person_id` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Map table to join event and person';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `event_site_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_site_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL,
  `site_id` int(10) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `ix_event_site_map_event_id` (`event_id`),
  KEY `ix_event_site_map_site_id` (`site_id`),
  KEY `ix_event_site_map_created_by` (`created_by`),
  KEY `ix_event_site_map_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_event_site_map_created_by_person_id` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_event_site_map_event_id_event_id` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `fk_event_site_map_last_updated_by_person_id` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_event_site_map_site_id_site_id` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='Map table to join event and site';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `event_type_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_type_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `display_order` smallint(5) unsigned DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL COMMENT 'information is required from the business as to whether end_date is expected for all events',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_code` (`code`),
  UNIQUE KEY `uk_display_order` (`display_order`),
  KEY `ix_created_by` (`created_by`),
  KEY `ix_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_event_type_lookup_created_by_person_id` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_event_type_lookup_last_updated_by_person_id` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='Event types lookup table. Populated with MOT1 data';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `event_type_outcome_category_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_type_outcome_category_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_type_id` smallint(5) unsigned NOT NULL,
  `event_outcome_id` smallint(5) unsigned NOT NULL,
  `event_category_id` smallint(5) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `ix_event_type_outcome_category_map_event_type_id` (`event_type_id`),
  KEY `ix_event_type_outcome_category_map_event_outcome_id` (`event_outcome_id`),
  KEY `ix_event_type_outcome_category_map_created_by` (`created_by`),
  KEY `ix_event_type_outcome_category_map_last_updated_by` (`last_updated_by`),
  KEY `ix_event_type_outcome_category_map_event_category_id` (`event_category_id`),
  CONSTRAINT `fk_event_type_outcome_category_map_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_event_type_outcome_category_map_event_category_id` FOREIGN KEY (`event_category_id`) REFERENCES `event_category_lookup` (`id`),
  CONSTRAINT `fk_event_type_outcome_category_map_event_outcome_id` FOREIGN KEY (`event_outcome_id`) REFERENCES `event_outcome_lookup` (`id`),
  CONSTRAINT `fk_event_type_outcome_category_map_event_type_id` FOREIGN KEY (`event_type_id`) REFERENCES `event_type_lookup` (`id`),
  CONSTRAINT `fk_event_type_outcome_category_map_last_updated_person_id` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=241 DEFAULT CHARSET=utf8 COMMENT='Restrict event outcome for a specific event type by categories';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `evidence`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evidence` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `document_type_id` smallint(5) unsigned NOT NULL,
  `document_ref` varchar(45) DEFAULT NULL,
  `method_of_delivery_id` smallint(5) unsigned zerofill NOT NULL,
  `recieved_on` datetime(6) DEFAULT NULL,
  `status_id` smallint(5) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `evidence_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `evidence_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `experience`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `experience` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employer` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `person_id` int(10) unsigned NOT NULL,
  `date_from` datetime(6) DEFAULT NULL,
  `date_to` datetime(6) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_experience_person` (`person_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `experience_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `experience_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_experience_person` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Experience a person has of work relating to the MOT trade, use in applications';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `facility_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facility_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_facility_type_code` (`code`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `facility_type_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `facility_type_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Normalisation of facility types at a site';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `failure_location_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failure_location_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_failure_location_lookup_created` (`created_by`),
  KEY `fk_failure_location_lookup_updated` (`last_updated_by`),
  CONSTRAINT `fk_failure_location_lookup_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_failure_location_lookup_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Location of a failure';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fuel_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fuel_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `dvla_propulsion_code` varchar(2) DEFAULT NULL,
  `display_order` smallint(5) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_fuel_type_code` (`code`),
  UNIQUE KEY `uk_fuel_type_display_order` (`display_order`),
  KEY `fk_fuel_type_person_created_by` (`created_by`),
  KEY `fk_fuel_type_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_fuel_type_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_fuel_type_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='Representation of the various fuel types';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gender`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gender` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `gender_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `gender_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='normalisation of a persons gender';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `identifying_token`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `identifying_token` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `serial_number` varchar(20) DEFAULT NULL,
  `token_lookup_id` smallint(5) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_identifying_token_token_lookup1` (`token_lookup_id`),
  KEY `fk_identifying_token_created` (`created_by`),
  KEY `fk_identifying_token_updated` (`last_updated_by`),
  CONSTRAINT `fk_identifying_token_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_identifying_token_token_lookup1` FOREIGN KEY (`token_lookup_id`) REFERENCES `token_lookup` (`id`),
  CONSTRAINT `fk_identifying_token_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `incognito_vehicle`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `incognito_vehicle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vehicle_id` int(10) unsigned NOT NULL,
  `start_date` date DEFAULT NULL COMMENT 'incognito start date',
  `end_date` date DEFAULT NULL COMMENT 'incognito end date',
  `test_date` date DEFAULT NULL COMMENT 'visible date of last test',
  `expiry_date` date DEFAULT NULL COMMENT 'visible expiry date',
  `site_id` int(10) unsigned DEFAULT NULL COMMENT 'AO for this Vehicle',
  `person_id` int(10) unsigned DEFAULT NULL COMMENT 'VE for this vehicle',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_incognito_vehicle_site_id` (`site_id`),
  KEY `fk_incognito_vehicle_vehicle_id` (`vehicle_id`),
  KEY `fk_incognito_vehicle_person_id` (`person_id`),
  KEY `fk_incognito_vehicle_person_created` (`created_by`),
  KEY `fk_incognito_vehicle_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_incognito_vehicle_person_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_incognito_vehicle_person_id` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_incognito_vehicle_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_incognito_vehicle_site_id` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
  CONSTRAINT `fk_incognito_vehicle_vehicle_id` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jasper_document`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jasper_document` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `template_id` int(10) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_jasper_document_template` (`template_id`),
  KEY `fk_jasper_document_created` (`created_by`),
  KEY `fk_jasper_document_updated` (`last_updated_by`),
  CONSTRAINT `fk_jasper_document_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_jasper_document_template` FOREIGN KEY (`template_id`) REFERENCES `jasper_template` (`id`),
  CONSTRAINT `fk_jasper_document_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='Jasper Reports related table.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jasper_document_variables`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jasper_document_variables` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint(20) unsigned NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `is_repeatable` tinyint(4) DEFAULT NULL,
  `ord` int(11) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `document_id_key` (`document_id`,`key`),
  KEY `fk_jasper_document_variables_created` (`created_by`),
  KEY `fk_jasper_document_variables_updated` (`last_updated_by`),
  CONSTRAINT `fk_jasper_document_variables_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_jasper_document_variables_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `jasper_document_variables_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `jasper_document` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=334 DEFAULT CHARSET=utf8 COMMENT='Jasper Reports related table.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jasper_hard_copy`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jasper_hard_copy` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint(20) unsigned DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `document_id` (`document_id`),
  KEY `fk_jasper_hard_copy_created` (`created_by`),
  KEY `fk_jasper_hard_copy_updated` (`last_updated_by`),
  CONSTRAINT `fk_jasper_hard_copy_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_jasper_hard_copy_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `hard_copy_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `jasper_document` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Jasper Reports related table.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jasper_template`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jasper_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template_type_id` int(10) unsigned NOT NULL,
  `jasper_report_name` varchar(255) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `active_from` datetime(6) NOT NULL,
  `active_to` datetime(6) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_jasper_template_jasper_template_type` (`template_type_id`),
  KEY `fk_jasper_template_created` (`created_by`),
  KEY `fk_jasper_template_updated` (`last_updated_by`),
  CONSTRAINT `fk_jasper_template_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_jasper_template_jasper_template_type` FOREIGN KEY (`template_type_id`) REFERENCES `jasper_template_type` (`id`),
  CONSTRAINT `fk_jasper_template_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='Jasper Reports related table.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jasper_template_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jasper_template_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_jasper_template_type_created` (`created_by`),
  KEY `fk_jasper_template_type_updated` (`last_updated_by`),
  CONSTRAINT `fk_jasper_template_type_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_jasper_template_type_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='Jasper Reports related table.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jasper_template_variation`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jasper_template_variation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `jasper_report_name` varchar(255) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `template_id` (`template_id`),
  KEY `fk_jasper_template_variation_created` (`created_by`),
  KEY `fk_jasper_template_variation_updated` (`last_updated_by`),
  CONSTRAINT `fk_jasper_template_variation_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_jasper_template_variation_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `jasper_template_variation_ibfk_1` FOREIGN KEY (`template_id`) REFERENCES `jasper_template` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Jasper Reports related table.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `language_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `language_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_language_type_created0` (`created_by`),
  KEY `fk_language_type_updated0` (`last_updated_by`),
  CONSTRAINT `fk_language_type_created0` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_language_type_updated0` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Written languages used in application';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `licence`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `licence` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `licence_number` varchar(45) NOT NULL,
  `licence_type_id` smallint(5) unsigned NOT NULL,
  `country_lookup_id` smallint(5) unsigned NOT NULL,
  `valid_from` datetime(6) DEFAULT NULL,
  `expiry_date` datetime(6) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_licence_licence_type` (`licence_type_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `fk_licence_country_lookup_id` (`country_lookup_id`),
  CONSTRAINT `fk_licence_country_lookup_id` FOREIGN KEY (`country_lookup_id`) REFERENCES `country_of_registration_lookup` (`id`),
  CONSTRAINT `fk_licence_licence_type` FOREIGN KEY (`licence_type_id`) REFERENCES `licence_type` (`id`),
  CONSTRAINT `licence_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `licence_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='License held by a person, such as drivers license';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `licence_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `licence_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_licence_type_code` (`code`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `licence_type_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `licence_type_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Type of license i.e. Driving Licence';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `make`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `make` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) DEFAULT NULL,
  `is_verified` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Verified and visible for dropdowns etc',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_UNIQUE` (`code`),
  KEY `ix_make_name` (`name`),
  KEY `fk_make_person_created_by` (`created_by`),
  KEY `fk_make_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_make_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_make_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100230 DEFAULT CHARSET=utf8 COMMENT='Vehicle makes';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `message`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `person_id` int(10) unsigned NOT NULL,
  `message_type_id` smallint(5) unsigned NOT NULL,
  `issue_date` datetime DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `is_acknowledged` tinyint(4) NOT NULL DEFAULT '0',
  `token` varchar(64) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_message_token` (`token`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `fk_message_message_type1` (`message_type_id`),
  KEY `fk_message_person` (`person_id`),
  CONSTRAINT `fk_message_message_type1` FOREIGN KEY (`message_type_id`) REFERENCES `message_type` (`id`),
  CONSTRAINT `fk_message_person` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `message_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `message_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Messages sent from the system i.e. Forgotten password';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `message_content`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `message_id` int(10) unsigned NOT NULL,
  `recipient_email` varchar(255) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `fk_message_content_message1` (`message_id`),
  CONSTRAINT `fk_message_content_message1` FOREIGN KEY (`message_id`) REFERENCES `message` (`id`),
  CONSTRAINT `message_content_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `message_content_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Content of messages sent from the system';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `message_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `expiry_period` smallint(6) unsigned DEFAULT NULL COMMENT 'How long after sending do URLs expire (in hours)',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_message_type_code` (`code`),
  KEY `fk_message_type_created` (`created_by`),
  KEY `fk_message_type_updated` (`last_updated_by`),
  CONSTRAINT `fk_message_type_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_message_type_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Types of messages i.e. Forgotten password, Username reminder etc';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `message_url`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_url` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `message_id` int(10) unsigned NOT NULL,
  `url_type_id` smallint(5) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `used_count` smallint(6) unsigned NOT NULL DEFAULT '0',
  `is_expired` tinyint(4) NOT NULL DEFAULT '0',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `id_message_url` (`url`),
  KEY `fk_d9b7aeca-375f-11e4-9d59-485d60c531e30` (`created_by`),
  KEY `fk_d9b7b10e-375f-11e4-9d59-485d60c531e30` (`last_updated_by`),
  KEY `fk_message_url_message1` (`message_id`),
  KEY `fk_message_url_url_type1` (`url_type_id`),
  CONSTRAINT `fk_d9b7aeca-375f-11e4-9d59-485d60c531e30` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_d9b7b10e-375f-11e4-9d59-485d60c531e30` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_message_url_message1` FOREIGN KEY (`message_id`) REFERENCES `message` (`id`),
  CONSTRAINT `fk_message_url_url_type1` FOREIGN KEY (`url_type_id`) REFERENCES `url_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='URLs used in messages';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `model`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) DEFAULT NULL COMMENT 'NULL for non DVLA models',
  `name` varchar(50) NOT NULL,
  `make_id` int(10) unsigned NOT NULL,
  `make_code` varchar(5) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  `is_verified` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_model_make_id` (`make_id`),
  KEY `fk_model_person_created_by` (`created_by`),
  KEY `fk_model_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_model_make_id` FOREIGN KEY (`make_id`) REFERENCES `make` (`id`),
  CONSTRAINT `fk_model_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_model_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=107689 DEFAULT CHARSET=utf8 COMMENT='Vehicle models';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `model_detail`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `make_id` int(10) unsigned NOT NULL,
  `model_id` int(10) unsigned NOT NULL,
  `code` varchar(5) NOT NULL,
  `is_verified` tinyint(4) NOT NULL DEFAULT '0',
  `weight` int(11) DEFAULT NULL COMMENT 'Generic weight for this Model from VSI/DVLA data',
  `weight_source_id` smallint(5) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_model_detail_created_by` (`created_by`),
  KEY `fk_model_detail_last_updated_by` (`last_updated_by`),
  KEY `fk_model_detail_make_id` (`make_id`),
  KEY `fk_model_detail_model_id` (`model_id`),
  KEY `fk_model_detail_weight_source_lookup` (`weight_source_id`),
  CONSTRAINT `fk_model_detail_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_model_detail_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_model_detail_make_id` FOREIGN KEY (`make_id`) REFERENCES `make` (`id`),
  CONSTRAINT `fk_model_detail_model_id` FOREIGN KEY (`model_id`) REFERENCES `model` (`id`),
  CONSTRAINT `fk_model_detail_weight_source_lookup` FOREIGN KEY (`weight_source_id`) REFERENCES `weight_source_lookup` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='Model detail that would have come from the VSI. Currently unused';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mot1_vts_device_status`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mot1_vts_device_status` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_mot1_vts_device_status_person_created_by` (`created_by`),
  KEY `fk_mot1_vts_device_status_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_mot1_vts_device_status_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_mot1_vts_device_status_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mot_test`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mot_test` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `person_id` int(10) unsigned NOT NULL,
  `vehicle_id` int(10) unsigned NOT NULL,
  `vehicle_version` int(10) unsigned DEFAULT NULL,
  `document_id` bigint(20) unsigned DEFAULT NULL,
  `site_id` int(10) unsigned DEFAULT NULL,
  `primary_colour_id` smallint(5) unsigned NOT NULL,
  `secondary_colour_id` smallint(5) unsigned DEFAULT NULL,
  `vehicle_class_id` smallint(5) unsigned DEFAULT NULL,
  `tested_as_fuel_type_id` smallint(5) unsigned DEFAULT NULL,
  `vin` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `empty_vin_reason_id` smallint(5) unsigned DEFAULT NULL,
  `registration` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `empty_vrm_reason_id` smallint(5) unsigned DEFAULT NULL,
  `make_id` int(10) unsigned DEFAULT NULL,
  `model_id` int(10) unsigned DEFAULT NULL,
  `model_detail_id` int(10) unsigned DEFAULT NULL,
  `country_of_registration_id` smallint(5) unsigned DEFAULT NULL,
  `has_registration` tinyint(4) unsigned NOT NULL,
  `mot_test_type_id` smallint(5) unsigned NOT NULL,
  `started_date` datetime(6) NOT NULL COMMENT 'It is populated by application due to contingency testing',
  `completed_date` datetime(6) DEFAULT NULL,
  `status_id` smallint(5) unsigned NOT NULL,
  `issued_date` datetime(6) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `mot_test_id_original` bigint(20) unsigned DEFAULT NULL,
  `prs_mot_test_id` bigint(20) unsigned DEFAULT NULL,
  `mot_test_reason_for_cancel_id` smallint(5) unsigned DEFAULT NULL COMMENT 'Not null only for canceled tests',
  `reason_for_cancel_comment_id` bigint(20) unsigned DEFAULT NULL,
  `reason_for_termination_comment` varchar(240) DEFAULT NULL,
  `full_partial_retest_id` smallint(5) unsigned DEFAULT '1',
  `partial_reinspection_comment_id` bigint(20) unsigned DEFAULT NULL,
  `items_not_tested_comment_id` bigint(20) unsigned DEFAULT NULL,
  `one_person_test` tinyint(3) unsigned DEFAULT NULL,
  `one_person_reinspection` tinyint(3) unsigned DEFAULT NULL,
  `complaint_ref` varchar(30) DEFAULT NULL,
  `number` decimal(12,0) DEFAULT NULL COMMENT 'No sequencial test number',
  `odometer_reading_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Updated during test so multiple odometer values may exist over test lifetime',
  `private` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Do not fetch this row unless you know you are allowed to. See enforcement for details.',
  `emergency_log_id` int(10) unsigned DEFAULT NULL COMMENT 'This should be only have an entry if the Test is an emergency/contingency Test',
  `emergency_reason_lookup_id` smallint(5) unsigned DEFAULT NULL COMMENT 'An emergency/contingency Test should always have an emergency reason entered by the Tester or the person who enters the Test on the system',
  `emergency_reason_comment_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Free text comments should be only be entered when the emergency_reason_lookup_id is "Other"',
  `vehicle_weight_source_lookup_id` smallint(5) unsigned DEFAULT NULL COMMENT 'Source of vehicle_weight',
  `vehicle_weight` int(10) unsigned DEFAULT NULL COMMENT 'Brake test weight from MOT1 VEHTESTBRAKEWEIGHT should be used in the calculation of brake efficiency. Recorded in kilograms. Migrated from MOT1 as brake test are not migrated',
  `incognito_vehicle_id` int(10) unsigned DEFAULT NULL,
  `address_comment_id` bigint(20) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  `make_name` varchar(50) DEFAULT NULL,
  `model_name` varchar(50) DEFAULT NULL,
  `model_detail_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_mot_test_number` (`number`),
  KEY `vehicle_id` (`vehicle_id`),
  KEY `site_id` (`site_id`),
  KEY `vehicle_class_id` (`vehicle_class_id`),
  KEY `person` (`person_id`),
  KEY `full_partial_retest_id` (`full_partial_retest_id`),
  KEY `partial_reinspection_comment_id` (`partial_reinspection_comment_id`),
  KEY `items_not_tested_comment_id` (`items_not_tested_comment_id`),
  KEY `fk_mot_test_mot_test_type_id` (`mot_test_type_id`),
  KEY `fk_mot_test_odometer_reading` (`odometer_reading_id`),
  KEY `fk_mot_test_make_and_model_id` (`make_id`,`model_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `fk_mot_test_emergency_log_id` (`emergency_log_id`),
  KEY `fk_mot_test_emergency_reason_lookup_id` (`emergency_reason_lookup_id`),
  KEY `fk_mot_test_emergency_reason_comment_id` (`emergency_reason_comment_id`),
  KEY `fk_mot_test_status_id` (`status_id`),
  KEY `fk_mot_test_country_of_registration_id` (`country_of_registration_id`),
  KEY `fk_test_primary_colour` (`primary_colour_id`),
  KEY `fk_test_secondary_colour` (`secondary_colour_id`),
  KEY `fk_mot_test_reason_for_cancel_comment_id` (`reason_for_cancel_comment_id`),
  KEY `fk_mot_test_model_detail_id` (`model_detail_id`),
  KEY `fk_mot_test_address_comment_id` (`address_comment_id`),
  KEY `fk_mot_test_incognito_vehicle_id` (`incognito_vehicle_id`),
  KEY `fk_mot_test_vehicle_weight_source_lookup_id` (`vehicle_weight_source_lookup_id`),
  KEY `fk_mot_test_jasper_document_id` (`document_id`),
  KEY `fk_mot_test_prs_mot_test_id_mot_test_id` (`prs_mot_test_id`),
  KEY `fk_mot_test_mot_test_id_original_mot_test_id` (`mot_test_id_original`),
  KEY `fk_mot_test_fuel_lookup_id` (`tested_as_fuel_type_id`),
  KEY `fk_mot_test_reason_for_cancel_id` (`mot_test_reason_for_cancel_id`),
  KEY `fk_mot_test_empty_vrm_reason_id_empty_vrm_reason_lookup_id` (`empty_vrm_reason_id`),
  KEY `fk_mot_test_empty_vin_reason_id_empty_vin_reason_lookup_id` (`empty_vin_reason_id`),
  CONSTRAINT `fk_items_not_tested_comment_id` FOREIGN KEY (`items_not_tested_comment_id`) REFERENCES `comment` (`id`),
  CONSTRAINT `fk_mot_test_address_comment_id` FOREIGN KEY (`address_comment_id`) REFERENCES `comment` (`id`),
  CONSTRAINT `fk_mot_test_country_of_registration_id` FOREIGN KEY (`country_of_registration_id`) REFERENCES `country_of_registration_lookup` (`id`),
  CONSTRAINT `fk_mot_test_emergency_log_id` FOREIGN KEY (`emergency_log_id`) REFERENCES `emergency_log` (`id`),
  CONSTRAINT `fk_mot_test_emergency_reason_comment_id` FOREIGN KEY (`emergency_reason_comment_id`) REFERENCES `comment` (`id`),
  CONSTRAINT `fk_mot_test_emergency_reason_lookup_id` FOREIGN KEY (`emergency_reason_lookup_id`) REFERENCES `emergency_reason_lookup` (`id`),
  CONSTRAINT `fk_mot_test_empty_vin_reason_id_empty_vin_reason_lookup_id` FOREIGN KEY (`empty_vin_reason_id`) REFERENCES `empty_vin_reason_lookup` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mot_test_empty_vrm_reason_id_empty_vrm_reason_lookup_id` FOREIGN KEY (`empty_vrm_reason_id`) REFERENCES `empty_vrm_reason_lookup` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `fk_mot_test_fuel_lookup_id` FOREIGN KEY (`tested_as_fuel_type_id`) REFERENCES `fuel_type` (`id`),
  CONSTRAINT `fk_mot_test_full_partial_retest_id` FOREIGN KEY (`full_partial_retest_id`) REFERENCES `enforcement_full_partial_retest_lookup` (`id`),
  CONSTRAINT `fk_mot_test_incognito_vehicle_id` FOREIGN KEY (`incognito_vehicle_id`) REFERENCES `incognito_vehicle` (`id`),
  CONSTRAINT `fk_mot_test_jasper_document_id` FOREIGN KEY (`document_id`) REFERENCES `jasper_document` (`id`),
  CONSTRAINT `fk_mot_test_make_and_model_id` FOREIGN KEY (`make_id`, `model_id`) REFERENCES `model` (`make_id`, `id`),
  CONSTRAINT `fk_mot_test_model_detail_id` FOREIGN KEY (`model_detail_id`) REFERENCES `model_detail` (`id`),
  CONSTRAINT `fk_mot_test_mot_test_id_original_mot_test_id` FOREIGN KEY (`mot_test_id_original`) REFERENCES `mot_test` (`id`),
  CONSTRAINT `fk_mot_test_mot_test_type_id` FOREIGN KEY (`mot_test_type_id`) REFERENCES `mot_test_type` (`id`),
  CONSTRAINT `fk_mot_test_odometer_reading` FOREIGN KEY (`odometer_reading_id`) REFERENCES `odometer_reading` (`id`),
  CONSTRAINT `fk_mot_test_person` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_mot_test_prs_mot_test_id_mot_test_id` FOREIGN KEY (`prs_mot_test_id`) REFERENCES `mot_test` (`id`),
  CONSTRAINT `fk_mot_test_reason_for_cancel_id` FOREIGN KEY (`mot_test_reason_for_cancel_id`) REFERENCES `mot_test_reason_for_cancel_lookup` (`id`),
  CONSTRAINT `fk_mot_test_site` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
  CONSTRAINT `fk_mot_test_status_id` FOREIGN KEY (`status_id`) REFERENCES `mot_test_status` (`id`),
  CONSTRAINT `fk_mot_test_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`id`),
  CONSTRAINT `fk_mot_test_vehicle_class` FOREIGN KEY (`vehicle_class_id`) REFERENCES `vehicle_class` (`id`),
  CONSTRAINT `fk_mot_test_vehicle_weight_source_lookup_id` FOREIGN KEY (`vehicle_weight_source_lookup_id`) REFERENCES `weight_source_lookup` (`id`),
  CONSTRAINT `fk_partial_reinspection_comment_id` FOREIGN KEY (`partial_reinspection_comment_id`) REFERENCES `comment` (`id`),
  CONSTRAINT `fk_test_primary_colour` FOREIGN KEY (`primary_colour_id`) REFERENCES `colour_lookup` (`id`),
  CONSTRAINT `fk_test_reason_for_cancel_comment_id` FOREIGN KEY (`reason_for_cancel_comment_id`) REFERENCES `comment` (`id`),
  CONSTRAINT `fk_test_secondary_colour` FOREIGN KEY (`secondary_colour_id`) REFERENCES `colour_lookup` (`id`),
  CONSTRAINT `mot_test_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `mot_test_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mot_test_event`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mot_test_event` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mot_test_id` bigint(20) unsigned NOT NULL,
  `mot_test_version` smallint(5) unsigned NOT NULL COMMENT 'version of test to identify record in history table if not current record',
  `tester_person_id` int(10) unsigned DEFAULT NULL COMMENT 'Person who entered/completed the test/certificate reissue data',
  `certificate_status_id` smallint(5) unsigned NOT NULL,
  `different_tester_reason_id` smallint(5) unsigned DEFAULT NULL,
  `reason_comment_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Free text for further clarification of reason for different tester',
  `document_id` bigint(20) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_mot_test_different_tester_reason_id_lookup` (`different_tester_reason_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `fk_mot_test_certificate_status` (`certificate_status_id`),
  KEY `fk_mot_test_reason` (`reason_comment_id`),
  KEY `fk_test_event_document` (`document_id`),
  KEY `fk_test_event_person` (`tester_person_id`),
  KEY `fk_mot_test_event_mot_test_id` (`mot_test_id`),
  CONSTRAINT `fk_mot_test_certificate_status` FOREIGN KEY (`certificate_status_id`) REFERENCES `certificate_status` (`id`),
  CONSTRAINT `fk_mot_test_different_tester_reason_id_lookup` FOREIGN KEY (`different_tester_reason_id`) REFERENCES `certificate_change_different_tester_reason_lookup` (`id`),
  CONSTRAINT `fk_mot_test_event_mot_test_id` FOREIGN KEY (`mot_test_id`) REFERENCES `mot_test` (`id`),
  CONSTRAINT `fk_mot_test_reason` FOREIGN KEY (`reason_comment_id`) REFERENCES `comment` (`id`),
  CONSTRAINT `fk_test_event_document` FOREIGN KEY (`document_id`) REFERENCES `jasper_document` (`id`),
  CONSTRAINT `fk_test_event_person` FOREIGN KEY (`tester_person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `mot_test_event_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `mot_test_event_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Records details of a vehicle test (including non-MOT) with one record per certificate issuance';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mot_test_reason_for_cancel_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mot_test_reason_for_cancel_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `reason` varchar(250) NOT NULL,
  `reason_cy` varchar(250) DEFAULT NULL COMMENT 'Welsh',
  `is_system_generated` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Legacy from mot1. CRON jobs aborted long mot tests, e.g. 24h',
  `is_displayable` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Some values are not used anymore in MOT 2, but we keep the them as legacy from MOT 1',
  `is_abandoned` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `mot_test_reason_for_cancel_lookup_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `mot_test_reason_for_cancel_lookup_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='Reasons for canceling a test';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mot_test_reason_for_refusal_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mot_test_reason_for_refusal_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `reason` varchar(250) NOT NULL COMMENT 'English',
  `reason_cy` varchar(250) DEFAULT NULL COMMENT 'Welsh translation - if available',
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `display_order` smallint(5) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mot_test_reason_for_refusal_lookup_code` (`code`),
  UNIQUE KEY `mot_test_reason_for_refusal_lookup_display_order` (`display_order`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `mot_test_reason_for_refusal_lookup_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `mot_test_reason_for_refusal_lookup_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='Reasons for refusing to test';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mot_test_refusal`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mot_test_refusal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vin` varchar(20) CHARACTER SET latin1 DEFAULT NULL COMMENT 'VIN of the refused car',
  `registration` varchar(7) CHARACTER SET latin1 DEFAULT NULL COMMENT 'registration of the refused car',
  `site_id` int(10) unsigned DEFAULT NULL COMMENT 'Where refusal took place',
  `person_id` int(10) unsigned NOT NULL COMMENT 'The person who refused the test',
  `refused_on` datetime(6) NOT NULL,
  `reason_for_refusal_id` smallint(5) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_mot_test_refusal_person_id` (`person_id`),
  KEY `fk_mot_test_refusal_site_id` (`site_id`),
  KEY `fk_mot_test_refusal_reason_for_refusal_id` (`reason_for_refusal_id`),
  KEY `fk_mot_test_refusal_person_created_by` (`created_by`),
  KEY `fk_mot_test_refusal_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_mot_test_refusal_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_mot_test_refusal_person_id` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_mot_test_refusal_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_mot_test_refusal_reason_for_refusal_id` FOREIGN KEY (`reason_for_refusal_id`) REFERENCES `mot_test_reason_for_refusal_lookup` (`id`),
  CONSTRAINT `fk_mot_test_refusal_site_id` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mot_test_rfr_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mot_test_rfr_map` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mot_test_id` bigint(20) unsigned NOT NULL,
  `rfr_id` int(10) unsigned DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `location_lateral` varchar(50) DEFAULT NULL,
  `location_longitudinal` varchar(50) DEFAULT NULL,
  `location_vertical` varchar(50) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `failure_dangerous` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'User option available when can_be_dangerous flag is set in test_item_selector_rfr',
  `generated` tinyint(4) NOT NULL,
  `custom_description` varchar(100) DEFAULT NULL,
  `on_original_test` tinyint(4) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `fk_mot_test_rfr_map_rfr` (`rfr_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `ix_mot_test_rfr_map_reason_for_rejection_type` (`type`),
  KEY `fk_mot_test_rfr_map_mot_test_id` (`mot_test_id`),
  CONSTRAINT `fk_mot_test_rfr_map_mot_test_id` FOREIGN KEY (`mot_test_id`) REFERENCES `mot_test` (`id`),
  CONSTRAINT `fk_mot_test_rfr_map_reason_for_rejection_type` FOREIGN KEY (`type`) REFERENCES `reason_for_rejection_type` (`name`),
  CONSTRAINT `fk_mot_test_rfr_map_rfr` FOREIGN KEY (`rfr_id`) REFERENCES `reason_for_rejection` (`id`),
  CONSTRAINT `mot_test_rfr_map_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `mot_test_rfr_map_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Records the RFR details of vehicle tests';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mot_test_status`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mot_test_status` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  `description` varchar(250) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_mot_test_status_code` (`code`),
  KEY `fk_mot_test_status_created_by` (`created_by`),
  KEY `fk_mot_test_status_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_mot_test_status_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_mot_test_status_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mot_test_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mot_test_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `display_order` smallint(5) unsigned NOT NULL DEFAULT '1',
  `is_demo` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_slot_consuming` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `is_reinspection` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `mot_test_type_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `mot_test_type_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='Normalisation of types of test performed';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `non_working_day_country_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `non_working_day_country_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `country_lookup_id` smallint(5) unsigned NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_non_working_day_country_lookup_country_lookup_id` (`country_lookup_id`),
  KEY `fk_non_working_day_country_lookup_person_created_by` (`created_by`),
  KEY `fk_non_working_day_country_lookup_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_non_working_day_country_lookup_country_lookup_id` FOREIGN KEY (`country_lookup_id`) REFERENCES `country_lookup` (`id`),
  CONSTRAINT `fk_non_working_day_country_lookup_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_non_working_day_country_lookup_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `non_working_day_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `non_working_day_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `non_working_day_country_lookup_id` smallint(5) unsigned NOT NULL,
  `day` date NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_non_working_day_lookup_non_working_day_country_id_day,` (`non_working_day_country_lookup_id`,`day`),
  KEY `fk_non_working_day_lookup_non_working_day_country_lookup_id` (`non_working_day_country_lookup_id`),
  KEY `fk_non_working_day_country_lookup_person_created_by` (`created_by`),
  KEY `fk_non_working_day_country_lookup_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_non_working_day_lookup_non_working_day_country_lookup_id` FOREIGN KEY (`non_working_day_country_lookup_id`) REFERENCES `non_working_day_country_lookup` (`id`),
  CONSTRAINT `fk_non_working_day_lookup_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_non_working_day_lookup_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notification`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notification` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `notification_template_id` int(10) unsigned NOT NULL,
  `recipient_id` int(10) unsigned NOT NULL,
  `read_on` datetime(6) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `fk_notification_notification_template_id` (`notification_template_id`),
  KEY `fk_notification_person_id` (`recipient_id`),
  CONSTRAINT `fk_notification_notification_template_id` FOREIGN KEY (`notification_template_id`) REFERENCES `notification_template` (`id`),
  CONSTRAINT `fk_notification_person_id` FOREIGN KEY (`recipient_id`) REFERENCES `person` (`id`),
  CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `notification_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Records instances of notifications to persons. Note these are different from Special Notices.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notification_action_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notification_action_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(100) NOT NULL,
  `display_order` smallint(5) unsigned NOT NULL DEFAULT '1',
  `code` varchar(5) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_notification_action_lookup_code` (`code`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `notification_action_lookup_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `notification_action_lookup_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Normalisation of the types of action that can be taken for all types of notification';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notification_action_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notification_action_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `notification_id` int(10) unsigned NOT NULL,
  `action_id` smallint(5) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_notification_action_1` (`notification_id`),
  KEY `fk_notification_action_2` (`action_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_notification_action_1` FOREIGN KEY (`notification_id`) REFERENCES `notification` (`id`),
  CONSTRAINT `fk_notification_action_2` FOREIGN KEY (`action_id`) REFERENCES `notification_action_lookup` (`id`),
  CONSTRAINT `fk_notification_action_map_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_notification_action_map_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Record of the action taken as a result of a notification';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notification_field`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notification_field` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `notification_id` int(10) unsigned NOT NULL,
  `field` varchar(30) NOT NULL,
  `content` varchar(250) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_notification_fields_1` (`notification_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_notification_fields_1` FOREIGN KEY (`notification_id`) REFERENCES `notification` (`id`),
  CONSTRAINT `notification_field_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `notification_field_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Records the strings substituted into notification instances';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notification_template`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notification_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text,
  `subject` varchar(255) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `notification_template_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `notification_template_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='Template text of notification including placeholder fields';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notification_template_action`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notification_template_action` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `notification_template_id` int(10) unsigned NOT NULL,
  `action_id` smallint(5) unsigned NOT NULL,
  `label` varchar(100) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_notification_template_action_1` (`notification_template_id`),
  KEY `fk_notification_template_action_2` (`action_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_notification_template_action_1` FOREIGN KEY (`notification_template_id`) REFERENCES `notification_template` (`id`),
  CONSTRAINT `fk_notification_template_action_2` FOREIGN KEY (`action_id`) REFERENCES `notification_action_lookup` (`id`),
  CONSTRAINT `notification_template_action_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `notification_template_action_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Names of actions that can be taken for a given notification template';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `odometer_reading`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `odometer_reading` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `recorded_on` datetime(6) DEFAULT NULL COMMENT 'date reading was made so that incognito test results can be hidden',
  `value` int(11) DEFAULT NULL,
  `unit` varchar(2) DEFAULT NULL,
  `result_type` varchar(10) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `odometer_reading_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `odometer_reading_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Holds odometer readings for tests';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `organisation`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organisation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL COMMENT '60 in MOT1',
  `registered_company_number` varchar(20) DEFAULT NULL,
  `vat_registration_number` varchar(20) DEFAULT NULL,
  `trading_name` varchar(60) DEFAULT NULL COMMENT '60 in MOT1',
  `company_type_id` smallint(5) unsigned DEFAULT NULL,
  `organisation_type_id` smallint(5) unsigned DEFAULT NULL,
  `transition_status_id` smallint(5) unsigned DEFAULT NULL,
  `transition_scheduled_on` date DEFAULT NULL,
  `sites_confirmed_ready_on` datetime(6) DEFAULT NULL,
  `transition_processed_on` datetime(6) DEFAULT NULL,
  `first_payment_setup_on` datetime(6) DEFAULT NULL,
  `first_slots_purchased_on` datetime(6) DEFAULT NULL,
  `mot1_total_running_balance` decimal(12,2) DEFAULT NULL COMMENT 'Total of all account balances in MOT1 before convertion to slots',
  `mot1_total_slots_converted` int(11) DEFAULT NULL COMMENT 'Total of slots converted from balance cash leaving remainder cash in mot1_remainder_migrated',
  `mot1_total_remainder_balance` decimal(12,2) DEFAULT NULL COMMENT 'Remainder of all account balances after convertion to slots',
  `mot1_total_vts_slots_merged` int(11) DEFAULT NULL COMMENT 'Total slots currently available accross all VTS devices in the AE',
  `mot1_total_slots_merged` int(11) DEFAULT NULL COMMENT 'Total of merged and converted MOT1 slots',
  `mot1_slots_migrated_on` datetime(6) DEFAULT NULL,
  `mot1_details_updated_on` datetime(6) DEFAULT NULL,
  `slots_balance` int(10) unsigned NOT NULL DEFAULT '0',
  `slots_warning` int(10) unsigned NOT NULL DEFAULT '15',
  `slots_purchased` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Negative slot balance in overdraft field may be migrated from MOT1 and occur if a cheque is bounced.This field is always positive.',
  `slots_overdraft` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Negative slot balance in overdraft field may be migrated from MOT1 and occur if a cheque is bounced.',
  `data_may_be_disclosed` tinyint(4) NOT NULL DEFAULT '0',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `ix_organisation_name` (`name`),
  KEY `ix_organisation_registered_company_number` (`registered_company_number`),
  KEY `ix_organisation_vat_registration_number` (`vat_registration_number`),
  KEY `fk_organisation_company_type_id` (`company_type_id`),
  KEY `fk_organisation_organisation_type_id` (`organisation_type_id`),
  KEY `fk_organisation_transition_status_id` (`transition_status_id`),
  KEY `fk_organisation_person_created_by` (`created_by`),
  KEY `fk_organisation_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_organisation_company_type_id` FOREIGN KEY (`company_type_id`) REFERENCES `company_type` (`id`),
  CONSTRAINT `fk_organisation_organisation_type_id` FOREIGN KEY (`organisation_type_id`) REFERENCES `organisation_type` (`id`),
  CONSTRAINT `fk_organisation_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_organisation_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_organisation_transition_status_id` FOREIGN KEY (`transition_status_id`) REFERENCES `transition_status` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2005 DEFAULT CHARSET=utf8 COMMENT='Representation of an organisation in the system';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `organisation_assembly_role_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organisation_assembly_role_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` int(10) unsigned NOT NULL,
  `assembly_id` int(10) unsigned NOT NULL,
  `assembly_role_id` smallint(5) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_organisation_assembly_role_map_org_assembly_assembly_role` (`organisation_id`,`assembly_id`,`assembly_role_id`),
  KEY `fk_organisation_assembly_role_map_assembly_id` (`assembly_id`),
  KEY `fk_organisation_assembly_role_map_assembly_role_id` (`assembly_role_id`),
  KEY `fk_organisation_assembly_organisation_id` (`organisation_id`),
  KEY `fk_organisation_assembly_role_map_person_created_by` (`created_by`),
  KEY `fk_organisation_assembly_role_map_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_organisation_assembly_organisation_id` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`),
  CONSTRAINT `fk_organisation_assembly_role_map_assembly_id` FOREIGN KEY (`assembly_id`) REFERENCES `assembly` (`id`),
  CONSTRAINT `fk_organisation_assembly_role_map_assembly_role_id` FOREIGN KEY (`assembly_role_id`) REFERENCES `assembly_role_type` (`id`),
  CONSTRAINT `fk_organisation_assembly_role_map_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_organisation_assembly_role_map_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `organisation_business_role`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organisation_business_role` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL,
  `code` varchar(5) NOT NULL,
  `organisation_type_id` smallint(5) unsigned DEFAULT NULL,
  `role_id` int(10) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_organisation_business_role_code` (`code`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `fk_organisation_business_role_role` (`role_id`),
  KEY `fk_organisation_business_role_organisation_type1` (`organisation_type_id`),
  CONSTRAINT `fk_organisation_business_role_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`),
  CONSTRAINT `organisation_business_role_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `organisation_business_role_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `organisation_business_role_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organisation_business_role_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` int(10) unsigned NOT NULL,
  `business_role_id` smallint(5) unsigned NOT NULL,
  `person_id` int(10) unsigned NOT NULL,
  `status_id` smallint(5) unsigned NOT NULL,
  `status_changed_on` datetime(6) DEFAULT NULL,
  `valid_from` datetime(6) DEFAULT NULL,
  `expiry_date` datetime(6) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_organisation_role_map_organisation` (`organisation_id`),
  KEY `fk_organisation_role_map_person` (`person_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `fk_organisation_business_role_map` (`business_role_id`),
  KEY `fk_organisation_business_role_map_status` (`status_id`),
  CONSTRAINT `fk_organisation_business_role_map` FOREIGN KEY (`business_role_id`) REFERENCES `organisation_business_role` (`id`),
  CONSTRAINT `fk_organisation_business_role_map_person_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_organisation_business_role_map_person_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_organisation_business_role_map_status` FOREIGN KEY (`status_id`) REFERENCES `business_role_status` (`id`),
  CONSTRAINT `fk_organisation_role_map_organisation` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`),
  CONSTRAINT `fk_organisation_role_map_person` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='A persons business role within an Organisation i.e. AEDM';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `organisation_contact_detail_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organisation_contact_detail_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` int(10) unsigned NOT NULL,
  `contact_detail_id` int(10) unsigned NOT NULL,
  `organisation_contact_type_id` smallint(5) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_organisation_contact_detail_map_contact_detail_id` (`contact_detail_id`),
  KEY `fk_organisation_contact_detail_map_organisation_id` (`organisation_id`),
  KEY `fk_organisation_contact_detail_map_contact_type_id` (`organisation_contact_type_id`),
  KEY `fk_organisation_contact_detail_map_person_created_by` (`created_by`),
  KEY `fk_organisation_contact_detail_map_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_organisation_contact_detail_map_contact_detail_id` FOREIGN KEY (`contact_detail_id`) REFERENCES `contact_detail` (`id`),
  CONSTRAINT `fk_organisation_contact_detail_map_contact_type_id` FOREIGN KEY (`organisation_contact_type_id`) REFERENCES `organisation_contact_type` (`id`),
  CONSTRAINT `fk_organisation_contact_detail_map_organisation_id` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`),
  CONSTRAINT `fk_organisation_contact_detail_map_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_organisation_contact_detail_map_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Link table between an organisation and a set of contact details';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `organisation_contact_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organisation_contact_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_organisation_contact_type_code` (`code`),
  KEY `fk_organisation_contact_type_created_by` (`created_by`),
  KEY `kf_organisation_contact_type_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_organisation_contact_type_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `kf_organisation_contact_type_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Normalisation of types of contact detail of an organisation';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `organisation_site_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organisation_site_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` int(10) unsigned NOT NULL,
  `site_id` int(10) unsigned NOT NULL,
  `trading_name` varchar(60) DEFAULT NULL,
  `status_id` smallint(5) unsigned NOT NULL,
  `status_changed_on` datetime(6) NOT NULL,
  `start_date` datetime(6) DEFAULT NULL,
  `end_date` datetime(6) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_organisation_site_map_organisation_id` (`organisation_id`),
  KEY `fk_organisation_site_map_site_id` (`site_id`),
  KEY `fk_organisation_site_map_organisation_site_status` (`status_id`),
  KEY `fk_organisation_site_map_person_created_by` (`created_by`),
  KEY `fk_organisation_site_map_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_organisation_site_map_organisation_id` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`),
  CONSTRAINT `fk_organisation_site_map_organisation_site_status` FOREIGN KEY (`status_id`) REFERENCES `organisation_site_status` (`id`),
  CONSTRAINT `fk_organisation_site_map_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_organisation_site_map_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_organisation_site_map_site_id` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `organisation_site_status`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organisation_site_status` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_organisation_site_status_code` (`code`),
  UNIQUE KEY `uk_organisation_site_status_name` (`name`),
  KEY `fk_organisation_site_status_person_created_by` (`created_by`),
  KEY `fk_organisation_site_status_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_organisation_site_status_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_organisation_site_status_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='Normalisation of the status of a site. ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `organisation_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organisation_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_organisation_type_code` (`code`),
  KEY `ix_organisation_type_mot1_legacy_id` (`mot1_legacy_id`),
  KEY `fk_organisation_type_person_created_by` (`created_by`),
  KEY `fk_organisation_type_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_organisation_type_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_organisation_type_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='Normalisation of the type of an organisation i.e. DVSA, DVLA, Examining Body, Authorised Examiner, Other';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `amount` decimal(10,2) NOT NULL,
  `receipt_reference` varchar(55) DEFAULT NULL COMMENT 'Unique reference received from CPMS to identify the payment',
  `status_id` smallint(5) unsigned DEFAULT NULL,
  `type` smallint(5) unsigned NOT NULL,
  `created` datetime(6) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_payment_payment_type` (`type`),
  KEY `fk_payment_payment_status` (`status_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_payment_payment_status` FOREIGN KEY (`status_id`) REFERENCES `payment_status` (`id`),
  CONSTRAINT `fk_payment_payment_type` FOREIGN KEY (`type`) REFERENCES `payment_type` (`id`),
  CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Records payments made for slots. Potentially more than one payment could be made per slot transaction.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment_status`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_status` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `payment_status_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `payment_status_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Normalisation of the status of a payment';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(75) NOT NULL DEFAULT '' COMMENT 'Name of the payment type',
  `active` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Whether the payment type is active',
  `display_order` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT 'Order the payment type appears in a list',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `payment_type_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `payment_type_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Normalisation of types of payment. Not all payment types are currently implemented';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permission`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(40) CHARACTER SET latin1 NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_permission_code` (`code`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `permission_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `permission_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `person`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `pin` varchar(60) DEFAULT NULL,
  `user_reference` varchar(100) DEFAULT NULL,
  `mot_one_user_id` varchar(100) DEFAULT NULL,
  `title_id` smallint(5) unsigned DEFAULT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `middle_name` varchar(45) DEFAULT NULL,
  `family_name` varchar(45) NOT NULL,
  `driving_licence_id` int(10) unsigned DEFAULT NULL,
  `gender_id` smallint(5) unsigned DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `disability` text,
  `demo_test_tester_status_id` smallint(5) unsigned DEFAULT NULL,
  `otp_failed_attempts` smallint(5) unsigned DEFAULT NULL,
  `is_account_claim_required` tinyint(3) unsigned DEFAULT '1',
  `transition_status_id` smallint(5) unsigned DEFAULT NULL,
  `mot1_userid` varchar(8) DEFAULT NULL,
  `mot1_current_smartcard_id` varchar(100) DEFAULT NULL,
  `2fa_token_id` varchar(100) DEFAULT NULL COMMENT 'current 2FA token identifier',
  `2fa_token_sent_on` datetime(6) DEFAULT NULL COMMENT 'date current 2fa token was sent',
  `details_confirmed_on` datetime(6) DEFAULT NULL,
  `first_training_test_done_on` datetime(6) DEFAULT NULL COMMENT 'Date first MOT2 training test was done',
  `first_live_test_done_on` datetime(6) DEFAULT NULL COMMENT 'Date first live MOT2 test was done by user',
  `mot1_details_updated_on` datetime(6) DEFAULT NULL COMMENT 'Date of last MOT1 sync update for this record',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `fk_person_1_idx` (`driving_licence_id`),
  KEY `fk_person_title` (`title_id`),
  KEY `fk_person_gender` (`gender_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_person_1` FOREIGN KEY (`driving_licence_id`) REFERENCES `licence` (`id`),
  CONSTRAINT `fk_person_gender` FOREIGN KEY (`gender_id`) REFERENCES `gender` (`id`),
  CONSTRAINT `fk_person_title` FOREIGN KEY (`title_id`) REFERENCES `title` (`id`),
  CONSTRAINT `person_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `person_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3011 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `person_accesslog`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_accesslog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `person_id` int(10) unsigned NOT NULL,
  `is_successful` tinyint(4) NOT NULL DEFAULT '0',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_person_accesslog_created` (`created_by`),
  KEY `fk_person_accesslog_updated` (`last_updated_by`),
  KEY `fk_person_accesslog_person1` (`person_id`),
  CONSTRAINT `fk_person_accesslog_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_person_accesslog_person1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_person_accesslog_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `person_contact_detail_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_contact_detail_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contact_type_id` smallint(5) unsigned NOT NULL,
  `person_id` int(10) unsigned NOT NULL,
  `contact_id` int(10) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_person_contact_detail_map_contact_details` (`contact_id`),
  KEY `fk_person_contact_detail_map_person_contact_type` (`contact_type_id`),
  KEY `fk_person_contact_detail_map_person` (`person_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_person_contact_detail_map_contact_details` FOREIGN KEY (`contact_id`) REFERENCES `contact_detail` (`id`),
  CONSTRAINT `fk_person_contact_detail_map_person` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_person_contact_detail_map_person_contact_type` FOREIGN KEY (`contact_type_id`) REFERENCES `person_contact_type` (`id`),
  CONSTRAINT `person_contact_detail_map_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `person_contact_detail_map_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=302 DEFAULT CHARSET=utf8 COMMENT='Used to link a person to their contact details';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `person_contact_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_contact_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_person_contact_type_code` (`code`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `person_contact_type_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `person_contact_type_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='The contact type for a contact record held against a person';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `person_identifying_token_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_identifying_token_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `person_id` int(10) unsigned NOT NULL,
  `identifying_token_id` int(10) unsigned NOT NULL,
  `start_date` datetime(6) DEFAULT NULL,
  `end_date` datetime(6) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_generic table_identifying_token1` (`identifying_token_id`),
  KEY `fk_generic table_person1` (`person_id`),
  KEY `fk_person_identifying_token_map_created` (`created_by`),
  KEY `fk_person_identifying_token_map_updated` (`last_updated_by`),
  CONSTRAINT `fk_generic table_identifying_token1` FOREIGN KEY (`identifying_token_id`) REFERENCES `identifying_token` (`id`),
  CONSTRAINT `fk_generic table_person1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_person_identifying_token_map_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_person_identifying_token_map_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `person_security_question_answer`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_security_question_answer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `person_security_question_map_id` int(10) unsigned NOT NULL,
  `is_answered_correctly` tinyint(4) NOT NULL DEFAULT '0',
  `is_service_desk` tinyint(4) NOT NULL DEFAULT '0',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_answer_person_security_question_map` (`person_security_question_map_id`),
  KEY `fk_person_security_question_answer_created` (`created_by`),
  KEY `fk_person_security_question_answer_updated` (`last_updated_by`),
  CONSTRAINT `fk_answer_person_security_question_map` FOREIGN KEY (`person_security_question_map_id`) REFERENCES `person_security_question_map` (`id`),
  CONSTRAINT `fk_person_security_question_answer_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_person_security_question_answer_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `person_security_question_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_security_question_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `person_id` int(10) unsigned NOT NULL,
  `security_question_id` int(10) unsigned NOT NULL,
  `answer` varchar(80) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_person_has_security_question_person1` (`person_id`),
  KEY `fk_person_has_security_question_security_question1` (`security_question_id`),
  KEY `fk_person_security_question_map_created` (`created_by`),
  KEY `fk_person_security_question_map_updated` (`last_updated_by`),
  CONSTRAINT `fk_person_has_security_question_person1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_person_has_security_question_security_question1` FOREIGN KEY (`security_question_id`) REFERENCES `security_question` (`id`),
  CONSTRAINT `fk_person_security_question_map_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_person_security_question_map_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=517 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `person_system_role`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_system_role` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `full_name` varchar(250) NOT NULL,
  `short_name` varchar(50) NOT NULL,
  `role_id` int(10) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_person_system_role_role_idx` (`role_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_person_system_role_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`),
  CONSTRAINT `person_system_role_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `person_system_role_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='Roles in the system that are not organisation/site specific. e.g. Root, Admin, Login';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `person_system_role_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_system_role_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `person_id` int(10) unsigned NOT NULL,
  `person_system_role_id` smallint(5) unsigned NOT NULL,
  `status_id` smallint(5) unsigned NOT NULL,
  `status_changed_on` datetime(6) DEFAULT NULL,
  `valid_from` datetime(6) DEFAULT NULL,
  `expiry_date` datetime(6) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_person_system_role_map_person` (`person_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `fk_person_system_role_map_role` (`person_system_role_id`),
  KEY `fk_person_system_role_map_status` (`status_id`),
  CONSTRAINT `fk_person_system_role_map` FOREIGN KEY (`person_system_role_id`) REFERENCES `person_system_role` (`id`),
  CONSTRAINT `fk_person_system_role_map_person` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_person_system_role_map_status` FOREIGN KEY (`status_id`) REFERENCES `business_role_status` (`id`),
  CONSTRAINT `person_system_role_map_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `person_system_role_map_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=303 DEFAULT CHARSET=utf8 COMMENT='A persons role within the system  i.e. Admin';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `phone`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phone` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contact_detail_id` int(10) unsigned NOT NULL,
  `number` varchar(24) NOT NULL,
  `phone_contact_type_id` smallint(5) unsigned NOT NULL,
  `is_primary` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_phone_contact_detail_id` (`contact_detail_id`),
  KEY `fk_phone_contact_phone_contact_type_id` (`phone_contact_type_id`),
  KEY `fk_phone_person_created_by` (`created_by`),
  KEY `fk_phone_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_phone_contact_detail_id` FOREIGN KEY (`contact_detail_id`) REFERENCES `contact_detail` (`id`),
  CONSTRAINT `fk_phone_contact_phone_contact_type_id` FOREIGN KEY (`phone_contact_type_id`) REFERENCES `phone_contact_type` (`id`),
  CONSTRAINT `fk_phone_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_phone_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='Representation of a phone number.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `phone_contact_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phone_contact_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_phone_contact_type_code` (`code`),
  KEY `fk_phone_contact_type_person_created_by` (`created_by`),
  KEY `fk_phone_contact_type_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_phone_contact_type_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_phone_contact_type_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Normalisation of the type of contact record.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `qualification`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qualification` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `qualification_type_id` smallint(5) unsigned NOT NULL,
  `awarded_by_organisation_id` int(10) unsigned NOT NULL,
  `country_lookup_id` smallint(5) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_qualification_qualification_type` (`qualification_type_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `fk_qualification_country_lookup_id` (`country_lookup_id`),
  CONSTRAINT `fk_qualification_country_lookup_id` FOREIGN KEY (`country_lookup_id`) REFERENCES `country_lookup` (`id`),
  CONSTRAINT `fk_qualification_qualification_type` FOREIGN KEY (`qualification_type_id`) REFERENCES `qualification_type` (`id`),
  CONSTRAINT `qualification_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `qualification_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='Qualification held by a person';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `qualification_award`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qualification_award` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `person_id` int(10) unsigned NOT NULL,
  `qualification_id` int(10) unsigned NOT NULL,
  `certificate_number` varchar(50) NOT NULL,
  `country_lookup_id` smallint(5) unsigned NOT NULL,
  `awarded_on` datetime(6) DEFAULT NULL,
  `verified_by` int(10) unsigned NOT NULL,
  `verified_on` datetime(6) NOT NULL,
  `expiry_date` datetime(6) DEFAULT NULL,
  `status_id` smallint(5) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_qualification_award_person` (`person_id`),
  KEY `fk_qualification_award_qualification` (`qualification_id`),
  KEY `fk_qualification_award_auth_status` (`status_id`),
  KEY `fk_qualification_award_verified` (`verified_by`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `fk_qualification_award_country_lookup_id` (`country_lookup_id`),
  CONSTRAINT `fk_qualification_award_auth_status` FOREIGN KEY (`status_id`) REFERENCES `auth_for_ae_status` (`id`),
  CONSTRAINT `fk_qualification_award_country_lookup_id` FOREIGN KEY (`country_lookup_id`) REFERENCES `country_lookup` (`id`),
  CONSTRAINT `fk_qualification_award_person` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_qualification_award_qualification` FOREIGN KEY (`qualification_id`) REFERENCES `qualification` (`id`),
  CONSTRAINT `fk_qualification_award_verified` FOREIGN KEY (`verified_by`) REFERENCES `person` (`id`),
  CONSTRAINT `qualification_award_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `qualification_award_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Instance of a qualification awarded to a person';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `qualification_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qualification_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `qualification_type_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `qualification_type_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Normalisation of the type of qualification held by a person';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reason_for_rejection`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reason_for_rejection` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `test_item_category_id` int(10) unsigned NOT NULL,
  `test_item_selector_name` varchar(100) NOT NULL,
  `test_item_selector_name_cy` varchar(100) NOT NULL,
  `inspection_manual_reference` varchar(10) NOT NULL,
  `minor_item` tinyint(4) NOT NULL,
  `location_marker` tinyint(4) NOT NULL,
  `qt_marker` tinyint(4) NOT NULL,
  `note` tinyint(4) NOT NULL,
  `manual` varchar(1) NOT NULL,
  `spec_proc` tinyint(4) NOT NULL,
  `is_advisory` tinyint(3) unsigned NOT NULL,
  `is_prs_fail` tinyint(3) unsigned NOT NULL,
  `section_test_item_selector_id` int(10) unsigned NOT NULL,
  `can_be_dangerous` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Flag to show whether to display failure_dangerous checkbox',
  `date_first_used` datetime DEFAULT NULL,
  `audience` varchar(1) NOT NULL DEFAULT 'b' COMMENT 'TODO: This should be replaced by a business rule when rules engine supports permissions',
  `end_date` date DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_test_item_selector_rfr_test_item_selector_id` (`test_item_category_id`),
  KEY `fk_test_item_selector_rfr_section_test_item_selector_id` (`section_test_item_selector_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  FULLTEXT KEY `description` (`test_item_selector_name`),
  CONSTRAINT `fk_test_item_selector_rfr_section_test_item_selector_id` FOREIGN KEY (`section_test_item_selector_id`) REFERENCES `test_item_category` (`id`),
  CONSTRAINT `fk_test_item_selector_rfr_test_item_selector_id` FOREIGN KEY (`test_item_category_id`) REFERENCES `test_item_category` (`id`),
  CONSTRAINT `reason_for_rejection_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `reason_for_rejection_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10108 DEFAULT CHARSET=utf8 COMMENT='Reasons For Rejection including advisory only and items not tested';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reason_for_rejection_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reason_for_rejection_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) NOT NULL,
  `description` varchar(250) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_reason_for_rejection_type_name` (`name`),
  UNIQUE KEY `uk_reason_for_rejection_type_code` (`code`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `reason_for_rejection_type_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `reason_for_rejection_type_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='Normalisation of type of RFR';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `replacement_certificate_draft`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `replacement_certificate_draft` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mot_test_id` bigint(20) unsigned NOT NULL,
  `mot_test_version` int(10) unsigned NOT NULL,
  `odometer_reading_id` bigint(20) unsigned DEFAULT NULL,
  `vrm` varchar(20) DEFAULT NULL,
  `empty_vrm_reason_id` smallint(5) unsigned DEFAULT NULL,
  `vin` varchar(30) DEFAULT NULL,
  `empty_vin_reason_id` smallint(5) unsigned DEFAULT NULL,
  `vehicle_testing_station_id` int(10) unsigned DEFAULT NULL,
  `make_id` int(10) unsigned DEFAULT NULL,
  `model_id` int(10) unsigned DEFAULT NULL,
  `primary_colour_id` smallint(5) unsigned NOT NULL,
  `secondary_colour_id` smallint(5) unsigned DEFAULT NULL,
  `country_of_registration_id` smallint(5) unsigned NOT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `different_tester_reason_id` smallint(5) unsigned DEFAULT NULL,
  `replacement_reason` text,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_replacement_certificate_draft_make_and_model` (`make_id`,`model_id`),
  KEY `fk_replacement_certificate_draft_odometer_reading` (`odometer_reading_id`),
  KEY `fk_different_tester_reason_id` (`different_tester_reason_id`),
  KEY `fk_replacement_certificate_draft_vts` (`vehicle_testing_station_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `fk_replacement_certificate_draft_cor` (`country_of_registration_id`),
  KEY `fk_replacement_certificate_draft_primary_colour` (`primary_colour_id`),
  KEY `fk_replacement_certificate_draft_secondary_colour` (`secondary_colour_id`),
  KEY `fk_replacement_certificate_draft_mot_test_id` (`mot_test_id`),
  KEY `fk_replacement_certificate_draft_empty_vrm_reason_id` (`empty_vrm_reason_id`),
  KEY `fk_replacement_certificate_draft_empty_vin_reason_id` (`empty_vin_reason_id`),
  CONSTRAINT `fk_different_tester_reason_id` FOREIGN KEY (`different_tester_reason_id`) REFERENCES `certificate_change_different_tester_reason_lookup` (`id`),
  CONSTRAINT `fk_replacement_certificate_draft_cor` FOREIGN KEY (`country_of_registration_id`) REFERENCES `country_of_registration_lookup` (`id`),
  CONSTRAINT `fk_replacement_certificate_draft_empty_vin_reason_id` FOREIGN KEY (`empty_vin_reason_id`) REFERENCES `empty_vin_reason_lookup` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_replacement_certificate_draft_empty_vrm_reason_id` FOREIGN KEY (`empty_vrm_reason_id`) REFERENCES `empty_vrm_reason_lookup` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `fk_replacement_certificate_draft_make_and_model` FOREIGN KEY (`make_id`, `model_id`) REFERENCES `model` (`make_id`, `id`),
  CONSTRAINT `fk_replacement_certificate_draft_mot_test_id` FOREIGN KEY (`mot_test_id`) REFERENCES `mot_test` (`id`),
  CONSTRAINT `fk_replacement_certificate_draft_odometer_reading` FOREIGN KEY (`odometer_reading_id`) REFERENCES `odometer_reading` (`id`),
  CONSTRAINT `fk_replacement_certificate_draft_primary_colour` FOREIGN KEY (`primary_colour_id`) REFERENCES `colour_lookup` (`id`),
  CONSTRAINT `fk_replacement_certificate_draft_secondary_colour` FOREIGN KEY (`secondary_colour_id`) REFERENCES `colour_lookup` (`id`),
  CONSTRAINT `fk_replacement_certificate_draft_vts` FOREIGN KEY (`vehicle_testing_station_id`) REFERENCES `site` (`id`),
  CONSTRAINT `replacement_certificate_draft_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `replacement_certificate_draft_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rfr_business_rule_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rfr_business_rule_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rfr_id` int(10) unsigned NOT NULL,
  `business_rule_id` int(10) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_rfr_business_rule_map_reason_for_rejection1` (`rfr_id`),
  KEY `fk_rfr_business_rule_map_business_rule1` (`business_rule_id`),
  KEY `fk_rfr_business_rule_map_created` (`created_by`),
  KEY `fk_rfr_business_rule_map_updated` (`last_updated_by`),
  CONSTRAINT `fk_rfr_business_rule_map_business_rule1` FOREIGN KEY (`business_rule_id`) REFERENCES `business_rule` (`id`),
  CONSTRAINT `fk_rfr_business_rule_map_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_rfr_business_rule_map_reason_for_rejection1` FOREIGN KEY (`rfr_id`) REFERENCES `reason_for_rejection` (`id`),
  CONSTRAINT `fk_rfr_business_rule_map_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Business rule on how to apply this RFR helps to facilitate mutiple rules based on class i.e. rule may apply to older class 4 then class 5 vehicles';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rfr_language_content_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rfr_language_content_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rfr_id` int(10) unsigned NOT NULL,
  `language_type_id` smallint(5) unsigned NOT NULL,
  `name` varchar(500) NOT NULL,
  `inspection_manual_description` varchar(500) DEFAULT NULL,
  `advisory_text` varchar(250) DEFAULT NULL,
  `test_item_selector_name` varchar(100) DEFAULT NULL COMMENT 'FOR FULLTEXT SEARCH',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_rfr_language_media_map_reason_for_rejection1` (`rfr_id`),
  KEY `fk_rfr_language_media_map_language_type1` (`language_type_id`),
  FULLTEXT KEY `name` (`name`,`test_item_selector_name`),
  CONSTRAINT `fk_rfr_language_media_map_language_type1` FOREIGN KEY (`language_type_id`) REFERENCES `language_type` (`id`),
  CONSTRAINT `fk_rfr_language_media_map_reason_for_rejection1` FOREIGN KEY (`rfr_id`) REFERENCES `reason_for_rejection` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10101 DEFAULT CHARSET=utf8 COMMENT='How RFRs are displayed in different languages and presented';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rfr_vehicle_class_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rfr_vehicle_class_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rfr_id` int(10) unsigned NOT NULL,
  `vehicle_class_id` smallint(5) unsigned NOT NULL,
  `business_rule_id` int(10) unsigned DEFAULT NULL COMMENT 'Filter for RFRs to handle rules that require more than vehicle class',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_rfr_vehicle_class_map_test_item_selector_rfr` (`rfr_id`),
  KEY `fk_rfr_vehicle_class_map` (`vehicle_class_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `fk_rfr_vehicle_class_map_business_rule1` (`business_rule_id`),
  CONSTRAINT `fk_rfr_vehicle_class_map_business_rule1` FOREIGN KEY (`business_rule_id`) REFERENCES `business_rule` (`id`),
  CONSTRAINT `fk_rfr_vehicle_class_map_test_item_selector_rfr` FOREIGN KEY (`rfr_id`) REFERENCES `reason_for_rejection` (`id`),
  CONSTRAINT `fk_rfr_vehicle_class_mapq` FOREIGN KEY (`vehicle_class_id`) REFERENCES `vehicle_class` (`id`),
  CONSTRAINT `rfr_vehicle_class_map_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `rfr_vehicle_class_map_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10384 DEFAULT CHARSET=utf8 COMMENT='reasons for rejection for a specific class';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `role`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(40) CHARACTER SET latin1 NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_role_code` (`code`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `role_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `role_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `role_permission_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_permission_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_role_permission_map_role` (`role_id`),
  KEY `fk_role_permission_map_permission` (`permission_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_role_permission_map_permission` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`),
  CONSTRAINT `fk_role_permission_map_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`),
  CONSTRAINT `role_permission_map_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `role_permission_map_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=523 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `security_question`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `security_question` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question_text` varchar(80) NOT NULL,
  `question_group` tinyint(4) NOT NULL,
  `display_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_security_question_created` (`created_by`),
  KEY `fk_security_question_updated` (`last_updated_by`),
  CONSTRAINT `fk_security_question_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_security_question_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `site`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `site_number` varchar(45) DEFAULT NULL,
  `default_brake_test_class_1_and_2_id` smallint(5) unsigned DEFAULT NULL,
  `default_service_brake_test_class_3_and_above_id` smallint(5) unsigned DEFAULT NULL,
  `default_parking_brake_test_class_3_and_above_id` smallint(5) unsigned DEFAULT NULL,
  `last_site_assessment_id` int(10) unsigned DEFAULT NULL,
  `dual_language` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `scottish_bank_holiday` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `latitude` decimal(8,5) DEFAULT NULL,
  `longitude` decimal(8,5) DEFAULT NULL,
  `type_id` smallint(5) unsigned NOT NULL,
  `transition_status_id` smallint(5) unsigned DEFAULT NULL,
  `non_working_day_country_lookup_id` smallint(5) unsigned DEFAULT NULL,
  `first_login_by` int(10) unsigned DEFAULT NULL,
  `first_login_on` datetime(6) DEFAULT NULL,
  `first_test_carried_out_by` int(10) unsigned DEFAULT NULL,
  `first_test_carried_out_number` int(10) unsigned DEFAULT NULL,
  `first_test_carried_out_on` datetime(6) DEFAULT NULL,
  `first_live_test_carried_out_by` int(10) unsigned DEFAULT NULL,
  `first_live_test_carried_out_number` int(10) unsigned DEFAULT NULL,
  `first_live_test_carried_out_on` datetime(6) DEFAULT NULL,
  `mot1_details_updated_on` datetime(6) DEFAULT NULL,
  `mot1_vts_device_status_id` smallint(5) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `bk_site` (`organisation_id`,`site_number`),
  KEY `fk_site_first_test_person` (`first_test_carried_out_by`) USING BTREE,
  KEY `fk_site_first_login_person` (`first_login_by`),
  KEY `fk_site_first_live_test_person` (`first_live_test_carried_out_by`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `fk_site_type_id` (`type_id`),
  KEY `fk_site_assessment_id` (`last_site_assessment_id`),
  KEY `fk_site_mot1_vts_device_status_id` (`mot1_vts_device_status_id`),
  KEY `fk_site_transition_status_id` (`transition_status_id`),
  KEY `fk_site_default_brake_test_class_1_and_2_id` (`default_brake_test_class_1_and_2_id`),
  KEY `fk_site_default_service_brake_test_class_3_and_above_id` (`default_service_brake_test_class_3_and_above_id`),
  KEY `fk_site_default_parking_brake_test_class_3_and_above_id` (`default_parking_brake_test_class_3_and_above_id`),
  KEY `fk_site_non_working_day_country_lookup_id` (`non_working_day_country_lookup_id`),
  CONSTRAINT `fk_site_assessment_id` FOREIGN KEY (`last_site_assessment_id`) REFERENCES `enforcement_site_assessment` (`id`),
  CONSTRAINT `fk_site_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_site_default_brake_test_class_1_and_2_id` FOREIGN KEY (`default_brake_test_class_1_and_2_id`) REFERENCES `brake_test_type` (`id`),
  CONSTRAINT `fk_site_default_parking_brake_test_class_3_and_above_id` FOREIGN KEY (`default_parking_brake_test_class_3_and_above_id`) REFERENCES `brake_test_type` (`id`),
  CONSTRAINT `fk_site_default_service_brake_test_class_3_and_above_id` FOREIGN KEY (`default_service_brake_test_class_3_and_above_id`) REFERENCES `brake_test_type` (`id`),
  CONSTRAINT `fk_site_first_live_test_person` FOREIGN KEY (`first_live_test_carried_out_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_site_first_login_person` FOREIGN KEY (`first_login_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_site_first_test_person` FOREIGN KEY (`first_test_carried_out_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_site_mot1_vts_device_status_id` FOREIGN KEY (`mot1_vts_device_status_id`) REFERENCES `mot1_vts_device_status` (`id`),
  CONSTRAINT `fk_site_non_working_day_country_lookup_id` FOREIGN KEY (`non_working_day_country_lookup_id`) REFERENCES `non_working_day_country_lookup` (`id`),
  CONSTRAINT `fk_site_organisation` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`),
  CONSTRAINT `fk_site_transition_status_id` FOREIGN KEY (`transition_status_id`) REFERENCES `transition_status` (`id`),
  CONSTRAINT `fk_site_type_id` FOREIGN KEY (`type_id`) REFERENCES `site_type` (`id`),
  CONSTRAINT `fk_site_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2007 DEFAULT CHARSET=utf8 COMMENT='Representation of a site in the system';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `site_assembly_role_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_assembly_role_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL,
  `assembly_id` int(10) unsigned NOT NULL,
  `assembly_role_id` smallint(5) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_site_assembly_role_map_site_id_assembly_id_assembly_role_id` (`site_id`,`assembly_id`,`assembly_role_id`),
  KEY `fk_site_assembly_role_map_assembly_id` (`assembly_id`),
  KEY `fk_site_assembly_role_map_assembly_role_id` (`assembly_role_id`),
  KEY `fk_site_assembly_role_map_person_created_by` (`created_by`),
  KEY `fk_site_assembly_role_map_person_last_updated_by` (`last_updated_by`),
  KEY `fk_site_assembly_role_map_site_id` (`site_id`),
  CONSTRAINT `fk_site_assembly_role_map_assembly_id` FOREIGN KEY (`assembly_id`) REFERENCES `assembly` (`id`),
  CONSTRAINT `fk_site_assembly_role_map_assembly_role_id` FOREIGN KEY (`assembly_role_id`) REFERENCES `assembly_role_type` (`id`),
  CONSTRAINT `fk_site_assembly_role_map_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_site_assembly_role_map_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_site_assembly_role_map_site_id` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `site_business_role`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_business_role` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned DEFAULT NULL,
  `code` varchar(40) CHARACTER SET latin1 NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL,
  `organisation_type_id` smallint(5) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `fk_site_business_role_role` (`role_id`),
  KEY `fk_site_business_role_organisation_type_id` (`organisation_type_id`),
  CONSTRAINT `fk_site_business_role_organisation_type_id` FOREIGN KEY (`organisation_type_id`) REFERENCES `organisation_type` (`id`),
  CONSTRAINT `fk_site_business_role_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`),
  CONSTRAINT `site_business_role_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `site_business_role_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `site_business_role_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_business_role_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL,
  `person_id` int(10) unsigned NOT NULL,
  `site_business_role_id` smallint(5) unsigned NOT NULL,
  `status_id` smallint(5) unsigned NOT NULL,
  `status_changed_on` datetime(6) DEFAULT NULL,
  `valid_from` datetime(6) DEFAULT NULL,
  `expiry_date` datetime(6) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_site_role_map_site` (`site_id`),
  KEY `fk_site_role_map_person` (`person_id`),
  KEY `fk_site_role_map_status` (`status_id`),
  KEY `fk_site_business_role_map` (`site_business_role_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_site_business_role_map` FOREIGN KEY (`site_business_role_id`) REFERENCES `site_business_role` (`id`),
  CONSTRAINT `fk_site_role_map_person` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_site_role_map_site` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
  CONSTRAINT `fk_site_role_map_status` FOREIGN KEY (`status_id`) REFERENCES `business_role_status` (`id`),
  CONSTRAINT `site_business_role_map_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `site_business_role_map_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=151 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `site_comment_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_comment_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL,
  `comment_id` bigint(20) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_site_comment_map_site` (`site_id`),
  KEY `fk_site_comment_map_comment` (`comment_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_site_comment_map_comment` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`),
  CONSTRAINT `fk_site_comment_map_site` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
  CONSTRAINT `site_comment_map_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `site_comment_map_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Used to link a comment made against a site.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `site_condition_approval`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_condition_approval` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL,
  `interviewed_name` varchar(100) NOT NULL,
  `interviewed_grade` varchar(100) NOT NULL,
  `visit_date` date NOT NULL,
  `fuel_id` smallint(5) unsigned NOT NULL,
  `atl_mode` tinyint(4) NOT NULL DEFAULT '0',
  `optl_mode` tinyint(4) NOT NULL DEFAULT '0',
  `comment_id` bigint(20) unsigned DEFAULT NULL,
  `ve_name` varchar(100) NOT NULL,
  `ve_grade` varchar(100) NOT NULL,
  `approval_date` date NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`),
  KEY `fuel_id` (`fuel_id`),
  KEY `comment_id` (`comment_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `sca_comment` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`),
  CONSTRAINT `sca_enforcement_fuel_type_lookup` FOREIGN KEY (`fuel_id`) REFERENCES `enforcement_fuel_type_lookup` (`id`),
  CONSTRAINT `sca_site` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
  CONSTRAINT `site_condition_approval_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `site_condition_approval_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Records results of a condition approval visit by a VE to a site';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `site_contact_detail_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_contact_detail_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_contact_type_id` smallint(5) unsigned NOT NULL,
  `site_id` int(10) unsigned NOT NULL,
  `contact_detail_id` int(10) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_site_contact_detail_map_contact_detail` (`contact_detail_id`),
  KEY `fk_site_contact_detail_map_site_contact_type` (`site_contact_type_id`),
  KEY `fk_site_contact_detail_map_site` (`site_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_site_contact_detail_map_contact_detail` FOREIGN KEY (`contact_detail_id`) REFERENCES `contact_detail` (`id`),
  CONSTRAINT `fk_site_contact_detail_map_site` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
  CONSTRAINT `fk_site_contact_detail_map_site_contact_type` FOREIGN KEY (`site_contact_type_id`) REFERENCES `site_contact_type` (`id`),
  CONSTRAINT `site_contact_detail_map_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `site_contact_detail_map_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25001 DEFAULT CHARSET=utf8 COMMENT='Link table between a site and a set of contact details';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `site_contact_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_contact_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_site_contact_type_code` (`code`),
  KEY `fk_site_contact_type_person_created_by` (`created_by`),
  KEY `fk_site_contact_type_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_site_contact_type_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_site_contact_type_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Normalisation of types of contact detail at a site';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `site_emergency_log_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_emergency_log_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL,
  `emergency_log_id` int(10) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_site_emergency_log_map_emergency_log1` (`emergency_log_id`),
  KEY `fk_site_emergency_log_map_site1` (`site_id`),
  CONSTRAINT `fk_site_emergency_log_map_emergency_log1` FOREIGN KEY (`emergency_log_id`) REFERENCES `emergency_log` (`id`),
  CONSTRAINT `fk_site_emergency_log_map_site1` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='sites confirm that they have completed catchup';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `site_facility`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_facility` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL,
  `facility_type_id` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`),
  KEY `facility_type_id` (`facility_type_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_site_fac_facility_type` FOREIGN KEY (`facility_type_id`) REFERENCES `facility_type` (`id`),
  CONSTRAINT `fk_site_id` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
  CONSTRAINT `site_facility_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `site_facility_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='Facilities available at sites';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `site_identifying_token_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_identifying_token_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL,
  `identifying_token_id` int(10) unsigned NOT NULL,
  `is_assigned_to_person` tinyint(4) NOT NULL DEFAULT '0',
  `start_date` datetime(6) DEFAULT NULL,
  `end_date` datetime(6) DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_generic table_site1` (`site_id`),
  KEY `fk_site_identifying_token_map_identifying_token1` (`identifying_token_id`),
  CONSTRAINT `fk_generic table_site1` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
  CONSTRAINT `fk_site_identifying_token_map_identifying_token1` FOREIGN KEY (`identifying_token_id`) REFERENCES `identifying_token` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `site_testing_daily_schedule`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_testing_daily_schedule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL,
  `open_time` time DEFAULT NULL,
  `close_time` time DEFAULT NULL,
  `weekday` tinyint(3) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`site_id`,`weekday`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `site_testing_daily_schedule_ibfk_1` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
  CONSTRAINT `site_testing_daily_schedule_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `site_testing_daily_schedule_ibfk_3` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='Records the normal operating hours of a site. -- Feature depricated';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `site_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `name` varchar(50) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  KEY `uk_site_type_code` (`code`),
  CONSTRAINT `site_type_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `site_type_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='Normalisation of a type of site';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `special_notice`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `special_notice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `person_id` int(10) unsigned DEFAULT NULL,
  `special_notice_content_id` int(10) unsigned NOT NULL,
  `is_acknowledged` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `is_deleted` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`special_notice_content_id`),
  KEY `fk_map_person_special_notice_special_notice` (`special_notice_content_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_map_person_special_notice_special_notice` FOREIGN KEY (`special_notice_content_id`) REFERENCES `special_notice_content` (`id`),
  CONSTRAINT `fk_map_person_special_notice_user` FOREIGN KEY (`username`) REFERENCES `person` (`username`),
  CONSTRAINT `special_notice_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `special_notice_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2151 DEFAULT CHARSET=utf8 COMMENT='Acknowledgement status of each special notice for each person in the system';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `special_notice_audience`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `special_notice_audience` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `special_notice_content_id` int(10) unsigned NOT NULL,
  `special_notice_audience_type_id` smallint(5) unsigned NOT NULL,
  `vehicle_class_id` smallint(5) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `special_notice_content_id` (`special_notice_content_id`,`special_notice_audience_type_id`,`vehicle_class_id`),
  KEY `special_notice_audience_type_id` (`special_notice_audience_type_id`),
  KEY `vehicle_class_id` (`vehicle_class_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `special_notice_audience_ibfk_1` FOREIGN KEY (`special_notice_content_id`) REFERENCES `special_notice_content` (`id`),
  CONSTRAINT `special_notice_audience_ibfk_2` FOREIGN KEY (`special_notice_audience_type_id`) REFERENCES `special_notice_audience_type` (`id`),
  CONSTRAINT `special_notice_audience_ibfk_3` FOREIGN KEY (`vehicle_class_id`) REFERENCES `vehicle_class` (`id`),
  CONSTRAINT `special_notice_audience_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `special_notice_audience_ibfk_5` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `special_notice_audience_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `special_notice_audience_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `special_notice_audience_type_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `special_notice_audience_type_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `special_notice_content`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `special_notice_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `issue_number` int(10) unsigned NOT NULL,
  `issue_year` int(10) unsigned NOT NULL,
  `issue_date` datetime(6) NOT NULL,
  `expiry_date` datetime(6) NOT NULL,
  `internal_publish_date` datetime(6) NOT NULL COMMENT 'DVSA broadcast date',
  `external_publish_date` datetime(6) NOT NULL COMMENT 'VTS broadcast date',
  `notice_text` text NOT NULL,
  `acknowledge_within` smallint(5) unsigned DEFAULT NULL COMMENT 'NULL for messages that dont require acknowledgement',
  `is_published` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `is_deleted` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mot1_legacy_id` varchar(80) DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `issue_number` (`issue_number`,`issue_year`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `special_notice_content_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `special_notice_content_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Representation of special notices';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `special_notice_content_role_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `special_notice_content_role_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `special_notice_content_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `special_notice_content_id` (`special_notice_content_id`,`role_id`),
  KEY `fk_special_notice_content_role_map_role` (`role_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_special_notice_content_role_map_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`),
  CONSTRAINT `fk_special_notice_content_role_map_special_notice` FOREIGN KEY (`special_notice_content_id`) REFERENCES `special_notice_content` (`id`),
  CONSTRAINT `special_notice_content_role_map_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `special_notice_content_role_map_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Maps special notices to audiences for special notices';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `test_item_category`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test_item_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_test_item_category_id` int(10) unsigned NOT NULL,
  `section_test_item_category_id` int(10) unsigned NOT NULL,
  `business_rule_id` int(10) unsigned DEFAULT NULL COMMENT 'Possible business rule for filtering categories',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_test_item_category_parent_test_item_category_id` (`parent_test_item_category_id`),
  KEY `fk_test_item_category_business_rule_id_business_rule_id` (`business_rule_id`),
  KEY `fk_test_item_category_created_by_person_id` (`created_by`),
  KEY `fk_test_item_category_last_updated_by_person_id` (`last_updated_by`),
  CONSTRAINT `fk_test_item_category_business_rule_id_business_rule_id` FOREIGN KEY (`business_rule_id`) REFERENCES `business_rule` (`id`),
  CONSTRAINT `fk_test_item_category_last_updated_by_person_id` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_test_item_category_parent_test_item_category_id` FOREIGN KEY (`parent_test_item_category_id`) REFERENCES `test_item_category` (`id`),
  CONSTRAINT `fk_test_item_category_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10155 DEFAULT CHARSET=utf8 COMMENT='Groups RFRs hierarchically';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `test_item_category_vehicle_class_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test_item_category_vehicle_class_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `test_item_category_id` int(10) unsigned NOT NULL,
  `vehicle_class_id` smallint(5) unsigned NOT NULL,
  `business_rule_id` int(10) unsigned DEFAULT NULL COMMENT 'Possible business rule for filtering categories',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_test_item_category_vehicle_class_map_test_item_category_id` (`test_item_category_id`),
  KEY `fk_test_item_category_vehicle_class_map_vehicle_class_id` (`vehicle_class_id`),
  KEY `fk_test_item_category_vehicle_class_map_business_rule_id` (`business_rule_id`),
  KEY `fk_test_item_category_vehicle_class_map_created_by_person_id` (`created_by`),
  KEY `fk_test_item_category_vehicle_class_map_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_test_item_category_vehicle_class_map_business_rule_id` FOREIGN KEY (`business_rule_id`) REFERENCES `business_rule` (`id`),
  CONSTRAINT `fk_test_item_category_vehicle_class_map_created_by_person_id` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_test_item_category_vehicle_class_map_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_test_item_category_vehicle_class_map_test_item_category_id` FOREIGN KEY (`test_item_category_id`) REFERENCES `test_item_category` (`id`),
  CONSTRAINT `fk_test_item_category_vehicle_class_map_vehicle_class_id` FOREIGN KEY (`vehicle_class_id`) REFERENCES `vehicle_class` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2133 DEFAULT CHARSET=utf8 COMMENT='Maps the vehicle classes of each test item category';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `test_slot_transaction`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test_slot_transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slots` int(10) unsigned NOT NULL,
  `slots_after` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Slot balance after buying slots',
  `status_id` smallint(5) unsigned NOT NULL,
  `payment_id` int(10) unsigned NOT NULL,
  `state` varchar(43) NOT NULL,
  `sales_reference` varchar(55) NOT NULL COMMENT 'Sales invoice number',
  `organisation_id` int(10) unsigned NOT NULL,
  `completed_on` datetime(6) DEFAULT NULL,
  `created` datetime(6) NOT NULL,
  `created_by_username` varchar(100) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `state` (`state`),
  KEY `fk_test_slot_transaction_payment` (`payment_id`),
  KEY `fk_test_slot_transaction_organisation_id` (`organisation_id`),
  KEY `fk_test_slot_transaction_status` (`status_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_test_slot_transaction_organisation_id` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`),
  CONSTRAINT `fk_test_slot_transaction_payment` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`id`),
  CONSTRAINT `fk_test_slot_transaction_status` FOREIGN KEY (`status_id`) REFERENCES `test_slot_transaction_status` (`id`),
  CONSTRAINT `test_slot_transaction_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `test_slot_transaction_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Records slot purchasing transactions - in progress and complete';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `test_slot_transaction_status`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test_slot_transaction_status` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `test_slot_transaction_status_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `test_slot_transaction_status_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Normalisation of the status of a transaction during the slot purchasing process';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ti_category_language_content_map`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ti_category_language_content_map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `test_item_category_id` int(10) unsigned NOT NULL,
  `language_lookup_id` smallint(5) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(100) DEFAULT NULL COMMENT 'Full version of name used in printed documents',
  `display_order` smallint(5) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_ti_category_language_content_map_display_order_language` (`display_order`,`language_lookup_id`),
  KEY `fk_ti_category_language_content_map_test_item_category_id` (`test_item_category_id`),
  KEY `fk_ti_category_language_content_map_language_lookup_id` (`language_lookup_id`),
  KEY `fk_ti_category_language_content_map_created_by_person_id` (`created_by`),
  KEY `fk_ti_category_language_content_map_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_ti_category_language_content_map_created_by_person_id` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_ti_category_language_content_map_language_lookup_id` FOREIGN KEY (`language_lookup_id`) REFERENCES `language_type` (`id`),
  CONSTRAINT `fk_ti_category_language_content_map_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_ti_category_language_content_map_test_item_category_id` FOREIGN KEY (`test_item_category_id`) REFERENCES `test_item_category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2051 DEFAULT CHARSET=utf8 COMMENT='How test items are displayed in different languages and presented';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `title`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `title` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_title_code` (`code`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `title_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `title_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='Normalisation for the Person table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `token_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `token_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_token_lookup_created` (`created_by`),
  KEY `fk_token_lookup_updated` (`last_updated_by`),
  CONSTRAINT `fk_token_lookup_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_token_lookup_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transition_status`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transition_status` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `code` varchar(5) NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_transition_status_code` (`code`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `transition_status_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `transition_status_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='Normalisation of the current transfer status of an organisation from MOT1 to MOT2';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transmission_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transmission_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) NOT NULL,
  `display_order` smallint(5) unsigned NOT NULL DEFAULT '1',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_transmission_type_code` (`code`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `transmission_type_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `transmission_type_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Normalisation of a vehicles transmission';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `url_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `url_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) DEFAULT NULL,
  `max_count` smallint(6) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_url_type_code` (`code`),
  KEY `fk_message_type_created0` (`created_by`),
  KEY `fk_message_type_updated0` (`last_updated_by`),
  CONSTRAINT `fk_message_type_created0` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_message_type_updated0` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Types of urls that can be used in messages';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vehicle`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `registration` varchar(20) CHARACTER SET latin1 DEFAULT NULL COMMENT 'also called VRM',
  `empty_vrm_reason_id` smallint(5) unsigned DEFAULT NULL,
  `vin` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `empty_vin_reason_id` smallint(5) unsigned DEFAULT NULL,
  `vehicle_class_id` smallint(5) unsigned DEFAULT NULL,
  `make_id` int(10) unsigned DEFAULT NULL,
  `model_id` int(10) unsigned DEFAULT NULL,
  `model_detail_id` int(10) unsigned DEFAULT NULL,
  `body_type_id` smallint(5) unsigned DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `make_name` varchar(50) DEFAULT NULL,
  `model_name` varchar(50) DEFAULT NULL,
  `model_detail_name` varchar(50) DEFAULT NULL,
  `manufacture_date` date DEFAULT NULL,
  `first_registration_date` date DEFAULT NULL,
  `first_used_date` date DEFAULT NULL,
  `primary_colour_id` smallint(5) unsigned NOT NULL,
  `secondary_colour_id` smallint(5) unsigned DEFAULT NULL,
  `fuel_type_id` smallint(5) unsigned DEFAULT NULL,
  `wheelplan_type_id` smallint(5) unsigned DEFAULT NULL COMMENT 'DVLA Wheelplan type is not a maintained table therefor FK relationship not enforce',
  `seating_capacity` smallint(5) unsigned DEFAULT NULL,
  `no_of_seat_belts` smallint(5) unsigned DEFAULT NULL,
  `seat_belts_last_checked` date DEFAULT NULL,
  `weight` int(10) unsigned DEFAULT NULL COMMENT 'in Kg',
  `weight_source_id` smallint(5) unsigned DEFAULT NULL,
  `country_of_registration_id` smallint(5) unsigned NOT NULL,
  `cylinder_capacity` int(10) unsigned DEFAULT NULL,
  `transmission_type_id` smallint(5) unsigned DEFAULT NULL,
  `sva_emission_std` varchar(10) CHARACTER SET latin1 DEFAULT NULL COMMENT 'SVA Emission std The emission standard for Single Vehicle Approval as supplied by VOSA to DVLA. The code is made up of three elements. 1) The first element - the vehicle class - is up to four characters (in any sequence), which describe the vehicle. 2) The second element - "0" - a zero, used only to separate first and third elements. 3) The third element - the emissions identifier, one of the following characters taken from the following list: - A - Visual test only; B - Emission limit 4.5% CO, HC 1200ppm; C - Emission limit 3.5% CO, HC 1200ppm; D - Emission limit 0.5% CO, Fast Idle 0.3% CO, 200ppm HC; Lambda 0.97 - 1.03; E - Emissions limit as per DOT current "In Service Emissions Standards" book; F - Emissions limit Diesel 2.5m-1; G - Emission limit Turbo Diesel 3.0m-1.',
  `engine_number` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `chassis_number` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `is_new_at_first_reg` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `eu_classification` varchar(2) DEFAULT NULL,
  `mass_in_service_weight` int(9) unsigned DEFAULT NULL,
  `dvla_vehicle_id` int(11) DEFAULT NULL COMMENT 'Unique DVLA reference',
  `is_damaged` tinyint(4) NOT NULL DEFAULT '0',
  `is_destroyed` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Destruction marker',
  `is_incognito` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `fk_vehicle_country_of_registration_id` (`country_of_registration_id`),
  KEY `fk_vehicle_colour_id` (`primary_colour_id`),
  KEY `fk_vehicle_secondary_colour_id` (`secondary_colour_id`),
  KEY `fk_vehicle_fuel_lookup_id` (`fuel_type_id`),
  KEY `ix_vehicle_vin_and_registration` (`vin`,`registration`),
  KEY `ix_vehicle_registration` (`registration`),
  KEY `fk_vehicle_make_id` (`make_id`),
  KEY `fk_vehicle_model_id` (`model_id`),
  KEY `fk_vehicle_model_detail_id` (`model_detail_id`),
  KEY `fk_vehicle_vehicle_class_id` (`vehicle_class_id`),
  KEY `fk_weight_source_id` (`weight_source_id`),
  KEY `fk_vehicle_transmission_type_id` (`transmission_type_id`),
  KEY `fk_vehicle_person_created_by` (`created_by`),
  KEY `fk_vehicle_person_last_updated_by` (`last_updated_by`),
  KEY `fk_vehicle_body_type_id_body_type_id` (`body_type_id`),
  KEY `ix_vehicle_dvla_vehicle_id` (`dvla_vehicle_id`),
  KEY `fk_vehicle_empty_vrm_reason_id_empty_vrm_reason_lookup_id` (`empty_vrm_reason_id`),
  KEY `fk_vehicle_empty_vin_reason_id_empty_vin_reason_lookup_id` (`empty_vin_reason_id`),
  CONSTRAINT `fk_vehicle_body_type_id_body_type_id` FOREIGN KEY (`body_type_id`) REFERENCES `body_type` (`id`),
  CONSTRAINT `fk_vehicle_colour_id` FOREIGN KEY (`primary_colour_id`) REFERENCES `colour_lookup` (`id`),
  CONSTRAINT `fk_vehicle_country_of_registration_id` FOREIGN KEY (`country_of_registration_id`) REFERENCES `country_of_registration_lookup` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_vehicle_empty_vin_reason_id_empty_vin_reason_lookup_id` FOREIGN KEY (`empty_vin_reason_id`) REFERENCES `empty_vin_reason_lookup` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_vehicle_empty_vrm_reason_id_empty_vrm_reason_lookup_id` FOREIGN KEY (`empty_vrm_reason_id`) REFERENCES `empty_vrm_reason_lookup` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `fk_vehicle_fuel_type_id` FOREIGN KEY (`fuel_type_id`) REFERENCES `fuel_type` (`id`),
  CONSTRAINT `fk_vehicle_make_id` FOREIGN KEY (`make_id`) REFERENCES `make` (`id`),
  CONSTRAINT `fk_vehicle_model_detail_id` FOREIGN KEY (`model_detail_id`) REFERENCES `model_detail` (`id`),
  CONSTRAINT `fk_vehicle_model_id` FOREIGN KEY (`model_id`) REFERENCES `model` (`id`),
  CONSTRAINT `fk_vehicle_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_vehicle_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_vehicle_secondary_colour_id` FOREIGN KEY (`secondary_colour_id`) REFERENCES `colour_lookup` (`id`),
  CONSTRAINT `fk_vehicle_transmission_type_id` FOREIGN KEY (`transmission_type_id`) REFERENCES `transmission_type` (`id`),
  CONSTRAINT `fk_vehicle_vehicle_class_id` FOREIGN KEY (`vehicle_class_id`) REFERENCES `vehicle_class` (`id`),
  CONSTRAINT `fk_weight_source_id` FOREIGN KEY (`weight_source_id`) REFERENCES `weight_source_lookup` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2033 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vehicle_class`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle_class` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `vehicle_class_group_id` smallint(5) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_vehicle_class_code` (`code`),
  KEY `fx_vehicle_class_vehicle_class_group_id` (`vehicle_class_group_id`),
  KEY `fk_vehicle_class_person_created_by` (`created_by`),
  KEY `fk_vehicle_class_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_vehicle_class_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_vehicle_class_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fx_vehicle_class_vehicle_class_group_id` FOREIGN KEY (`vehicle_class_group_id`) REFERENCES `vehicle_class_group` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='Records of the various vehicle classes';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vehicle_class_group`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle_class_group` (
  `id` smallint(5) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_vehicle_class_group_code` (`code`),
  KEY `fk_vehicle_class_group_person_created_by` (`created_by`),
  KEY `fk_vehicle_class_group_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_vehicle_class_group_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_vehicle_class_group_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vehicle_v5c`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle_v5c` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_id` int(10) unsigned NOT NULL,
  `v5c_ref` varchar(11) NOT NULL,
  `first_seen` date NOT NULL,
  `last_seen` date DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `ix_vehicle_v5c_v5c_ref` (`v5c_ref`),
  KEY `ix_vehicle_v5c_vehicle` (`vehicle_id`),
  KEY `ix_vehicle_v5c_created` (`created_by`),
  KEY `ix_vehicle_v5c_updated` (`last_updated_by`),
  CONSTRAINT `fk_vehicle_v5c_created` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_vehicle_v5c_updated` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_vehicle_v5c_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8 COMMENT='History of a vehicles V5C data';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `visit`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned NOT NULL,
  `visit_date` date NOT NULL,
  `visit_reason_id` smallint(5) unsigned NOT NULL,
  `visit_outcome_id` smallint(5) unsigned NOT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`),
  KEY `visit_reason_id` (`visit_reason_id`),
  KEY `visit_outcome_id` (`visit_outcome_id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_esvr_site_id` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
  CONSTRAINT `fk_esvr_visit_outcome_id` FOREIGN KEY (`visit_outcome_id`) REFERENCES `enforcement_visit_outcome_lookup` (`id`),
  CONSTRAINT `fk_esvr_visit_reason_id` FOREIGN KEY (`visit_reason_id`) REFERENCES `visit_reason_lookup` (`id`),
  CONSTRAINT `visit_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `visit_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Representation of a visit made by DVSA to a site.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `visit_reason_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visit_reason_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `reason` varchar(80) NOT NULL,
  `display_order` smallint(5) unsigned NOT NULL DEFAULT '1',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `visit_reason_lookup_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `visit_reason_lookup_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Normalisation of the reason for a DVSA visit to a site';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `weight_source_lookup`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weight_source_lookup` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL,
  `display_order` smallint(5) unsigned DEFAULT NULL,
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_weight_source_lookup_name` (`name`),
  UNIQUE KEY `uk_weight_source_lookup_code` (`code`),
  UNIQUE KEY `uk_weight_source_lookup_display_order` (`display_order`),
  KEY `ix_weight_source_lookup_person_created_by` (`created_by`),
  KEY `ix_weight_source_lookup_person_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_weight_source_lookup_person_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_weight_source_lookup_person_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COMMENT='Normalisation of types of weight used for brake test calculations';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wheelplan_type`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wheelplan_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `display_order` smallint(5) unsigned NOT NULL DEFAULT '1',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_wheelplan_type_code` (`code`),
  KEY `created_by` (`created_by`),
  KEY `last_updated_by` (`last_updated_by`),
  CONSTRAINT `wheelplan_type_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `wheelplan_type_ibfk_2` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='Will be removed - normalisation of unused data';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-04-30 15:15:41
