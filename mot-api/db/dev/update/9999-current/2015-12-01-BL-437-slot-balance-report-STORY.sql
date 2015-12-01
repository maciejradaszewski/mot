ALTER TABLE `site_hist`
  ADD INDEX `ix_site_hist_organisation_id_hist_timestamp` (`organisation_id`, `hist_timestamp`);

