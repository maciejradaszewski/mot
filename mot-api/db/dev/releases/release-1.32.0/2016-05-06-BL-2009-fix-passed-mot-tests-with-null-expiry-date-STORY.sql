SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' OR `user_reference` = 'Static Data');

-- Those ID's were pulled from DB with this command
-- 	SELECT id  FROM `mot_test` m WHERE
-- 		m.`expiry_date` IS NULL
-- 		AND m.`last_updated_on` > '2016-03-01 00:00:00.000000'
-- 		AND m.`prs_mot_test_id` IS NOT NULL
-- 		AND m.`status_id` = 6
-- 		AND m.`mot_test_type_id` = 1;

CREATE TEMPORARY TABLE IF NOT EXISTS mot_tests_with_null_expiry_date AS
	SELECT m.id,mth.`expiry_date` as expiry_date FROM `mot_test` m
	INNER JOIN `mot_test_hist` mth ON m.id = mth.id
		AND mth.`expiry_date` IS NOT NULL
		AND m.`id` IN (
			273612544,
			681752530,
			780693048,
			790330743,
			792568607,
			793450005,
			794820989,
			797593415,
			799925799,
			801683699,
			813875603,
			814939885,
			823030993,
			823519429,
			824634491,
			827513663,
			827743661,
			827750187,
			828906615
		)
	GROUP BY m.id;

SELECT * FROM mot_tests_with_null_expiry_date;

UPDATE mot_test m
	INNER JOIN mot_tests_with_null_expiry_date mtnn ON
		mtnn.id = m.id
	SET
    m.expiry_date = mtnn.expiry_date,
    m.last_updated_by = @app_user_id,
    m.last_updated_on = CURRENT_TIMESTAMP(6)
  WHERE m.`prs_mot_test_id` IS NOT NULL
    AND m.`status_id` = 6
    AND m.`mot_test_type_id` = 1
    AND m.`expiry_date` IS NULL;


DROP TABLE IF EXISTS mot_tests_with_null_expiry_date;