SET @app_user_id = (SELECT id FROM person WHERE username = 'static data' OR user_reference = 'Static Data');

insert into `permission` (`name`,`code`,`is_restricted`,`created_by` )
	values (
		'Do not create mismatch on VRM and VIN Change',
		'CERTIFICATE-REPLACEMENT-NO-MISMATCH-VIN-VRN-CHANGE', 0, @app_user_id);

insert into `role_permission_map` (`role_id`, `permission_id`, `created_by`)
  select
   (select id from role where code = 'DVLA-OPERATIVE') as `role_id`,
   (select id from permission where code = 'CERTIFICATE-REPLACEMENT-NO-MISMATCH-VIN-VRN-CHANGE') as `permission_id`,
   (select id from person where id = @app_user_id) as `created_by`
  from dual;
