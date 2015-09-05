SET @createdBy = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

SET @id = (SELECT MAX(`id`) FROM `site`);
SET @type = (SELECT `id` FROM `site_type` WHERE `code` = 'VTS');
SET @version = 1;

INSERT INTO `site` (
    `name`, `site_number`, `default_brake_test_class_1_and_2_id`, `default_service_brake_test_class_3_and_above_id`,
    `default_parking_brake_test_class_3_and_above_id`, `dual_language`, `scottish_bank_holiday`, `type_id`,
    `created_by`, `last_updated_by`,  `version`
)
VALUES
    ('New Garage Test 1', 'S900001', 5, 5, 5, false, false, @type, @createdBy, @createdBy, @version),
    ('New Garage Test 2', 'S900002', 5, 5, 5, false, false, @type, @createdBy, @createdBy, @version),
    ('New Garage Test 3', 'S900003', 5, 5, 5, false, false, @type, @createdBy, @createdBy, @version),
    ('New Garage Test 4', 'S900004', 5, 5, 5, false, false, @type, @createdBy, @createdBy, @version),
    ('New Garage Test 5', 'S900005', 5, 5, 5, false, false, @type, @createdBy, @createdBy, @version);

SET @contactType = (SELECT `id` FROM `site_contact_type` WHERE `code` = 'BUS');
SET @contactDetail = (SELECT `id` FROM `contact_detail` WHERE `id` = 1);

SET @site1 = (SELECT `id` FROM `site` WHERE `site_number` = 'S900001');
SET @site2 = (SELECT `id` FROM `site` WHERE `site_number` = 'S900002');
SET @site3 = (SELECT `id` FROM `site` WHERE `site_number` = 'S900003');
SET @site4 = (SELECT `id` FROM `site` WHERE `site_number` = 'S900004');
SET @site5 = (SELECT `id` FROM `site` WHERE `site_number` = 'S900005');


INSERT INTO `site_contact_detail_map` (`site_contact_type_id`, `site_id`, `contact_detail_id`, `created_by`, `last_updated_by`)
VALUES
    (@contactType, @site1, @contactDetail, @createdBy, @createdBy),
    (@contactType, @site2, @contactDetail, @createdBy, @createdBy),
    (@contactType, @site3, @contactDetail, @createdBy, @createdBy),
    (@contactType, @site4, @contactDetail, @createdBy, @createdBy),
    (@contactType, @site5, @contactDetail, @createdBy, @createdBy);

INSERT INTO `site_contact_detail_map` (`site_contact_type_id`, `site_id`, `contact_detail_id`, `created_by`, `last_updated_by`)
VALUES
    (@contactType, @site1, @contactDetail, @createdBy, @createdBy),
    (@contactType, @site2, @contactDetail, @createdBy, @createdBy),
    (@contactType, @site3, @contactDetail, @createdBy, @createdBy),
    (@contactType, @site4, @contactDetail, @createdBy, @createdBy),
    (@contactType, @site5, @contactDetail, @createdBy, @createdBy);

