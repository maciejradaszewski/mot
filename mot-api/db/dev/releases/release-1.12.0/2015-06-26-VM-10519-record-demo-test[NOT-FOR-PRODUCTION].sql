-- [NOT-FOR-PRODUCTION]
-- Demo test data

SET @demoTestNeededStatusId = (SELECT `id` FROM `auth_for_testing_mot_status` WHERE `code` = 'DMTN'); # 'Demo Test Needed' status
SET @qualifiedStatusId = (SELECT `id` FROM `auth_for_testing_mot_status` WHERE `code` = 'QLFD'); # 'Qualified' status

SET @vehicleClass1Id = (SELECT `id` FROM `vehicle_class` WHERE `code` IN ('1'));
SET @vehicleClass2Id = (SELECT `id` FROM `vehicle_class` WHERE `code` IN ('2'));

UPDATE `auth_for_testing_mot`
  JOIN `person` ON `person`.`id` = `auth_for_testing_mot`.`person_id`
SET `status_id` = @demoTestNeededStatusId
WHERE `auth_for_testing_mot`.`vehicle_class_id` IN (SELECT `id` FROM `vehicle_class` WHERE `code` IN ('3', '4', '5', '7'))
      AND `person`.`username` = 'vts-tester-2';

INSERT `auth_for_testing_mot` (`person_id`, `vehicle_class_id`, `status_id`, `created_by`)
VALUES
  ((SELECT `id` FROM `person` WHERE `username` = 'vts-tester-2'), @vehicleClass1Id, @qualifiedStatusId, 0), # created_by = 0 doesn't matter. We don't care who added this row. 0 should be unknown
  ((SELECT `id` FROM `person` WHERE `username` = 'vts-tester-2'), @vehicleClass2Id, @qualifiedStatusId, 0); # created_by = 0 doesn't matter. We don't care who added this row. 0 should be unknown

