delimiter //
DROP PROCEDURE IF EXISTS gen_transition_fin_report //

CREATE PROCEDURE gen_transition_fin_report()
BEGIN
SELECT concat(IFNULL(o.name,''), ',',
   IFNULL(afa.ae_ref,''), ',',
   IFNULL(tar.description,''), ',',
   IFNULL(ta.slots,''), ',',
   date_format(ta.created_on, '%Y-%m-%d %T'))
 FROM test_slot_transaction_amendment ta
 INNER JOIN organisation o ON o.id = ta.organisation_id
 INNER JOIN auth_for_ae afa ON afa.organisation_id = o.id
 INNER JOIN test_slot_transaction_amendment_reason tar ON tar.id = ta.reason_id
 WHERE ta.type_id = 1
 GROUP BY ta.id, o.name, afa.ae_ref, tar.description, ta.slots, ta.created_on
 ORDER BY ta.created_on;
END //
delimiter ;
