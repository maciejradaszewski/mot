SET @createdBy = (SELECT id FROM person WHERE user_reference = 'Static Data' OR username = 'static data');
SET @displayOrder = (SELECT MAX(`display_order`) FROM `event_type_lookup`);

INSERT INTO `event_type_lookup` (`code`, `name`, `display_order`, `start_date`, `end_date`, `mot1_legacy_id`, `created_by`)
VALUES
    ('CS', 'DVSA Administrator Create Site', @displayOrder + 1, '1900-01-01', null, null, @createdBy);


INSERT INTO ctrl_sequence VALUES (
    NULL,
    'Site Number',
    'SITENR',
    'S',
    6,   -- padding
    0,   -- sequence_number you want to start with minus 1
    1,   -- increment
    0,   -- min
    4294967295, -- max
    @createdBy,   -- created by
    CURRENT_TIMESTAMP(6),
    @createdBy,
    CURRENT_TIMESTAMP(6),
    1
);

ALTER TABLE site MODIFY site_number varchar(45) NOT NULL;
