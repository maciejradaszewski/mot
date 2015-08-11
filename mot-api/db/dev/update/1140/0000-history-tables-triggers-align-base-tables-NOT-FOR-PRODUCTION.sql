-- add columns used in new version of history triggers
ALTER TABLE `country_of_registration_lookup`
    ADD COLUMN `batch_number` int(10) unsigned NOT NULL DEFAULT '0'
        COMMENT 'ETL batch number: for use by ETL process only'
        AFTER `version`;

ALTER TABLE `non_working_day_country_lookup`
    ADD COLUMN `batch_number` int(10) unsigned NOT NULL DEFAULT '0'
        COMMENT 'ETL batch number: for use by ETL process only'
        AFTER `version`,
    ADD COLUMN `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL
        COMMENT 'Used to hold MOT1 associated primary key for migration purposes'
        AFTER `country_lookup_id`;

ALTER TABLE `non_working_day_lookup`
    ADD COLUMN `batch_number` int(10) unsigned NOT NULL DEFAULT '0'
        COMMENT 'ETL batch number: for use by ETL process only'
        AFTER `version`,
    ADD COLUMN `mot1_legacy_id` varchar(80) CHARACTER SET latin1 DEFAULT NULL
        COMMENT 'Used to hold MOT1 associated primary key for migration purposes'
        AFTER `day`;
