-- DVLA export required, VM-10871
alter table mot_test add index ix_mot_test_created_on_emergency_log_id (created_on, emergency_log_id);
