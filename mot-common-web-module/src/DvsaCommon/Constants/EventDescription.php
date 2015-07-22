<?php


namespace DvsaCommon\Constants;

/**
 * Class EventDescription
 *
 * @package DvsaCommon\Constants
 */
class EventDescription extends BaseEnumeration
{
    const USER_CLAIMS_ACCOUNT = 'Account claimed by user %s';
    const USER_RECLAIMS_ACCOUNT = 'Account reclaimed by user %s';
    const USER_ACCOUNT_RESET = 'Account for user %s reset by %s';
    const ROLE_ASSOCIATION_CHANGE = '%s role associated with %s (%s)';
    const ROLE_ASSOCIATION_REMOVE = '%s role removed from %s (%s)';
    const ROLE_ASSOCIATION_REMOVE_SITE_ORG = '%s role for %s removed from %s (%s)';
    const DVSA_ADMINISTRATOR_CREATE_AE = 'Authorised examiner %s %s created by user %s';
    const TESTER_QUALIFICATION_STATUS_CHANGE = 'Qualified to test Group %s vehicles following a demonstration test. Recorded by %s';
}
