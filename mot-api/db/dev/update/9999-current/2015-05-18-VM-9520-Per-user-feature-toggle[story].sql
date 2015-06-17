-- VM-9520 : Per User Feature Toggling

ALTER TABLE `permission`
ADD COLUMN `is_restricted` TINYINT NOT NULL DEFAULT 0 AFTER `code`;

UPDATE `permission` SET `is_restricted` = '1' WHERE `code` in (
    'CERTIFICATE-PRINT',
    'CERTIFICATE-REPLACEMENT',
    'CERTIFICATE-REPLACEMENT-FULL',
    'CERTIFICATE-REPLACEMENT-SPECIAL-FIELDS',
    'NOMINATE-AEDM',
    'NOMINATE-ROLE-AT-AE',
    'NOMINATE-ROLE-AT-SITE',
    'REMOVE-AEDM-FROM-AE',
    'REMOVE-POSITION-FROM-AE',
    'REMOVE-ROLE-AT-SITE',
    'REMOVE-SITE-MANAGER',
    'SKIP-NOMINATION-REQUEST',
    'SLOTS-PURCHASE',
    'TESTING-SCHEDULE-UPDATE',
    'VTS-UPDATE-BUSINESS-DETAILS',
    'VTS-UPDATE-CORRESPONDENCE-DETAILS',
    'VTS-UPDATE-NAME',
    'AUTHORISED-EXAMINER-UPDATE'
);

DELETE FROM `transition_status` WHERE `id`='2';
DELETE FROM `transition_status` WHERE `id`='3';
DELETE FROM `transition_status` WHERE `id`='4';
DELETE FROM `transition_status` WHERE `id`='5';
DELETE FROM `transition_status` WHERE `id`='7';
DELETE FROM `transition_status` WHERE `id`='8';
DELETE FROM `transition_status` WHERE `id`='9';
UPDATE `transition_status` SET `last_updated_on`=NULL, `batch_number`='1' WHERE `id`='0';
UPDATE `transition_status` SET `last_updated_on`=NULL, `batch_number`='1' WHERE `id`='1';
UPDATE `transition_status` SET `id`='2', `last_updated_on`=NULL, `batch_number`='1' WHERE `id`='6';
INSERT INTO `transition_status` (`id`, `name`, `code`, `created_by`, `created_on`, `version`, `batch_number`) VALUES ('3', 'One time password assigned', 'OTPA', '1', CURRENT_TIMESTAMP(6), '1', '1');
INSERT INTO `transition_status` (`id`, `name`, `code`, `created_by`, `created_on`, `version`, `batch_number`) VALUES ('4', 'Restricted', 'REST', '1', CURRENT_TIMESTAMP(6), '1', '1');
INSERT INTO `transition_status` (`id`, `name`, `code`, `created_by`, `created_on`, `version`, `batch_number`) VALUES ('5', 'Full functionality', 'FULL', '1', CURRENT_TIMESTAMP(6), '1', '1');
INSERT INTO `transition_status` (`id`, `name`, `code`, `created_by`, `created_on`, `version`, `batch_number`) VALUES ('6', 'Not to be transitioned', 'NOT', '1', CURRENT_TIMESTAMP(6), '1', '1');

ALTER TABLE `person`
ADD INDEX `fk_person_transition_status_idx` (`transition_status_id` ASC);
ALTER TABLE `person`
ADD CONSTRAINT `fk_person_transition_status`
FOREIGN KEY (`transition_status_id`)
REFERENCES `transition_status` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

UPDATE `person` SET `transition_status_id` = (SELECT `id` FROM `transition_status` WHERE `code` = 'FULL')
WHERE `transition_status_id` is null;

UPDATE `site` SET `transition_status_id` = (SELECT `id` FROM `transition_status` WHERE `code` = 'FULL')
WHERE `transition_status_id` is null;

UPDATE `organisation` SET `transition_status_id` = (SELECT `id` FROM `transition_status` WHERE `code` = 'FULL')
WHERE `transition_status_id` is null;
