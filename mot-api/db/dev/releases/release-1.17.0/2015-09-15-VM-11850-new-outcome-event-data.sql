-- VM-11850 Create manual event
-- Adding new events, outcomes and mapping between event type, category and outcome
SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' || `user_reference` = 'Static Data');

INSERT INTO `event_type_lookup` (`code`, `name`, `start_date`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    ('INTM', 'Intelligence marker', DATE(NOW()), @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    ('VTSCO', 'VTS complaint', DATE(NOW()), @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    ('NTTA',    'Tester training assessment', DATE(NOW()), @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    ('NTTDA', 'NTT Directed Retraining: classes 1,2', DATE(NOW()), @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    ('NTTRA', 'NTT Refresher: classes 1,2', DATE(NOW()), @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

INSERT INTO `event_outcome_lookup` (`code`, `name`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    ('NOOUT', 'No outcome', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    ('ATT', 'Attended' , @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    ('VTSAA', 'VTS application approved', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    ('VTSAC', 'VTS application status changes', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    ('VTSAS', 'VTS application submitted', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    ('VTSCD', 'VTS Authorisation Confirmation of details', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    ('SUSP', 'Suspended', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    ('FTA', 'Failed to attend', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    ('DTN', 'Demo test needed', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    ('DAR', 'Disciplinary Action report (DAR)', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    ('ITR', 'Initial training required', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    ('NFA', 'No further action', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    ('AWL', 'Advisory Warning Letter ', @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @ae = (SELECT `id` FROM `event_category_lookup` WHERE `code` = 'AE');
SET @site = (SELECT `id` FROM `event_category_lookup` WHERE `code` = 'VTS');
SET @person = (SELECT `id` FROM `event_category_lookup` WHERE `code` = 'NT');

-- doing these in batches for variable re-use
SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'CONVC');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NFA'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AWL'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'DAR'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'REPUT');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NFA'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AWL'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'DAR'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'INC');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NFA'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AWL'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'DAR'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'RFW');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NFA'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AWL'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'DAR'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'VT19');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NFA'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AWL'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'DAR'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'VT19I');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NFA'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AWL'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'DAR'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'VT55');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NFA'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AWL'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'DAR'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'VT55R');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NFA'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AWL'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'DAR'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'VT6');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NTS'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NTMS'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NTU'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'DTN'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'ITR'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'SUSP'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'SI');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NFA'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AWL'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'DAR'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'APPL');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NAPU'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NAPFW'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NAPRJ'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'INTM');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NOOUT'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'SA');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NFA'), @site, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'VT7');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'VTSAA'), @site, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'VTSAS'), @site, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'VTSAC'), @site, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'VTSCD'), @site, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'SA');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AWL'), @site, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'DAR'), @site, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'VTSCO');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NFA'), @site, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AWL'), @site, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'DAR'), @site, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AEFW'), @site, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AESTC'), @site, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AESCA'), @site, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'INTM');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NOOUT'), @site, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'AE');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'ATT'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'FTA'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'NTTMC');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'P'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'F'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'FTA'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'NTT2');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'P'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'F'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'FTA'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'NTTDA');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'P'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'F'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'FTA'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'NTTD');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'P'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'F'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'FTA'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'NTTRA');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'P'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'F'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'FTA'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'NTTR');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'P'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'F'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'FTA'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'NTTA');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'P'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'F'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'FTA'), @person, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'APPL');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NAPRJ'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NAPU'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NAPWD'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NAPFW'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NAPWC'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'CONVC');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NFA'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'DSV');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NFA'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AWL'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'DAR'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'INC');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NFA'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AWL'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'DAR'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'NOTFY');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NFA'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AWL'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'DAR'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'REPUT');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NFA'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AWL'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'DAR'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'SA');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NFA'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AWL'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'DAR'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'VT19');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NFA'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AWL'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'DAR'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'VT19I');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NFA'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AWL'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'DAR'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'VT55');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NFA'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AWL'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'DAR'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'VT55R');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NFA'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AWL'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'DAR'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'VTSCO');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NFA'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AWL'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'DAR'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AEFW'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AESTC'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6)),
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'AESCA'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));

SET @event_code = (SELECT `id` FROM `event_type_lookup` WHERE `code` = 'INTM');
INSERT INTO `event_type_outcome_category_map` (`event_type_id`, `event_outcome_id`, `event_category_id`, `created_by`, `created_on`, `last_updated_by`, `last_updated_on`) VALUES
    (@event_code, (SELECT `id` FROM `event_outcome_lookup` WHERE `code` = 'NOOUT'), @ae, @app_user_id, CURRENT_TIMESTAMP(6), @app_user_id, CURRENT_TIMESTAMP(6));