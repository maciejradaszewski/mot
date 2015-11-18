SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

INSERT INTO `permission` (`name`, `code`, `created_by`) VALUES
  ('Add/Edit Driving Licence', 'ADD-EDIT-DRIVING-LICENCE', @created_by);

SET @add_edit_driving_licence_id = (SELECT `id` FROM `permission` WHERE `code` = 'ADD-EDIT-DRIVING-LICENCE');

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1'),
    @add_edit_driving_licence_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2'),
    @add_edit_driving_licence_id,
    @created_by
  ),
  (
    (SELECT `id` FROM `role` WHERE `code` = 'VEHICLE-EXAMINER'),
    @add_edit_driving_licence_id,
    @created_by
  );