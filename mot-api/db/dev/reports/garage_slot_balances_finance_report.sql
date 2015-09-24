DROP PROCEDURE IF EXISTS gen_transition_slot_balances_fin_report;

delimiter //
CREATE PROCEDURE gen_transition_slot_balances_fin_report()
BEGIN
SELECT
    concat(IFNULL(a.ae_ref,'NULL'), ',',
    IFNULL(o.name,'NULL'), ',',
    IFNULL(o.mot1_total_slots_merged,'NULL'), ',',
    IFNULL(slots_purchased_sum,'NULL'), ',',
    IFNULL(slots_used_count,'NULL'))
FROM
    organisation o
INNER JOIN
    auth_for_ae a
    ON a.organisation_id = o.id
   AND a.status_id NOT IN (0,1) -- IGNORE UNKNOWN AND APPLICATIONS
LEFT OUTER JOIN
    (SELECT
        t.organisation_id, SUM(t.slots) slots_purchased_sum
    FROM
        test_slot_transaction t
    WHERE
        t.status_id = 2
            AND t.completed_on BETWEEN DATE(DATE_ADD(NOW(), INTERVAL -1 DAY)) AND DATE(NOW())
    GROUP BY 1) slots_purchased ON slots_purchased.organisation_id = o.id
LEFT OUTER JOIN
    (SELECT
        s.organisation_id, COUNT(mt.id) slots_used_count
    FROM
        mot_test mt
    INNER JOIN site s ON mt.site_id = s.id
    WHERE
        mt.mot_test_type_id IN (1 , 2, 3, 9)
            AND mt.status_id = 6
            AND mt.completed_date BETWEEN DATE(DATE_ADD(NOW(), INTERVAL -1 DAY)) AND DATE(NOW())
    GROUP BY 1) slots_used ON slots_used.organisation_id = o.id
ORDER BY a.ae_ref , o.name , o.mot1_total_slots_merged , slots_purchased_sum , slots_used_count;
END //
delimiter ;
