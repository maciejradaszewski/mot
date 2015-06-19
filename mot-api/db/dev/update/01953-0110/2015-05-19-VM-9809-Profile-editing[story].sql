-- VM-9809
-- Profile Editing

INSERT INTO permission (`code`, `name`, created_by)
VALUE
('PROFILE-EDIT-OTHERS-PERSONAL-DETAILS' ,'Updating Personal Details', 2),
('PROFILE-EDIT-OWN-CONTACT-DETAILS', 'Editing contact details', 2),
('PROFILE-EDIT-OTHERS-EMAIL-ADDRESS', 'Editing email address', 2);

INSERT INTO role_permission_map (role_id, permission_id, created_by) VALUES (
  (SELECT id FROM role WHERE `code` = 'USER'),
  (SELECT id FROM permission WHERE `code` = 'PROFILE-EDIT-OWN-CONTACT-DETAILS'),
  2
),
(
  (SELECT id FROM role WHERE `code` = 'DVSA-AREA-OFFICE-1'),
  (SELECT id FROM permission WHERE `code` = 'PROFILE-EDIT-OTHERS-PERSONAL-DETAILS'),
  2
),
(
  (SELECT id FROM role WHERE `code` = 'DVSA-AREA-OFFICE-1'),
  (SELECT id FROM permission WHERE `code` = 'PROFILE-EDIT-OTHERS-EMAIL-ADDRESS'),
  2
),
(
  (SELECT id FROM role WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE'),
  (SELECT id FROM permission WHERE `code` = 'PROFILE-EDIT-OTHERS-EMAIL-ADDRESS'),
  2
)
;