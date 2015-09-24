# Change area offices 10, 13 and anything greater than 16 to extinct

# Get the id for static data user
SET @app_user_id = (SELECT `id` FROM `person` WHERE `username` = 'static data' || `user_reference` = 'Static Data');

# Get the id for Extinct site status
SET @site_status_extinct = (SELECT `id` FROM `site_status_lookup` WHERE name = 'Extinct');

# Get the idea of area office site type
SET @site_type_area_office = (SELECT `id` FROM `site_type` WHERE `code`='AO');

# Update sub area offices to extinct status
UPDATE site
SET site_status_id=@site_status_extinct, last_updated_by=@app_user_id
WHERE
  type_id=@site_type_area_office AND
  site_number NOT IN('01', '02', '03', '04', '05', '06', '07', '08', '09', '11', '12', '14', '15', '16')