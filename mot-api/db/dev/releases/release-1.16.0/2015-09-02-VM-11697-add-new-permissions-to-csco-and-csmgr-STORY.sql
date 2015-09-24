# VM-11687
# adding USER-PASSWORD-RESET, USERNAME-RECOVERY, USER-ACCOUNT-RECLAIM permissions to CSCO and CSMGR roles
# document with list of roles is attached in the jira ticket

SET @created_by = (SELECT `id` FROM `person` WHERE `user_reference` = 'Static Data' OR `username` = 'static data');

INSERT INTO `permission` (`name`, `code`, `created_by`) VALUES
  ('User Password Reset', 'USER-PASSWORD-RESET', @created_by),
  ('Username Recovery', 'USERNAME-RECOVERY', @created_by),
  ('User Account Reclaim', 'USER-ACCOUNT-RECLAIM', @created_by);

SET @user_password_reset_id = (SELECT `id` FROM `permission` WHERE `code` = 'USER-PASSWORD-RESET');
SET @username_recovery_id = (SELECT `id` FROM `permission` WHERE `code` = 'USERNAME-RECOVERY');
SET @user_account_reset_id = (SELECT `id` FROM `permission` WHERE `code` = 'USER-ACCOUNT-RECLAIM');

SET @csmgr = ((SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-MANAGER'));
SET @csco = ((SELECT `id` FROM `role` WHERE `code` = 'CUSTOMER-SERVICE-CENTRE-OPERATIVE'));

INSERT INTO
  `role_permission_map` (`role_id`, `permission_id`, `created_by`)
VALUES
  (
    @csmgr,
    @user_password_reset_id,
    @created_by
  ),
  (
    @csmgr,
    @username_recovery_id,
    @created_by
  ),
  (
    @csmgr,
    @user_account_reset_id,
    @created_by
  ),
  (
    @csco,
    @user_password_reset_id,
    @created_by
  ),
  (
    @csco,
    @username_recovery_id,
    @created_by
  ),
  (
    @csco,
    @user_account_reset_id,
    @created_by
  );