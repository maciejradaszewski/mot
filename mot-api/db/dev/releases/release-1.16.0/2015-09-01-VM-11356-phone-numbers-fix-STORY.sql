-- VM-11356
-- 1. script updates phone to primary if there's only one phone per contact_detail
-- 2. script "turns off" (by making them not primary) duplicated phones created by update bug while editting site and organisation contact details, leaving only last by created_on date
SET @static_user = (SELECT `id`
                    FROM `person`
                    WHERE `user_reference` = 'Static Data' OR `username` = 'static data'
                    LIMIT 1);
-- update phone to primary if there's only one phone (other cases with missing primary are going to be solved by MDM-382)
UPDATE phone
SET is_primary = 1, last_updated_by = @static_user, last_updated_on = CURRENT_TIMESTAMP(6)
WHERE contact_detail_id IN (
  SELECT *
  FROM (
         SELECT contact_detail_id
         FROM phone
         GROUP BY contact_detail_id
         HAVING COUNT(*) = 1
       ) e
) AND is_primary = 0;

SELECT row_count() AS rows_updated;

-- make all the phones for contact_detail non primary, but the latest one
-- duplicated phones were created by flaw in site and organistation contact_details update
-- changing them makes them not being used in app anymore
UPDATE phone
SET is_primary = 0, last_updated_by = @static_user, last_updated_on = CURRENT_TIMESTAMP(6)
WHERE id NOT IN (
  SELECT id
  FROM (
         SELECT p.id
         -- select latest phone by created_on date for each contact_detail
         FROM (
                SELECT
                  contact_detail_id,
                  MAX(created_on) AS created
                FROM phone
                WHERE is_primary = TRUE
                GROUP BY contact_detail_id
              ) AS x
           INNER JOIN phone AS p
             ON p.contact_detail_id = x.contact_detail_id AND p.created_on = x.created
       ) e
) AND is_primary = 1;

SELECT row_count() AS rows_updated;