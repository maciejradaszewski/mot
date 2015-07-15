-- liquibase formatted sql

-- changeset peleodiase:20150625000000

ALTER TABLE `direct_debit` CHANGE `slots` `slots` int(10) unsigned NOT NULL;