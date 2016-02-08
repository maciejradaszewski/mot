SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

UPDATE permission
SET `code` = 'AE-UPDATE-REGISTERED-OFFICE-ADDRESS',
  name = 'User can update AE registered office address', `last_updated_by` = @app_user_id, `last_updated_on` = CURRENT_TIMESTAMP(6)
WHERE `code` = 'AE-UPDATE-BUSINESS-ADDRESS';

UPDATE permission
SET `code` = 'AE-UPDATE-REGISTERED-OFFICE-EMAIL',
  name = 'User can update AE registered office email', `last_updated_by` = @app_user_id, `last_updated_on` = CURRENT_TIMESTAMP(6)
WHERE `code` = 'AE-UPDATE-BUSINESS-EMAIL';

UPDATE permission
SET `code` = 'AE-UPDATE-REGISTERED-OFFICE-PHONE',
  name = 'User can update AE registered office telephone' , `last_updated_by` = @app_user_id, `last_updated_on` = CURRENT_TIMESTAMP(6)
WHERE `code` = 'AE-UPDATE-BUSINESS-PHONE';

UPDATE permission
SET name = 'User can update AE correspondence address', `last_updated_by` = @app_user_id, `last_updated_on` = CURRENT_TIMESTAMP(6)
WHERE `code` = 'AE-UPDATE-CORRESPONDENCE-ADDRESS';

UPDATE permission
SET name = 'User can update AE correspondence email', `last_updated_by` = @app_user_id, `last_updated_on` = CURRENT_TIMESTAMP(6)
WHERE `code` = 'AE-UPDATE-CORRESPONDENCE-EMAIL';

UPDATE permission
SET name = 'User can update AE correspondence telephone', `last_updated_by` = @app_user_id, `last_updated_on` = CURRENT_TIMESTAMP(6)
WHERE `code` = 'AE-UPDATE-CORRESPONDENCE-PHONE';
