/***********************
* Import anonymous data
************************/

-- select MAKE, count(*) from MAKE_MODEL group by 1 order by count(*) desc;

-- Import the users
INSERT INTO mot.user (username, password, display_name, display_role)
SELECT username, 't', CONCAT(FIRSTNAME,' ',SURNAME), 'Anonymous Data'
FROM mot_test.USERS WHERE username NOT IN (SELECT username from mot.user);

-- Make all imported users nominated testers
INSERT INTO nominated_tester (active, username)
SELECT 1, username
FROM mot.user WHERE username NOT IN (SELECT username from nominated_tester);

-- Import all the vehicles
INSERT INTO mot.vehicle (id, registration, vin, vehicle_class, make, model, year, 
first_used_date, colour, fuel_type)
SELECT vv.VEHICLEID+10000 VEHID, REGISTRATION, VIN, TESTCLASSID, MIN(MAKE), MIN(MODEL), YEAR(FIRSTUSEDATE),
FIRSTUSEDATE, COLOUR, FUELTYPE
FROM mot_test.TESTRESULT tr
JOIN mot_test.VEHICLE_VTS vv 
ON tr.VEHICLEID = vv.VEHICLEID
GROUP BY VEHID;

-- TODO: fix ae schema
-- Convert users to to authorised examiners
INSERT INTO mot.authorised_examiner (username, slots, slots_warning)
SELECT MIN(USERNAME), 1000, 25
FROM mot_test.MOT_VTS_SITE s
JOIN mot_test.VTS_USER u ON s.SITE_NUMBER=u.SITE_NUMBER
GROUP BY s.SITE_NUMBER;

-- Create all the VTS data
INSERT INTO mot.vehicle_testing_station (authorised_examiner_id, name, address, site_number)
SELECT ae.id, TRADING_NAME, TRIM(CONCAT(ADDRESS_LINE_1,',',ADDRESS_LINE_2,',',ADDRESS_LINE_3,',',ADDRESS_LINE_4,',',POSTCODE)), s.SITE_NUMBER
FROM mot_test.MOT_VTS_SITE s
JOIN mot_test.VTS_USER u ON s.SITE_NUMBER=u.SITE_NUMBER
JOIN mot.authorised_examiner ae ON u.USERNAME = ae.username;

-- Create all the tests
INSERT INTO mot.mot_test (nominated_tester, vehicle_id, vehicle_testing_station_id, 
primary_colour, secondary_colour, has_registration, odometer_value, odometer_unit,test_type,
started_date, completed_date, issued_date, expiry_date, status)
SELECT vv.NOMINATED_TESTER, vv.VEHICLEID+10000, vts.id, 
tr.COLOUR, tr.TESTID, 1, tr.TESTMILEAGE, 'mi', CASE tr.TESTTYPE WHEN 'N' THEN 'NT' WHEN 'F' THEN 'NT' WHEN 'PL' THEN 'PL' WHEN 'PM' THEN 'PV' WHEN 'PR' THEN 'PV' WHEN 'RF' THEN NULL ELSE NULL END,
tr.TESTDATE, tr.TESTDATE, 
CASE tr.TESTRESULT 
 WHEN 'P' THEN tr.TESTDATE 
 WHEN 'PRS' THEN tr.TESTDATE 
 ELSE NULL END,
CASE tr.TESTRESULT 
 WHEN 'P' THEN DATE_ADD(tr.TESTDATE, INTERVAL 1 YEAR) 
 WHEN 'PRS' THEN DATE_ADD(tr.TESTDATE, INTERVAL 1 YEAR) ELSE NULL END,
CASE tr.TESTTYPE 
 WHEN 'RF' THEN 'REFUSED' 
 ELSE CASE tr.TESTRESULT 
  WHEN 'P' THEN 'PASSED' 
  WHEN 'F' THEN 'FAILED' 
  WHEN 'PRS' THEN 'PASSED' 
  WHEN 'ABA' THEN 'ABORTED' 
  WHEN 'ABO' THEN 'ABORTED' 
  ELSE 'ACTIVE' END END
FROM mot_test.TESTRESULT tr
JOIN mot_test.VEHICLE_VTS vv 
ON tr.VEHICLEID = vv.VEHICLEID
JOIN mot.vehicle_testing_station vts 
ON vts.site_number = vv.VTS_SITE_NUMBER;

-- Reasons for rejection
INSERT INTO mot.map_mot_test_rfr
(mot_test_id, rfr_id, name,
failure_text, inspection_manual_reference, type,
location_lateral, location_longitudinal, location_vertical,
comment, failure_dangerous)
SELECT t.id, rfr.rfr_id, test_item_selector_name, description, inspection_manual_reference,
CASE i.RFRTYPE WHEN 'F' THEN 'FAIL' WHEN 'A' THEN 'ADVISORY' WHEN 'P' THEN 'PRS' END,
lat.FAILURELOCATIONTXT,
lon.FAILURELOCATIONTXT,
vert.FAILURELOCATIONTXT,
'',
CASE WHEN DMARK='D' THEN 1 ELSE 0 END
FROM mot.mot_test t
JOIN mot.vehicle v ON t.vehicle_id=v.id
JOIN TESTRESULT r ON r.TESTID=t.secondary_colour
JOIN TESTITEM i ON i.TESTID=r.TESTID
JOIN mot.test_item_selector_rfr rfr ON rfr.rfr_id = i.RFRID AND rfr.vehicle_class = v.vehicle_class
LEFT OUTER JOIN FAILURE_LOCATION lat ON lat.FAILURELOCATIONID=LATLOCATIONID
LEFT OUTER JOIN FAILURE_LOCATION lon ON lon.FAILURELOCATIONID=LONGLOCATIONID
LEFT OUTER JOIN FAILURE_LOCATION vert ON vert.FAILURELOCATIONID=VERTLOCATIONID;



