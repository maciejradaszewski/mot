SET @createdBy = (SELECT id FROM person WHERE user_reference = 'Static Data' OR username = 'static data');

SET @displayOrder = (SELECT MAX(`display_order`)+1 FROM `event_type_lookup`);

INSERT INTO `event_type_lookup` (`code`, `name`, `display_order`, `start_date`, `end_date`, `mot1_legacy_id`, `created_by`, `last_updated_by`)
VALUES
    ('AEULS', 'Unlink AE/Site', @displayOrder, '1900-01-01', null, null, @createdBy, @createdBy);



INSERT INTO `notification_template` (`content`, `subject`, `created_by`, `last_updated_by`)
VALUES
    (
        'Site ${siteNr} ${siteName} has been unlinked from Authorised Examiner ${aeNr} ${aeName}',
        'Site Unlinked from AE',
        @createdBy,
        @createdBy
    );