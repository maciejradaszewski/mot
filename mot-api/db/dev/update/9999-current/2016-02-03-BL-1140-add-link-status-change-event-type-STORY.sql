SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

INSERT INTO
  `event_type_lookup` (`code`,`name`,`start_date`,`created_by`,`created_on`, `last_updated_by`, `last_updated_on`, `version`)
VALUES (
  'LSC',
  'Link Status Change',
  '2016-01-01',
  @app_user_id,
  CURRENT_TIMESTAMP (6),
  @app_user_id,
  CURRENT_TIMESTAMP(6),
  1
);