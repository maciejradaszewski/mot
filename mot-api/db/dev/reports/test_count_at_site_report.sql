delimiter //
DROP PROCEDURE IF EXISTS test_count_at_site_report //

CREATE PROCEDURE test_count_at_site_report (IN i_site_id INT(10), IN i_report_start_date DATETIME(6), IN i_report_end_date DATETIME(6), IN i_active_days SMALLINT)
BEGIN

    DECLARE i_created_start_date DATETIME(6);

    SET i_created_start_date = DATE_SUB(i_report_start_date, INTERVAL i_active_days  DAY);

    SELECT  concat( ifnull(a.ae_ref,'null'), ',', ifnull(se.organisation_id,'null'), ',', ifnull(se.site_number,'null'), ',', m.site_id, ',',
        #Need to calculate the effect on the slot balance from each test 'action' within the reporting window.
        #This will either be:
        # a) consumption of 1 slot for a regular test pass done within the window.
        # b) no slot usage if a failed test started and finished within the report window
        # c) return of a slot if the test was started before report window but failed within it.
        SUM(CASE
           WHEN s.code = 'P' AND
                m.created_on >= i_report_start_date AND
                m.created_on <= i_report_end_date
           THEN 1 #applies to contingency AND non-contingency tests

           WHEN s.code = 'A' AND
                m.created_on >= i_report_start_date AND
                m.created_on <= i_report_end_date
           THEN 1 #applies to both contingency AND non-contingency tests

           WHEN m.emergency_log_id is null AND
                s.code NOT IN ('P','A')  AND
                m.created_on >= i_report_start_date AND
                m.completed_date <= i_report_end_date
           THEN 0 #applies to non-contingency tests - slot usage cancelled by failure

           WHEN m.emergency_log_id is null AND
                s.code NOT IN ('P','A')  AND
                m.created_on >= i_report_start_date AND
                m.created_on <= i_report_end_date AND
                m.completed_date > i_report_end_date
           THEN 1  #applies to non-contingency tests - slot added back outside window

           WHEN m.emergency_log_id is null AND
                s.code NOT IN ('P','A')  AND
                m.created_on < i_report_start_date AND
                m.completed_date >= i_report_start_date AND
                m.completed_date <= i_report_end_date
           THEN -1 #applies to non-contingency tests started before reporting window, but failing inside it.

           WHEN m.emergency_log_id IS NOT NULL AND
                s.code NOT IN ('P','A')  AND
                m.created_on < i_report_start_date AND
                m.last_updated_on >= i_report_start_date AND
                m.last_updated_on <= i_report_end_date
           THEN #applies to contingency tests started before reporting window, but failing inside it.
               IF((SELECT IFNULL(min(h.last_updated_on),m.created_on)
                   FROM mot_test_hist h
                   WHERE m.id = h.id
                   AND h.status_id IS NOT NULL
                   AND h.status_id not in (4,6)) BETWEEN i_report_start_date AND i_report_end_date,  -1, 0)

           WHEN m.emergency_log_id IS NOT NULL AND
                s.code NOT IN ('P','A')  AND
                m.created_on >= i_report_start_date AND
                m.created_on <= i_report_end_date AND
                m.last_updated_on > i_report_end_date
           THEN #applies to contingency tests started inside reporting window, but failing outside it.
               IF((SELECT IFNULL(min(h.last_updated_on),m.created_on)
                   FROM mot_test_hist h
                   WHERE m.id = h.id
                   AND h.status_id IS NOT NULL
                   AND h.status_id not in (4,6)) > i_report_end_date,  1, 0)
           ELSE 0
           END) ) as line
    FROM mot_test m          FORCE INDEX (ix_mot_test_created_on_emergency_log_id)
    JOIN mot_test_status s   ON s.id = m.status_id
    JOIN mot_test_type t     ON t.id = m.mot_test_type_id AND t.code IN ('NT','PL','PV','RT','ES','EI')
    JOIN site se             ON se.id = m.site_id
    LEFT JOIN auth_for_ae a  ON se.organisation_id = a.organisation_id
    WHERE m.created_on >= i_created_start_date
    AND   m.created_on <= i_report_end_date
    AND   m.mot1_legacy_id is null
    AND   (i_site_id is null OR i_site_id = m.site_id)
    GROUP BY  a.ae_ref, se.organisation_id, se.site_number, m.site_id;

END //
delimiter ;

