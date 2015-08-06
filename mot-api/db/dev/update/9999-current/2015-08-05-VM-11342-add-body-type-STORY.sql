/*
VM-11342, VM-10400
DVLA Imported Vehicle containing a body type not recognised by MOT
*/

SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = "Static Data");
SET @display_order = (SELECT MAX(`display_order`) FROM `body_type`);

INSERT INTO `body_type` (`name`, `code`, `display_order`, `created_by`)
VALUES
	('Not provided', '08', @display_order + 1, @created_by),
	('Not provided', '15', @display_order + 2, @created_by),
	('Not provided', '16', @display_order + 3, @created_by),
	('Not provided', '17', @display_order + 4, @created_by),
	('Not provided', '20', @display_order + 5, @created_by),
	('Not provided', '29', @display_order + 6, @created_by),
	('Not provided', '35', @display_order + 7, @created_by),
	('Not provided', '42', @display_order + 8, @created_by),
	('Not provided', '50', @display_order + 9, @created_by),
	('Not provided', '98', @display_order + 10, @created_by),
	('Not provided', '99', @display_order + 11, @created_by);
