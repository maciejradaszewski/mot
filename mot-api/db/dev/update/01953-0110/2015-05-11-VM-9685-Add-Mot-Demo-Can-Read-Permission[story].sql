-- VM-9685
-- Adding a permission to allow other people to read the demo

INSERT INTO permission (`code`, `name`, created_by) VALUE ('MOT-DEMO-READ' ,'Reading Demo Test', 2);
INSERT INTO role_permission_map (role_id, permission_id, created_by) VALUES (
  (SELECT id FROM role WHERE `code` = 'VEHICLE-EXAMINER'),
  (SELECT id FROM permission WHERE `code` = 'MOT-DEMO-READ'),
  2
);

