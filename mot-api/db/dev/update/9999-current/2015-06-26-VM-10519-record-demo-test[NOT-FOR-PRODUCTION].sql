-- [NOT-FOR-PRODUCTION]
-- Demo test data

UPDATE `auth_for_testing_mot`
JOIN `person` on `person`.`id` = `auth_for_testing_mot`.`person_id`
SET `status_id` = 8
WHERE `auth_for_testing_mot`.`vehicle_class_id` in (3, 4, 5, 7)
AND `person`.`username` = 'vts-tester-2';

INSERT `auth_for_testing_mot` (`person_id`, `vehicle_class_id`, `status_id`, `created_by`)
VALUES
((SELECT `id` FROM `person` WHERE `username` = 'vts-tester-2'), 1, 9, 1),
((SELECT `id` FROM `person` WHERE `username` = 'vts-tester-2'), 2, 9, 1);

