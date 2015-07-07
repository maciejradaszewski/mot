alter table mot_test
   add index ix_mot_test_site_id_started_date_completed_date (site_id, started_date desc, completed_date desc),
   add index ix_mot_test_person_id_started_date_completed_date (person_id, started_date desc, completed_date desc),
   add index ix_mot_test_site_id_status_id (site_id, status_id),
   add index ix_mot_test_person_id_status_id (person_id, status_id);