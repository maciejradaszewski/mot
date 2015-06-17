SET @areaOffice2 = (SELECT `id` FROM `role` WHERE `code` = 'DVSA-AREA-OFFICE-2');
SET @permission = (SELECT `id` FROM `permission` WHERE `code` = 'DVSA-SITE-SEARCH');


INSERT INTO `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (@areaOffice2, @permission, 2);


ALTER TABLE site ADD FULLTEXT INDEX ft_site_number_name (site_number ASC, name ASC);
ALTER TABLE address ADD FULLTEXT INDEX ft_contact_town_postcode (town ASC, postcode ASC);
