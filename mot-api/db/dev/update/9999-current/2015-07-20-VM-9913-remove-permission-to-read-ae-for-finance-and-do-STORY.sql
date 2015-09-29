/**********************

VM-9913

Finance and DVLA-operative users could read Authorised Examiner overview page.
Now they cannot.

**********************/

DELETE map
FROM role_permission_map map
JOIN role on role.id = map.role_id
JOIN permission on permission.id = map.permission_id
WHERE (permission.code = 'AUTHORISED-EXAMINER-READ' AND role.code = 'FINANCE')
OR    (permission.code = 'AUTHORISED-EXAMINER-READ' AND role.code = 'DVLA-OPERATIVE')
OR    (permission.code = 'AUTHORISED-EXAMINER-READ-FULL' AND role.code = 'FINANCE')
OR    (permission.code = 'AUTHORISED-EXAMINER-READ-FULL' AND role.code = 'DVLA-OPERATIVE')
OR    (permission.code = 'VEHICLE-TESTING-STATION-READ' AND role.code = 'FINANCE')
OR    (permission.code = 'VEHICLE-TESTING-STATION-READ' AND role.code = 'DVLA-OPERATIVE');


