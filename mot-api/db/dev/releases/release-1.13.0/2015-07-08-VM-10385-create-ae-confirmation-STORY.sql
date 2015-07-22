SET @createdBy = (SELECT id FROM person WHERE user_reference = 'Static Data' OR username = 'static data');

INSERT INTO `event_type_lookup` (`code`, `name`, `display_order`, `start_date`, `end_date`, `mot1_legacy_id`, `created_by`)
VALUES
    ('CAE', 'DVSA Administrator Create AE', 1005, '1900-01-01', null, null, @createdBy);
