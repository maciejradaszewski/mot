START TRANSACTION;
SET @previous_deploy_date = '2016-03-24 00:00:00';

UPDATE mot_test mt
  INNER JOIN `jasper_document` jd ON jd.id = mt.`document_id`
  SET mt.`document_id` = NULL
  WHERE jd.`created_on` >= @previous_deploy_date OR jd.`last_updated_on` >= @previous_deploy_date;

DELETE FROM `jasper_document` WHERE `created_on` >= @previous_deploy_date OR `last_updated_on` >= @previous_deploy_date;

COMMIT;