SET @updatedBy = (SELECT id FROM person WHERE user_reference = 'Static Data' OR username = 'static data');

UPDATE `mot_test`
SET `last_updated_by` = @updatedBy, `completed_date`=`started_date` + INTERVAL 5 MINUTE
WHERE `completed_date` IS NULL
AND `status_id` != 4;
