# removing AEP security questions - same reason as above
DELETE `answer` FROM `auth_for_ae_person_as_principal_map` `map`
  JOIN `person` `p` ON `p`.`id` = `map`.`person_id`
  JOIN `person_security_question_map` `answer` ON `p`.`id` = `answer`.`person_id`
WHERE (`p`.`username` IS NULL);

# removing AEP system roles
DELETE `person_role` FROM `auth_for_ae_person_as_principal_map` `map`
  JOIN `person` `p` ON `p`.`id` = `map`.`person_id`
  JOIN `person_system_role_map` `person_role` ON `p`.`id` = `person_role`.`person_id`
WHERE (`p`.`username` IS NULL);
