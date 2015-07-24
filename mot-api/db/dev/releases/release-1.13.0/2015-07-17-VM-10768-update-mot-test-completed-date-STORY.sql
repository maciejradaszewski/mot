UPDATE `mot_test`
SET `completed_date`=`started_date` + INTERVAL 5 MINUTE
WHERE `completed_date` IS NULL
AND `status_id` != 4;
