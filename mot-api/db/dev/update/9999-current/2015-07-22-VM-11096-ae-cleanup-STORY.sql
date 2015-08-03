
-- This is for production, discussed with Ana Rotstein and approved by Danny Charles.
-- May not produce any rows to delete in lower environments, as these were created in
-- Prod for business testing after go-live.


-- Delete AEs that are no longer required
SET @org1 = (select id from organisation where name = 'Test new AE 1');
SET @org2 = (select id from organisation where name = 'AE 2');
SET @org3 = (select id from organisation where name = 'Really - this is still here?');
set @org4 = (select id from organisation where name = 'OTHERS MAILING LIST');

-- These two AEs get an ae_ref from Danny Charles, as they have some Site links, and
-- a larger data cleanup exercise is needed.
set @dvla = (select id from organisation where name = 'Driver Vehicle Licensing Authority');
set @atos = (select id from organisation where name = 'Atos IT Solutions & Services');

-- Update AEs that do not have an ae_ref
UPDATE auth_for_ae SET ae_ref = 'A000000' WHERE organisation_id = @dvla;
UPDATE auth_for_ae SET ae_ref = 'A999999' WHERE organisation_id = @atos;

DELETE from auth_for_ae where organisation_id in (@org1, @org2, @org3, @org4);
DELETE from organisation where id in (@org1, @org2, @org3, @org4);

-- Make ae_ref not null
ALTER TABLE auth_for_ae MODIFY ae_ref varchar(12) NOT NULL;
