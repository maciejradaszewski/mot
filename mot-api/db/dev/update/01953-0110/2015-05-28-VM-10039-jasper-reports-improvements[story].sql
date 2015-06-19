-- VM-10039
-- We must change the way we store mot test "snapshot"
-- in database so that we can improve performance and decrease amount of memory used for each certificate.

ALTER TABLE `jasper_document`
  ADD COLUMN `document_content` text DEFAULT NULL COMMENT 'Column to store serialised data (json) used by jasper reports o generate documents. Max length 64k' AFTER `template_id`;

-- this will move all rows to _hist table. `jasper_document_variables_hist` will not be dropped.
-- We can always retrieve all data that has ever been populated in the system.
UPDATE `jasper_document_variables` SET `version`=`version`+1;

DROP TABLE `jasper_document_variables`;
