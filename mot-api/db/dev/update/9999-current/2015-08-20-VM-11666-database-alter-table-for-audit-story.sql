-- ALTERING permission_to_assign_role_map_hist for KDD-069 compliance.  Below table is used for auditing.
ALTER TABLE permission_to_assign_role_map_hist
MODIFY `created_on` TIMESTAMP(6) NULL DEFAULT NULL,
MODIFY `last_modified_on` TIMESTAMP(6) NULL DEFAULT NULL;