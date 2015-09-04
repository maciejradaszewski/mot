-- original issue was reported in VM-11358, but it was decided to be handled in VM-11356 as is the same issue as with phones
-- 1. script updates email to primary if there's only one email per contact_detail
-- 2. script "turns off" (by making them not primary) duplicated emails created by update bug while editting site and organisation contact details, leaving only last by created_on date
SET @static_user = (SELECT `id`
                    FROM `person`
                    WHERE `user_reference` = 'Static Data' OR `username` = 'static data'
                    LIMIT 1);
-- update email to primary if there's only one email (other cases with missing primary are going to be solved by MDM-383)
UPDATE email
SET is_primary = 1, last_updated_by = @static_user, last_updated_on = CURRENT_TIMESTAMP(6)
WHERE contact_detail_id IN (
  SELECT *
  FROM (
         SELECT contact_detail_id
         FROM email
         GROUP BY contact_detail_id
         HAVING COUNT(*) = 1
       ) e
) AND is_primary = 0;

SELECT row_count() AS rows_updated;

-- make all the emails for contact_detail non primary, but the latest one
-- duplicated emails were created by flaw in site and organistation contact_details update
-- changing them makes them not being used in app anymore
UPDATE email
SET is_primary = 0, last_updated_by = @static_user, last_updated_on = CURRENT_TIMESTAMP(6)
WHERE id NOT IN (
  SELECT id
  FROM (
         SELECT p.id
         -- select latest email by created_on date for each contact_detail
         FROM (
                SELECT
                  contact_detail_id,
                  MAX(created_on) AS created
                FROM email
                WHERE is_primary = TRUE
                GROUP BY contact_detail_id
              ) AS x
           INNER JOIN email AS p
             ON p.contact_detail_id = x.contact_detail_id AND p.created_on = x.created
       ) e
) AND is_primary = 1;

SELECT row_count() AS rows_updated;