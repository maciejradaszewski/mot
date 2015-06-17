ALTER TABLE `auth_for_ae` MODIFY `status_changed_on` DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6);
ALTER TABLE `auth_for_testing_mot` MODIFY `status_changed_on` DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6);
ALTER TABLE `auth_for_testing_mot_at_site` MODIFY `status_changed_on` DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6);
ALTER TABLE `event` MODIFY `description` varchar(250) NOT NULL;