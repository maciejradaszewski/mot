ALTER TABlE `test_slot_transaction_amendment_reason`
ADD COLUMN `amendment_type_id` SMALLINT UNSIGNED NOT NULL DEFAULT '1' AFTER `description`;

ALTER TABlE `test_slot_transaction_amendment_reason`
ADD CONSTRAINT `fk_test_slot_transaction_amendment_reason_amendment_type_id` FOREIGN KEY (`amendment_type_id`) REFERENCES `test_slot_transaction_amendment_type` (`id`);