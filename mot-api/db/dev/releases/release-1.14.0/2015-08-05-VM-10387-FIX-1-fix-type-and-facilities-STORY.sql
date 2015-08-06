SET @createdBy = (SELECT id FROM person WHERE user_reference = 'Static Data' OR username = 'static data');

--  Alistar (PO) said need to change it (same to MOT1).
UPDATE site_type
SET
    name='Training Centre', --  before Contracted Training Centre
    last_updated_by=@createdBy,
    version=version+1
WHERE
    code = 'CTC';