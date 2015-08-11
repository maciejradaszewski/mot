-- This is for production, discussed with Ana Rotstein and approved by Danny Charles.
-- May not produce any rows to delete in lower environments, as these were created in
-- Prod for business testing after go-live.

set @updatedBy = (select id from person where username = 'static data' or user_reference = 'static data');

-- These three AEs get an ae_ref from Danny Charles, as they have some Site links, and
-- a larger data cleanup exercise is needed.
set @dvla = (select id from organisation where name = 'Driver Vehicle Licensing Authority');
set @atos = (select id from organisation where name = 'Atos IT Solutions & Services');
set @mail = (select id from organisation where name = 'OTHERS MAILING LIST');

-- Update AEs that do not have an ae_ref
UPDATE auth_for_ae SET ae_ref = 'A000000', last_updated_by = @updatedBy, version = version+1 WHERE organisation_id = @dvla;
UPDATE auth_for_ae SET ae_ref = 'A999999', last_updated_by = @updatedBy, version = version+1 WHERE organisation_id = @atos;
UPDATE auth_for_ae SET ae_ref = 'A888888', last_updated_by = @updatedBy, version = version+1 WHERE organisation_id = @mail;


-- Delete AEs that are no longer required
SET @org1 = (select id from organisation where name = 'Test new AE 1');
SET @org2 = (select id from organisation where name = 'AE 2');
SET @org3 = (select id from organisation where name = 'Really - this is still here?');

SET @contactdetailorg1 = (select contact_detail_id from organisation_contact_detail_map where organisation_id = @org1);
SET @contactdetailorg2 = (select contact_detail_id from organisation_contact_detail_map where organisation_id = @org2);
SET @contactdetailorg3 = (select contact_detail_id from organisation_contact_detail_map where organisation_id = @org3);

SET @addressorg1 = (select address_id from contact_detail where id = @contactdetailorg1);
SET @addressorg2 = (select address_id from contact_detail where id = @contactdetailorg2);
SET @addressorg3 = (select address_id from contact_detail where id = @contactdetailorg3);


DELETE from email where contact_detail_id in (@contactdetailorg1, @contactdetailorg2, @contactdetailorg3); -- 3 rows

DELETE from phone where contact_detail_id in (@contactdetailorg1, @contactdetailorg2, @contactdetailorg3); -- 4 rows

DELETE from organisation_contact_detail_map where organisation_id in (@org1, @org2, @org3); -- 3 rows

DELETE from contact_detail where id in (@contactdetailorg1, @contactdetailorg2, @contactdetailorg3); -- 3 rows

DELETE from address where id in (@addressorg1, @addressorg2, @addressorg3); -- 3 rows

DELETE from auth_for_ae where organisation_id in (@org1, @org2, @org3); -- 3 rows

DELETE from organisation where id in (@org1, @org2, @org3); -- 3 rows

-- Make ae_ref not null
ALTER TABLE auth_for_ae MODIFY ae_ref varchar(12) NOT NULL;

