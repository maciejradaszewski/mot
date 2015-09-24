-- SITE.site_status_id COLUMN SHOULD NOT BE NULL. BY DEFAULT ASSOCIATE 'APPROVED' STATUS TO ALL SITES
SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' || `user_reference` = 'Static Data');

INSERT INTO
`site_status_lookup` (`name`, `code`, `display_order`,`created_by`, `last_modified_by`, `last_modified_on`)
VALUES
('Approved', 'AV', '1', @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
('Applied', 'AP', '2', @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
('Retracted', 'RE', '3', @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
('Rejected', 'RJ', '4', @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
('Lapsed', 'LA', '5', @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6)),
('Extinct', 'EX', '6', @app_user_id, @app_user_id, CURRENT_TIMESTAMP(6));

-- SET APPROVED SITE STATUS ID
SET @approved_site_status = (SELECT `id` FROM `site_status_lookup` WHERE `code` = 'AV');

-- UPDATE ALL site RECORDS TO HAVE 'approved' STATUS
UPDATE `site` SET `site_status_id`=@approved_site_status WHERE `id`>=0;

-- MODIFY site.site_status_id TO NOT NULL, SET KEY
ALTER TABLE `site`
  MODIFY COLUMN `site_status_id` BIGINT UNSIGNED NOT NULL COMMENT 'Vehicle Testing Station Status',
  ADD KEY `ix_site_site_status_id` (`site_status_id`),
	ADD CONSTRAINT `fk_site_site_status_id_site_status_lookup_id`
	FOREIGN KEY (`site_status_id`) REFERENCES `site_status_lookup` (`id`);
