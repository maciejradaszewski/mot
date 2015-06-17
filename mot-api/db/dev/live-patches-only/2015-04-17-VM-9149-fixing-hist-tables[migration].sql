-- VM-9149
-- This script cannot be executed locally as it modifies _hist table that doesn't exist when all alter scripts are run.
-- algorithm of creating db on local machines:
-- 1. drop database
-- 2. create db schema with mot-api/db/dev/schema/create_dev_db_schema.sql
-- 3. populate tables
-- 4. run all updates mot-api/db/dev/update/9999-current
-- 5. create _hist tables with mot-api/db/dev/schema/create_hist_tables.sql

-- on "production-like" environment we do not drop and recreate db. We must update existsing schema.
-- this script must be run with of mot-api/db/dev/update/9999-current/2015-04-17-VM-9149-ct_related_mark-and-seating_capacity[migration].sql
-- process for patching live environment is still not defined.

ALTER TABLE `dvla_vehicle_hist`
  CHANGE COLUMN `ct_related_mark` `ct_related_mark` VARCHAR(13) NULL DEFAULT NULL,
  CHANGE COLUMN `seating_capacity` `seating_capacity` SMALLINT UNSIGNED NULL DEFAULT NULL;
