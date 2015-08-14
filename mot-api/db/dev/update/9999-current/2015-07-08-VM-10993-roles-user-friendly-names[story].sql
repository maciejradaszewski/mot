-- Setting/syncing user friendly role names
SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' || `user_reference` = 'Static Data');

-- role
UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Assessment'
WHERE
	`code`='ASSESSMENT';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Assessment line manager'
WHERE
	`code`='ASSESSMENT-LINE-MANAGER';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Authorised examiner'
WHERE
	`code`='AUTHORISED-EXAMINER';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Authorised examiner delegate'
WHERE
	`code`='AUTHORISED-EXAMINER-DELEGATE';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Authorised examiner designated manager'
WHERE
	`code`='AUTHORISED-EXAMINER-DESIGNATED-MANAGER';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Authorised examiner principal'
WHERE
	`code`='AUTHORISED-EXAMINER-PRINCIPAL';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Cron'
WHERE
	`code`='CRON';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Customer service operative'
WHERE
	`code`='CUSTOMER-SERVICE-CENTRE-OPERATIVE';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Customer service manager'
WHERE
	`code`='CUSTOMER-SERVICE-MANAGEMENT';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Demo-test'
WHERE
	`code`='DEMOTEST';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='DVLA operative'
WHERE
	`code`='DVLA-OPERATIVE';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Area office 1'
WHERE
	`code`='DVSA-AREA-OFFICE-1';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Area office 2'
WHERE
	`code`='DVSA-AREA-OFFICE-2';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Scheme manager'
WHERE
	`code`='DVSA-SCHEME-MANAGEMENT';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Scheme user'
WHERE
	`code`='DVSA-SCHEME-USER';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Finance '
WHERE
	`code`='FINANCE';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Guest'
WHERE
	`code`='GUEST';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Tester (GVTS)'
WHERE
	`code`='GVTS-TESTER';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Site admin'
WHERE
	`code`='SITE-ADMIN';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Site manager'
WHERE
	`code`='SITE-MANAGER';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Slot purchaser'
WHERE
	`code`='SLOT-PURCHASER';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Tester'
WHERE
	`code`='TESTER';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Tester (active)'
WHERE
	`code`='TESTER-ACTIVE';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Tester applicant (demo test required)'
WHERE
	`code`='TESTER-APPLICANT-DEMO-TEST-REQUIRED';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Tester applicant (initial training failed)'
WHERE
	`code`='TESTER-APPLICANT-INITIAL-TRAINING-FAILED';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Tester applicant (initial training required)'
WHERE
	`code`='TESTER-APPLICANT-INITIAL-TRAINING-REQUIR';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Tester (inactive)'
WHERE
	`code`='TESTER-INACTIVE';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='User'
WHERE
	`code`='USER';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Vehicle examiner'
WHERE
	`code`='VEHICLE-EXAMINER';

UPDATE `role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`=''
WHERE
	`code`='VM-10519-USER';

-- organisation_business_role
UPDATE `organisation_business_role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Authorised examiner delegate'
WHERE
	`code`='AED';

UPDATE `organisation_business_role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Authorised examiner designated manager'
WHERE
	`code`='AEDM';

UPDATE `organisation_business_role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Authorised examiner principal'
WHERE
	`code`='AEP';

UPDATE `organisation_business_role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Scheme manager'
WHERE
	`code`='DSM';

-- person_system_role
UPDATE `person_system_role` SET
  `full_name`='User',
  `version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6)
WHERE `name`='USER';

UPDATE `person_system_role` SET
  `full_name`='Vehicle examiner',
  `version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6)
WHERE `name`='VEHICLE-EXAMINER';

UPDATE `person_system_role` SET
  `full_name`='Scheme manager',
  `version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6)
WHERE `name`='DVSA-SCHEME-MANAGEMENT';

UPDATE `person_system_role` SET
  `full_name`='Scheme user',
  `version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6)
WHERE `name`='DVSA-SCHEME-USER';

UPDATE `person_system_role` SET
  `full_name`='Area office 1',
  `version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6)
WHERE `name`='DVSA-AREA-OFFICE-1';

UPDATE `person_system_role` SET
  `full_name`='Finance',
  `version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6)
WHERE `name`='FINANCE';

UPDATE `person_system_role` SET
  `full_name`='Customer service manager',
  `version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6)
WHERE `name`='CUSTOMER-SERVICE-MANAGER';

UPDATE `person_system_role` SET
  `full_name`='Customer service operative',
  `version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6)
WHERE `name`='CUSTOMER-SERVICE-CENTRE-OPERATIVE';

UPDATE `person_system_role` SET
  `full_name`='Cron',
  `version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6)
WHERE `name`='CRON';

UPDATE `person_system_role` SET
  `full_name`='DVLA operative',
  `version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6)
WHERE `name`='DVLA-OPERATIVE';

UPDATE `person_system_role` SET
  `full_name`='Area office 2',
  `version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6)
WHERE `name`='DVSA-AREA-OFFICE-2';

UPDATE `person_system_role` SET
  `full_name`='Tester (GVTS)',
  `version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6)
WHERE `name`='GVTS-TESTER';

-- site_business_role
UPDATE `site_business_role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Site admin'
WHERE
	`code`='Site admin';

UPDATE `site_business_role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Site manager'
WHERE
	`code`='Site manager';

UPDATE `site_business_role` SET
	`version` = (`version` + 1),
	`last_updated_by` = @app_user_id,
	`last_updated_on` = CURRENT_TIMESTAMP(6),
	`name`='Tester'
WHERE
	`code`='Tester';
