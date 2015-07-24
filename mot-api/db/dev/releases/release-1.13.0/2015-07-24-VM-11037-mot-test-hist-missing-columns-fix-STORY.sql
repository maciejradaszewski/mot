UPDATE mot_test_hist hist,
  (SELECT
     id,
     empty_vin_reason_id,
     empty_vrm_reason_id,
     make_name,
     model_name,
     model_detail_name
   FROM mot_test
   WHERE completed_date > '2015-06-12'   -- 23,738 on 19/7
         AND (empty_vin_reason_id IS NOT NULL OR empty_vrm_reason_id IS NOT NULL
              OR make_name IS NOT NULL OR model_name IS NOT NULL OR model_detail_name IS NOT NULL) -- 2,417
  ) base
SET hist.empty_vin_reason_id = base.empty_vin_reason_id,
  hist.empty_vrm_reason_id   = base.empty_vrm_reason_id,
  hist.make_name             = base.make_name,
  hist.model_name            = base.model_name,
  hist.model_detail_name     = base.model_detail_name
WHERE hist.id = base.id AND hist.hist_transaction_type = 'U';