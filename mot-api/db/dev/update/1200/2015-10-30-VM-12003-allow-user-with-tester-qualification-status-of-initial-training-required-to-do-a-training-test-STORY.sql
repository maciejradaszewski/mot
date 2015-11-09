SET @role_id = (SELECT `id` FROM `role` WHERE `code` = 'TESTER-APPLICANT-INITIAL-TRAINING-REQUIR');
SET @auth_status = (SELECT `id` FROM `auth_for_testing_mot_status` WHERE `code` = 'ITRN');
SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

SET @vehicleClass1Id = (SELECT id FROM vehicle_class WHERE code IN ('1'));
SET @vehicleClass2Id = (SELECT id FROM vehicle_class WHERE code IN ('2'));
SET @vehicleClass3Id = (SELECT id FROM vehicle_class WHERE code IN ('3'));
SET @vehicleClass4Id = (SELECT id FROM vehicle_class WHERE code IN ('4'));
SET @vehicleClass5Id = (SELECT id FROM vehicle_class WHERE code IN ('5'));
SET @vehicleClass7Id = (SELECT id FROM vehicle_class WHERE code IN ('7'));

INSERT INTO `auth_for_testing_mot_role_map` (`vehicle_class_id`, `auth_status_id`, `role_id`, `mot1_legacy_id`, `created_by`)
VALUES
  (@vehicleClass1Id, @auth_status, @role_id, NULL, @created_by),
  (@vehicleClass2Id, @auth_status, @role_id, NULL, @created_by),
  (@vehicleClass3Id, @auth_status, @role_id, NULL, @created_by),
  (@vehicleClass4Id, @auth_status, @role_id, NULL, @created_by),
  (@vehicleClass5Id, @auth_status, @role_id, NULL, @created_by),
  (@vehicleClass7Id, @auth_status, @role_id, NULL, @created_by);