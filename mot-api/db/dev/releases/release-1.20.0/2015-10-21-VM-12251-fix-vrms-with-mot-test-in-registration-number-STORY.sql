SET @app_user_id = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

SET @empty_vrm_reason_id = (SELECT `id` FROM empty_vrm_reason_lookup WHERE code = 'NOTR');

UPDATE mot_test m_t
  INNER JOIN vehicle v
    ON m_t.vehicle_id = v.id
SET
  m_t.registration = NULL,
  m_t.empty_vrm_reason_id = @empty_vrm_reason_id,
  m_t.last_updated_by = @app_user_id,
  m_t.last_updated_on = CURRENT_TIMESTAMP(6)
WHERE
  v.registration LIKE '?____________' AND m_t.registration LIKE '?____________';


UPDATE vehicle
SET
  registration = NULL,
  empty_vrm_reason_id = @empty_vrm_reason_id,
  last_updated_by = @app_user_id,
  last_updated_on = CURRENT_TIMESTAMP(6)

WHERE
  registration LIKE '?____________';