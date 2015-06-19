-- VM-9822
-- permissions for CSCO to view AE list, search for vehicles and view MOT test history

-- ROLES
SET @customerOperative = (SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE');

-- PERMISSIONS
SET @aeList                        = (SELECT `id` FROM `permission` WHERE `code` = 'AUTHORISED-EXAMINER-LIST');
SET @motTestHistory                = (SELECT `id` FROM `permission` WHERE `code` = 'FULL-VEHICLE-MOT-TEST-HISTORY-VIEW');
SET @testerRead                    = (SELECT `id` FROM `permission` WHERE `code` = 'TESTER-READ');
SET @testerReadOthers              = (SELECT `id` FROM `permission` WHERE `code` = 'TESTER-READ-OTHERS');

SET @fullVehicleMotTestHistoryView = (SELECT `id` FROM `permission` WHERE `code` = 'FULL-VEHICLE-MOT-TEST-HISTORY-VIEW');

-- MAPPING
INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
-- internal roles
  (@customerOperative,  @aeList, 2),
  (@customerOperative,  @motTestHistory, 2),
  (@customerOperative,  @testerRead, 2),
  (@customerOperative,  @testerReadOthers, 2)
;

DELETE FROM `role_permission_map`
WHERE
  permission_id = @fullVehicleMotTestHistoryView AND
  role_id = @customerOperative;