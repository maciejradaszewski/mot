ALTER TABLE site
    DROP INDEX `ix_site_site_number`,
    -- Add the index we want
    ADD UNIQUE INDEX `uk_site_site_number` (site_number),
    -- Add this one so the requirement that a FK has an index is met
    ADD INDEX `ix_site_organisation_id` (organisation_id),
    -- Drop the old one as not needed, as per the ticket
    DROP INDEX `bk_site`,
    -- non-ticket related goodies, generated in-passing, 'cos it needs done.
    -- these updates are to bring the table more in line with the database assurance criteria
    DROP INDEX `fk_site_first_test_person`, ADD INDEX `ix_site_first_test_carried_out_by` (first_test_carried_out_by),
    DROP INDEX `fk_site_first_login_person`, ADD INDEX `ix_site_first_login_by` (first_login_by),
    DROP INDEX `fk_site_first_live_test_person`, ADD INDEX `ix_site_first_live_test_carried_out_by` (first_live_test_carried_out_by),
    DROP INDEX `fk_site_type_id`, ADD INDEX `ix_site_type_id` (type_id),
    DROP INDEX `fk_site_assessment_id`, ADD INDEX `ix_site_last_site_assessment_id` (last_site_assessment_id),
    -- ok to here
    DROP INDEX `fk_site_mot1_vts_device_status_id`, ADD INDEX `ix_site_mot1_vts_device_status_id` (mot1_vts_device_status_id),
    DROP INDEX `fk_site_transition_status_id`, ADD INDEX `ix_site_transition_status_id` (transition_status_id),
    DROP INDEX `fk_site_default_brake_test_class_1_and_2_id`, ADD INDEX `ix_site_default_brake_test_class_1_and_2_id` (default_brake_test_class_1_and_2_id),
    DROP INDEX `fk_site_default_service_brake_test_class_3_and_above_id`, ADD INDEX `ix_site_default_service_brake_test_class_3_and_above_id` (default_service_brake_test_class_3_and_above_id),
    -- ok to here
    DROP INDEX `fk_site_default_parking_brake_test_class_3_and_above_id`, ADD INDEX `ix_site_default_parking_brake_test_class_3_and_above_id`(default_parking_brake_test_class_3_and_above_id),
    DROP INDEX `fk_site_non_working_day_country_lookup_id`, ADD INDEX `ix_site_non_working_day_country_lookup_id` (non_working_day_country_lookup_id),
    DROP INDEX `created_by`, ADD INDEX `ix_site_created_by` (created_by),
    DROP INDEX `last_updated_by`, ADD INDEX `ix_site_last_updated_by` (last_updated_by)
    -- ok to here
;

-- I would like to do this index rename, but when running behat tests it actually causes mysql to die.
# ALTER TABLE site
#     DROP INDEX `ft_site_number_name`, ADD FULLTEXT INDEX `ft_site_site_number_name` (`site_number`, `name`)
# ;


ALTER TABLE auth_for_ae
    ADD UNIQUE INDEX `uk_auth_for_ae_ae_ref` (ae_ref)
;
