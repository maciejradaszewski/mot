-- We need to change the start of the sequence number here to 3 as we have some test data using the number below
UPDATE `ctrl_sequence` SET sequence_number = 3 WHERE code = 'SITENR';
