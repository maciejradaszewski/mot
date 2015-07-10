-- DVLA export required, VM-10782
alter table vehicle add index ix_vehicle_created_on_dvla_vehicle_id (created_on, dvla_vehicle_id);

alter table certificate_replacement add index ix_certificate_replacement_created_on_is_vin_reg_changed (created_on, is_vin_registration_changed);
