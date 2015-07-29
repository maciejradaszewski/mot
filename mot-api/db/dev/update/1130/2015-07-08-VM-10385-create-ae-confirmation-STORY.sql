SET @createdBy = (SELECT id FROM person WHERE user_reference = 'Static Data' OR username = 'static data');
SET @displayOrder = (SELECT MAX(`display_order`) FROM `event_type_lookup`);

INSERT INTO `event_type_lookup` (`code`, `name`, `display_order`, `start_date`, `end_date`, `mot1_legacy_id`, `created_by`)
VALUES
    ('CAE', 'DVSA Administrator Create AE', @displayOrder + 1, '1900-01-01', null, null, @createdBy);
