INSERT INTO `person_system_role_map` (person_id, person_system_role_id, created_by, status_id) VALUES
(
 (SELECT `id` from `person` where `username` = 'cron-job'),
 (SELECT `id` from `person_system_role` where `name` = 'CRON'),
 (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data' LIMIT 1),
 (SELECT `id` from `business_role_status` where `code` = 'AC')
) ON DUPLICATE KEY UPDATE `last_updated_by` = VALUES(created_by);