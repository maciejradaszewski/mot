INSERT INTO `assembly_type` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number`) VALUES (1, 'Area', 'AREA', NULL, 2, CURRENT_TIMESTAMP(6), NULL, NULL, 1, 1);
INSERT INTO `assembly_type` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number`) VALUES (2, 'MOT Enforcement Patch', 'MEP', NULL, 2, CURRENT_TIMESTAMP(6), NULL, NULL, 1, 1);
INSERT INTO `assembly_type` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number`) VALUES (3, 'Area Office', 'AO', NULL, 2, CURRENT_TIMESTAMP(6), NULL, NULL, 1, 1);

-- changed ID from 0 to 1 due to MySQL auto-incrementing the zero when supplied unless sql_mode is set differently
-- so without relying on a global setting of NO_AUTO_VALUE_ON_ZERO, seeing as it's test data, let's just use ID 1
INSERT INTO `assembly` (`id`, `name`, `code`, `assembly_type_id`, `parent_assembly_id`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number`) VALUES (1, 'Unknown', 'UNKN', NULL, NULL, NULL, 1, CURRENT_TIMESTAMP(6), NULL, NULL, 1, 1);


INSERT INTO `assembly_role_type` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number`) VALUES (1, 'Area Office for', 'AOF', NULL, 2, CURRENT_TIMESTAMP(6), NULL, NULL, 1, 1);
INSERT INTO `assembly_role_type` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number`) VALUES (2, 'Member of', 'MO', NULL, 2, CURRENT_TIMESTAMP(6), NULL, NULL, 1, 1);
INSERT INTO `assembly_role_type` (`id`, `name`, `code`, `mot1_legacy_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`, `version`, `batch_number`) VALUES (3, 'Managed by', 'MB', NULL, 2, CURRENT_TIMESTAMP(6), NULL, NULL, 1, 1);
