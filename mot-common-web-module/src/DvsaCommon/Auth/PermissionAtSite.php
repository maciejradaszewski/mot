<?php
namespace DvsaCommon\Auth;

/**
 * List of all site level permissions. Global or Organisation permissions should go to their respective classes.
 */
class PermissionAtSite
{
    const VIEW_TESTS_IN_PROGRESS_AT_VTS = 'VIEW-TESTS-IN-PROGRESS-AT-VTS';
    const MOT_TEST_REFUSE_TEST_AT_SITE = 'MOT-TEST-REFUSE-TEST-AT-SITE';
    const MOT_TEST_ABORT_AT_SITE = 'MOT-TEST-ABORT-AT-SITE';
    const MOT_TEST_CONFIRM_AT_SITE = 'MOT-TEST-CONFIRM-AT-SITE'; // duplicate of PermissionInSystem::MOT_TEST_CONFIRM
    const MOT_TEST_ABANDON_AT_SITE = 'MOT-TEST-ABANDON-AT-SITE'; // Duplicated permission
    const SITE_SLOTS_USAGE_READ = 'SITE-SLOTS-USAGE-READ';
    const VEHICLE_TESTING_STATION_READ = 'VEHICLE-TESTING-STATION-READ';
    const VTS_UPDATE_NAME = 'VTS-UPDATE-NAME';
    const VTS_UPDATE_CORRESPONDENCE_DETAILS = 'VTS-UPDATE-CORRESPONDENCE-DETAILS';
    const VTS_UPDATE_BUSINESS_DETAILS = 'VTS-UPDATE-BUSINESS-DETAILS';
    const MOT_TEST_START_AT_SITE = 'MOT-TEST-START-AT-SITE'; // duplicate
    const MOT_TEST_PERFORM_AT_SITE = 'MOT-TEST-PERFORM-AT-SITE';
    const NOMINATE_ROLE_AT_SITE = 'NOMINATE-ROLE-AT-SITE';
    const REMOVE_ROLE_AT_SITE = 'REMOVE-ROLE-AT-SITE';
    const CERTIFICATE_PRINT = 'CERTIFICATE-PRINT';
    const VTS_EMPLOYEE_PROFILE_READ = 'VTS-EMPLOYEE-PROFILE-READ';
    const REMOVE_SITE_MANAGER = 'REMOVE-SITE-MANAGER';
    const TESTING_SCHEDULE_UPDATE = 'TESTING-SCHEDULE-UPDATE';
    const DEFAULT_BRAKE_TESTS_CHANGE = 'DEFAULT-BRAKE-TESTS-CHANGE';
    const VTS_USERNAME_VIEW = 'VTS-USERNAME-VIEW';

    public static function all()
    {
        return [
            self::VIEW_TESTS_IN_PROGRESS_AT_VTS,
            self::MOT_TEST_REFUSE_TEST_AT_SITE,
            self::MOT_TEST_ABORT_AT_SITE,
            self::MOT_TEST_CONFIRM_AT_SITE, # Duplicated permission
            self::MOT_TEST_ABANDON_AT_SITE,
            self::SITE_SLOTS_USAGE_READ,
            self::VEHICLE_TESTING_STATION_READ,
            self::VTS_UPDATE_NAME,
            self::VTS_UPDATE_CORRESPONDENCE_DETAILS,
            self::VTS_UPDATE_BUSINESS_DETAILS,
            self::MOT_TEST_START_AT_SITE,
            self::MOT_TEST_PERFORM_AT_SITE,
            self::NOMINATE_ROLE_AT_SITE,
            self::REMOVE_ROLE_AT_SITE,
            self::CERTIFICATE_PRINT,
            self::VTS_EMPLOYEE_PROFILE_READ,
            self::REMOVE_SITE_MANAGER,
            self::TESTING_SCHEDULE_UPDATE,
            self::DEFAULT_BRAKE_TESTS_CHANGE,
            self::VTS_USERNAME_VIEW,
        ];
    }
}
