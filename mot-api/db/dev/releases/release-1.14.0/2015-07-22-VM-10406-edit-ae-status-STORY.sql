SET @createdBy = (SELECT id FROM person WHERE user_reference = 'Static Data' OR username = 'static data');
SET @displayOrder = (SELECT MAX(`display_order`) FROM `event_type_lookup`);

INSERT INTO `event_type_lookup`
(`code`, `name`, `display_order`, `start_date`, `end_date`, `mot1_legacy_id`, `last_updated_by`, `last_updated_on`, `created_by`)
VALUES
  ('UAE', 'Update AE', @displayOrder + 1, '1900-01-01', null, null, @createdBy, CURRENT_TIMESTAMP(6), @createdBy);

INSERT INTO `permission` (`code`, `name`, `last_updated_by`, `last_updated_on`, `created_by`)
VALUES
  ('AUTHORISED-EXAMINER-STATUS-UPDATE', 'Update status for an Authorised Examiner', @createdBy, CURRENT_TIMESTAMP(6), @createdBy);

SET @permission = (SELECT `id` FROM `permission` WHERE `code` = 'AUTHORISED-EXAMINER-STATUS-UPDATE');
SET @ao1_role = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-1');

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `last_updated_by`, `last_updated_on`, `created_by`)
VALUES
  (@ao1_role, @permission, @createdBy, CURRENT_TIMESTAMP(6), @createdBy);
