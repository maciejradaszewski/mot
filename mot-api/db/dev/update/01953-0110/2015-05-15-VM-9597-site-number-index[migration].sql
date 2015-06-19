-- VM-9597
-- Working on production the following query was hanging due to execution time.
-- Adding an index on site.site_number speeds this up

ALTER TABLE `site`
  ADD INDEX `ix_site_site_number` (`site_number` ASC);
