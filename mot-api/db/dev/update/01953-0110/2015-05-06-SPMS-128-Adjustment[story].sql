-- liquibase formatted sql

-- changeset peleodiase:20150427125800


CREATE TABLE `test_slot_transaction_amendment_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `title` varchar(75) NOT NULL DEFAULT '' COMMENT 'Name of the amendment type',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Whether the amendment type is active',
  `display_order` smallint(5) unsigned NOT NULL DEFAULT 1 COMMENT 'Order the amendment type appears in a list',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT 1,
  `batch_number` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'ETL batch number: for use by ETL process only',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_test_slot_transaction_amendment_type_code` (`code`),
  KEY `ix_test_slot_transaction_amendment_type_created_by` (`created_by`),
  KEY `ix_test_slot_transaction_amendment_type_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_test_slot_transaction_amendment_type_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_test_slot_transaction_amendment_type_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Normalisation of types of amendment. Not all amendment types are currently implemented';

CREATE TABLE `test_slot_transaction_amendment_reason` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) CHARACTER SET latin1 NOT NULL,
  `description` varchar(75) NOT NULL DEFAULT '' COMMENT 'text of the amendment reason',
  `display_order` smallint(5) unsigned NOT NULL DEFAULT 1 COMMENT 'Order the amendment reason appears in a list',
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_test_slot_transaction_amendment_reason_code` (`code`),
  KEY `ix_test_slot_transaction_amendment_reason_created_by` (`created_by`),
  KEY `ix_test_slot_transaction_amendment_reason_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_test_slot_transaction_amendment_reason_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_test_slot_transaction_amendment_reason_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Normalisation of reasons of amendment.';


CREATE TABLE `test_slot_transaction_amendment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` int(10) unsigned NOT NULL,
  `type_id` smallint(5) unsigned NOT NULL,
  `reason_id` smallint(5) unsigned NOT NULL,
  `slots` smallint(5) NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `created_on` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `last_updated_by` int(10) unsigned DEFAULT NULL,
  `last_updated_on` datetime(6) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `batch_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ETL batch number: for use by ETL process only',
  `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Used to hold MOT1 associated primary key for migration purposes',
  PRIMARY KEY (`id`),
  KEY `ix_test_slot_transaction_amendment_organisation_id` (`organisation_id`),
  KEY `ix_test_slot_transaction_amendment_type_id` (`type_id`),
  KEY `ix_test_slot_transaction_amendment_reason` (`reason_id`),
  KEY `ix_test_slot_transaction_created_by` (`created_by`),
  KEY `ix_test_slot_transaction_last_updated_by` (`last_updated_by`),
  CONSTRAINT `fk_test_slot_transaction_amendment_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_test_slot_transaction_amendment_last_updated_by` FOREIGN KEY (`last_updated_by`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_test_slot_transaction_amendment_organisation_id` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`),
  CONSTRAINT `fk_test_slot_transaction_amendment_type_id` FOREIGN KEY (`type_id`) REFERENCES `test_slot_transaction_amendment_type` (`id`),
  CONSTRAINT `fk_test_slot_transaction_amendment_reason_id` FOREIGN KEY (`reason_id`) REFERENCES `test_slot_transaction_amendment_reason` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='History of all transaction amendments e.g. refunds, adjustments';

INSERT INTO
  `test_slot_transaction_amendment_type` (`code`,`title`, `created_by`)
VALUES
  ('T700','Adjustment', 2);

INSERT INTO
  `test_slot_transaction_amendment_reason` (`code`,`description`, `created_by`)
VALUES
  ('R101','Refund', 2),
  ('R102','Manual error', 2),
  ('R103','Failed payment', 2),
  ('R104','Account closure', 2)
;

ALTER TABLE `organisation` CHANGE `slots_balance` `slots_balance` int(10) signed NOT NULL DEFAULT 0;

INSERT INTO
  permission (`code`, `name`, `created_by`)
VALUES
  ('SLOTS-ADJUSTMENT', '', 2);

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    (SELECT `id` FROM role WHERE `code` = 'FINANCE'),
    (SELECT `id` FROM permission WHERE `code` = 'SLOTS-ADJUSTMENT'),
    2
  )
    ON DUPLICATE KEY UPDATE `created_by` = VALUES(`created_by`)
  ;