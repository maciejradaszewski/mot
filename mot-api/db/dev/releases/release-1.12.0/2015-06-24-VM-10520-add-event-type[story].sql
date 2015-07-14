SET @createdBy = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');
SET @displayOrder = (SELECT MAX(`display_order`) FROM `event_type_lookup`);

INSERT INTO `event_type_lookup`
    (`code`, `name`, `display_order`, `start_date`, `created_by`)
    VALUES
    ('GATQ', 'Group A Tester Qualification', @displayOrder + 1, '1900-01-01', @createdBy),
    ('GBTQ', 'Group B Tester Qualification', @displayOrder + 2, '1900-01-01', @createdBy);