-- [NOT-FOR-PRODUCTION]
-- Prepend similar addresses with different digits to facilitate recognising duplicated records

UPDATE `mot`.`address` SET `address_line_1`='1 Straw Hut' WHERE `id`='3';
UPDATE `mot`.`address` SET `address_line_1`='2 Straw Hut' WHERE `id`='1000009';
UPDATE `mot`.`address` SET `address_line_1`='3 Straw Hut' WHERE `id`='1000010';
UPDATE `mot`.`address` SET `address_line_1`='4 Straw Hut' WHERE `id`='1000012';
UPDATE `mot`.`address` SET `address_line_1`='5 Straw Hut' WHERE `id`='1000013';
UPDATE `mot`.`address` SET `address_line_1`='6 Straw Hut' WHERE `id`='1000014';
UPDATE `mot`.`address` SET `address_line_1`='7 Straw Hut' WHERE `id`='1000015';
UPDATE `mot`.`address` SET `address_line_1`='8 Straw Hut' WHERE `id`='1000017';
UPDATE `mot`.`address` SET `address_line_1`='2 Station Road' WHERE `id`='1000008';
UPDATE `mot`.`address` SET `address_line_1`='3 Station Road' WHERE `id`='1000011';

-- Changing person "Bob Thomas Actor" address to differentiate with person "Philip Fry" both are associated with
-- "Example AE Inc." organisation to demonstrate we are not displaying the same address for everyone.
UPDATE `mot`.`person_contact_detail_map` SET `contact_id`='2' WHERE `id`='7';
UPDATE `mot`.`person_contact_detail_map` SET `contact_id`='2' WHERE `id`='119';
