/*
VM-11342, VM-10400
DVLA Imported Vehicle containing a body type not recognised by MOT
*/

SET @static_user = (SELECT `id` FROM `person` WHERE `user_reference` = "Static Data" OR `username` = "static data");
SET @display_order = (SELECT MAX(`display_order`) FROM `body_type`);

INSERT INTO `body_type` (`name`, `code`, `display_order`, `created_by`, `last_updated_by`, `last_updated_on`)
VALUES
	('Not provided', '08', @display_order + 1, @static_user, @static_user, CURRENT_TIMESTAMP(6)),
	('Not provided', '15', @display_order + 2, @static_user, @static_user, CURRENT_TIMESTAMP(6)),
	('Not provided', '16', @display_order + 3, @static_user, @static_user, CURRENT_TIMESTAMP(6)),
	('Not provided', '17', @display_order + 4, @static_user, @static_user, CURRENT_TIMESTAMP(6)),
	('Not provided', '20', @display_order + 5, @static_user, @static_user, CURRENT_TIMESTAMP(6)),
	('Not provided', '29', @display_order + 6, @static_user, @static_user, CURRENT_TIMESTAMP(6)),
	('Not provided', '35', @display_order + 7, @static_user, @static_user, CURRENT_TIMESTAMP(6)),
	('Not provided', '42', @display_order + 8, @static_user, @static_user, CURRENT_TIMESTAMP(6)),
	('Not provided', '50', @display_order + 9, @static_user, @static_user, CURRENT_TIMESTAMP(6)),
	('Not provided', '98', @display_order + 10, @static_user, @static_user, CURRENT_TIMESTAMP(6)),
	('Not provided', '99', @display_order + 11, @static_user, @static_user, CURRENT_TIMESTAMP(6)),
	('Not provided', '0', @display_order + 12, @static_user, @static_user, CURRENT_TIMESTAMP(6));
