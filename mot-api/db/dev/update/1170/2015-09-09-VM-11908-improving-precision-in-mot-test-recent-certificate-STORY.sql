-- VM-11908

ALTER TABLE `mot_test_recent_certificate` MODIFY `generation_started_on` DATETIME(6) DEFAULT NULL COMMENT 'time worker picked up row';
ALTER TABLE `mot_test_recent_certificate` MODIFY `generation_completed_on` DATETIME(6) DEFAULT NULL COMMENT 'time worker completed certificate generation';