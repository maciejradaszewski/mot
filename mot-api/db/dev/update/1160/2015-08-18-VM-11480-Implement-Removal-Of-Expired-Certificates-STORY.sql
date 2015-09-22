DROP PROCEDURE IF EXISTS `sp_housekeeping_mot_test_recent_certificate`; 

DELIMITER //
CREATE PROCEDURE `sp_housekeeping_mot_test_recent_certificate` (IN p_max_day INT, IN p_chunk_size INT)
	BEGIN
		DECLARE rows_touched INT;
		DECLARE cut_off_date  DATETIME;
		SET rows_touched=1;
		SET cut_off_date=DATE_ADD(NOW(), INTERVAL -p_max_day DAY);
		
		WHILE rows_touched > 0 DO
			DELETE FROM mot_test_recent_certificate WHERE generation_completed_on <  cut_off_date and status = 'COMPLETED' LIMIT p_chunk_size;
            SET rows_touched = (SELECT ROW_COUNT());
            COMMIT;
		END WHILE;
	END //