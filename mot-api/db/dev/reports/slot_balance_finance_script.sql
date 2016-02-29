SET @ae_ref='ALL';
SET @report_start_date='2015-12-01 00:00:00.000000';
SET @report_end_date='2015-12-31 23:59.59.999999';

SET @created_start_date = DATE_SUB(@report_start_date, INTERVAL 10  DAY);

SELECT CONCAT( 
       ae_ref, ',',                        # AE Reference
       REPLACE(name,',',''), ',',      #as 'AE Name',
       IFNULL(ae_status,''), ',',      #as 'AE Status', 
       DATE(@report_start_date), ',',
       DATE(@report_end_date), ',',
       IFNULL(opening_slots,0), ',',   # as 'Start Slot Balance', 
       IFNULL(sum_slots_used,0), ',',  # as 'Slots Used', 
       IFNULL(slots_purchased,0), ',', # as 'Purchased Volume',
       IFNULL(slot_amendments,0), ',', # as 'Slot Amendments', 
       IFNULL(end_slots,0),',',        # as 'End Slot Balance',
       IFNULL(opening_slots,0) 
           - IFNULL(sum_slots_used,0) 
           + IFNULL(slots_purchased,0) 
           + IFNULL(slot_amendments,0),',',  # Expected Slot Balance
      (IFNULL(opening_slots,0) 
           - IFNULL(sum_slots_used,0) 
           + IFNULL(slots_purchased,0) 
           + IFNULL(slot_amendments,0)) 
           - IFNULL(end_slots,0), ',',      # Difference bewteen actual and expected balance      
        ae_ref, ',',                        # AE Reference
        site_change_count, ',',             # Indication that a AE to Site relationship was altering within reporting window.
        ifnull(ae_list,'')                  # Other AEs Slot Usage counts impacted by site changes.
    )
    as 'AE_Ref, AE Name, AE status, Start Date, End Date, Start Slot Balance, Slots Used, Purchased Volume, Slot Amendments, End Slot Balance, Expected End Slot Balance, Variance, AE Ref, AE-Site Changes, AE_list'
FROM  
(SELECT o.id, afa.ae_ref, o.name, ast.name as ae_status, o.slots_balance, coalesce(o.last_updated_on,o.created_on) last_modified_on,
        IFNULL((SELECT ifnull(oh.slots_balance,0)      #This is OLD value before transactions
        FROM  organisation_hist oh 
        JOIN  transition_status ts on ts.id = oh.transition_status_id
        WHERE oh.id = o.id
        AND   oh.hist_timestamp >= @report_start_date
        AND   oh.last_updated_on <= @report_end_date
        AND   ts.code = 'FULL'
        ORDER BY oh.hist_timestamp
        LIMIT 1),
          CASE
          WHEN 
               coalesce(o.last_updated_on,o.created_on)  <= @report_end_date
          THEN IFNULL(slots_balance,0)
          ELSE 0
          END) opening_slots,
       IFNULL(test_count.sum_slots_used,0) sum_slots_used,
       CASE 
       WHEN 
            coalesce(o.last_updated_on,o.created_on) <= @report_end_date 
       THEN o.slots_balance
       ELSE
         (SELECT oh.slots_balance     #This is OLD value after all transactions
          FROM organisation_hist oh 
          JOIN  transition_status ts on ts.id = oh.transition_status_id
          WHERE oh.id = o.id
          AND   oh.hist_timestamp >= @report_start_date
          AND   oh.last_updated_on <= @report_end_date
          AND   ts.code = 'FULL'
          ORDER BY oh.hist_timestamp desc
          LIMIT 1) 
       END as end_slots,
       (SELECT sum(tst.slots) 
        FROM test_slot_transaction tst
        JOIN test_slot_transaction_status tss ON tst.status_id = tss.id
        JOIN payment p ON tst.payment_id = p.id
        JOIN payment_status ps ON p.status_id = ps.id
        WHERE tss.code = 'CO' #Complete
        AND   coalesce(tst.completed_on,tst.created_on) >= @report_start_date
        AND   coalesce(tst.completed_on,tst.created_on) <= @report_end_date
        AND   ps.code = 'S'   #Success
        AND   tst.organisation_id = o.id) slots_purchased,
       ((SELECT ifnull(sum(tsa.slots),0) 
        FROM  test_slot_transaction_amendment tsa
        JOIN  test_slot_transaction_amendment_type atype ON atype.id = tsa.type_id
        WHERE tsa.created_on >= @report_start_date
        AND   tsa.created_on <= @report_end_date
        AND   atype.code <> 'T702'
        AND   tsa.organisation_id = o.id) +
       (SELECT ifnull(sum(tsa.slots),0) * -1 
        FROM  test_slot_transaction_amendment tsa
        JOIN  test_slot_transaction_amendment_type atype ON atype.id = tsa.type_id
        WHERE tsa.created_on >= @report_start_date
        AND   tsa.created_on <= @report_end_date
        AND   atype.code = 'T702'
        AND   tsa.organisation_id = o.id) ) slot_amendments ,
       (#Sites previously linked to organisation potentially in the report window are no longer linked.
         SELECT ifnull(count(distinct id),0) 
         FROM site_hist h
         WHERE h.organisation_id = o.id
         AND  h.hist_timestamp >= @report_start_date
         AND  h.last_updated_on <= @report_end_date
         AND NOT EXISTS 
         (SELECT 1 FROM site s
          WHERE h.id = s.id
          AND   h.organisation_id = s.organisation_id)
         ) +
         # currently linked sites which were linked to a different organisation since start of the report window
        (SELECT ifnull(count(distinct id),0) 
         FROM site_hist h  
         WHERE h.id in (select id FROM site WHERE organisation_id = o.id)
         AND   h.last_updated_on <= @report_end_date
         AND   h.hist_timestamp >= @report_start_date
         AND   h.organisation_id is not null
         AND   h.organisation_id <> o.id
         )   
         site_change_count,
         replace(concat(ifnull(ae_not_linked.ae_list,','),',',ifnull(new_ae_link.ae_list ,'')),',,','') ae_list
FROM organisation o
JOIN auth_for_ae afa ON afa.organisation_id = o.id
JOIN auth_for_ae_status ast ON afa.status_id = ast.id
LEFT JOIN
  (SELECT o.id,
        #Need to calculate the effect on the slot balance from each test 'action' within the reporting window.
        #This will either be:
        # a) consumption of 1 slot for a regular test pass done within the window.
        # b) no slot usage if a failed test started and finished within the report window
        # c) return of a slot if the test was started before report window but failed within it.
        SUM(CASE
           WHEN s.code = 'P' AND
                m.created_on >= @report_start_date AND
                m.created_on <= @report_end_date 
           THEN 1 #applies to contingency AND non-contingency tests

           WHEN s.code = 'A' AND
                m.created_on >= @report_start_date AND
                m.created_on <= @report_end_date 
           THEN 1 #applies to both contingency AND non-contingency tests
           
           WHEN m.emergency_log_id is null AND
                s.code NOT IN ('P','A')  AND 
                m.created_on >= @report_start_date AND
                m.completed_date <= @report_end_date 
           THEN 0 #applies to non-contingency tests - slot usage cancelled by failure
 
           WHEN m.emergency_log_id is null AND
                s.code NOT IN ('P','A')  AND 
                m.created_on >= @report_start_date AND
                m.created_on <= @report_end_date AND
                m.completed_date > @report_end_date 
           THEN 1  #applies to non-contingency tests - slot added back outside window

           WHEN m.emergency_log_id is null AND
                s.code NOT IN ('P','A')  AND 
                m.created_on < @report_start_date AND
                m.completed_date >= @report_start_date AND
                m.completed_date <= @report_end_date 
           THEN -1 #applies to non-contingency tests started before reporting window, but failing inside it.
 
           WHEN m.emergency_log_id IS NOT NULL AND
                s.code NOT IN ('P','A')  AND 
                m.created_on < @report_start_date AND
                m.last_updated_on >= @report_start_date AND
                m.last_updated_on <= @report_end_date 
           THEN #applies to contingency tests started before reporting window, but failing inside it.
               IF((SELECT IFNULL(min(h.last_updated_on),m.created_on)  
                   FROM mot_test_hist h 
                   WHERE m.id = h.id 
                   AND h.status_id IS NOT NULL 
                   AND h.status_id not in (4,6)) BETWEEN @report_start_date AND @report_end_date,  -1, 0) 

           WHEN m.emergency_log_id IS NOT NULL AND
                s.code NOT IN ('P','A')  AND 
                m.created_on >= @report_start_date AND
                m.created_on <= @report_end_date AND
                m.last_updated_on > @report_end_date
           THEN #applies to contingency tests started inside reporting window, but failing outside it.
               IF((SELECT IFNULL(min(h.last_updated_on),m.created_on)  
                   FROM mot_test_hist h 
                   WHERE m.id = h.id 
                   AND h.status_id IS NOT NULL 
                   AND h.status_id not in (4,6)) > @report_end_date,  1, 0) 
           ELSE 0 
           END
        )  sum_slots_used  
   FROM organisation o 
   JOIN auth_for_ae afa ON afa.organisation_id = o.id
   JOIN site se ON se.organisation_id = o.id
   JOIN mot_test m FORCE INDEX (ix_mot_test_created_on_emergency_log_id) ON m.site_id = se.id 
   JOIN mot_test_status s ON s.id = m.status_id # AND s.code in ('P','A','F')   
   JOIN mot_test_type t   ON t.id = m.mot_test_type_id AND t.code IN ('NT','PL','PV','RT','ES','EI')
   WHERE m.created_on >= @created_start_date
   AND   m.created_on <= @report_end_date
   AND  (@ae_ref = 'ALL' OR afa.ae_ref = @ae_ref)
   AND   m.mot1_legacy_id is null
   GROUP BY o.id
   ) test_count  ON test_count.id = o.id
LEFT JOIN 
( # list of previous AEs (or site links) where a different (or no) current AE link exists.
    SELECT site_hist.organisation_id, group_concat(distinct concat(ifnull(ae.ae_ref,concat('Site Unlink')),' [',se.site_number,']')) ae_list
    FROM site_hist
    JOIN site se             ON site_hist.id = se.id 
    LEFT JOIN auth_for_ae ae ON ae.organisation_id = se.organisation_id
    WHERE 
          site_hist.organisation_id is not null
    AND   site_hist.hist_timestamp >= @report_start_date  #A change was made since start of report window.
    AND NOT EXISTS                                        #Site not linked to same organisation as before.
        (SELECT 1 FROM site
         WHERE site.id = site_hist.id
         AND   site.organisation_id = site_hist.organisation_id
         )   
    GROUP BY site_hist.organisation_id
) ae_not_linked   ON ae_not_linked.organisation_id = o.id
LEFT JOIN 
(  # list of previous AEs from perspective of currently linked organisation.
    SELECT se.organisation_id, group_concat(distinct concat(ifnull(ae.ae_ref,concat('Site Unlink')),' [',se.site_number,']')) ae_list
    FROM site_hist 
    JOIN site se             ON site_hist.id = se.id
    LEFT JOIN auth_for_ae ae ON ae.organisation_id = site_hist.organisation_id
    WHERE 
          site_hist.organisation_id is not null
    AND   site_hist.hist_timestamp >= @report_start_date  #A change was made since start of report window.
    AND EXISTS                                            #Site is now linked to a different AE from that at the time of report window.
	    (SELECT 1 FROM site
	     WHERE site.id = site_hist.id
	     AND   site.organisation_id <> site_hist.organisation_id
	     )
    GROUP BY se.organisation_id
) new_ae_link   ON new_ae_link.organisation_id = o.id
WHERE 
   (@ae_ref = 'ALL' OR afa.ae_ref = @ae_ref)
) sb_rpt;

