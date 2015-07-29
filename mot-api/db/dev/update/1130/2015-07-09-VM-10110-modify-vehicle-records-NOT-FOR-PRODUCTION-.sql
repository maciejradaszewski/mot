--  To correctly calculate notional "grace period" the vehicle row MUST now have
--  values for manufacture and first registration columns
UPDATE vehicle SET
	manufacture_date = first_used_date,
	first_registration_date = first_used_date;
