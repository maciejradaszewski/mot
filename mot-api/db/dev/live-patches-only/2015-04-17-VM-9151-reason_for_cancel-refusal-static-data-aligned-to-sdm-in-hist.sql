-- This script cannot be executed locally as it modifies _hist table that doesn't exist when all alter scripts are run.
-- algorithm of creating db on local machines:
-- 1. drop database
-- 2. create db schema with mot-api/db/dev/schema/create_dev_db_schema.sql
-- 3. populate tables
-- 4. run all updates mot-api/db/dev/update/9999-current
-- 5. create _hist tables with mot-api/db/dev/schema/create_hist_tables.sql

--
-- This must be run on deployed environment
--

UPDATE `mot_test_hist`
  SET
    `mot_test_reason_for_cancel_id`=(
        SELECT
            `mot1_legacy_id`
        FROM
            `mot_test_reason_for_cancel_lookup`
        WHERE
            `id`=`mot_test_hist`.`mot_test_reason_for_cancel_id`
    )
WHERE `mot_test_reason_for_cancel_id` IS NOT NULL;

UPDATE `mot_test_refusal_hist` SET `reason_for_refusal_id`=26 WHERE `reason_for_refusal_id`=12;