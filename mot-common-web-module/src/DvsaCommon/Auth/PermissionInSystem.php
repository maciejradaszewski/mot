<?php
namespace DvsaCommon\Auth;

/**
 * List of all system permissions. Permissions at site or organisation level should go to their respective classes.
 *
 * Permission that is present on more than one level should be decided on which level it should exist or if it should be
 * split into two separate permissions.
 */
final class PermissionInSystem
{
    const SLOTS_VIEW = 'SLOTS-VIEW';
    const SLOTS_INCREMENT_BALANCE = 'SLOTS-INCREMENT-BALANCE';
    const AUTHORISED_EXAMINER_CREATE = 'AUTHORISED-EXAMINER-CREATE';
    const AUTHORISED_EXAMINER_STATUS_UPDATE = 'AUTHORISED-EXAMINER-STATUS-UPDATE';
    const AUTHORISED_EXAMINER_LIST = 'AUTHORISED-EXAMINER-LIST';
    const AUTHORISED_EXAMINER_READ_FULL = 'AUTHORISED-EXAMINER-READ-FULL';
    const TESTER_READ = 'TESTER-READ';
    const TESTER_READ_OTHERS = 'TESTER-READ-OTHERS';
    const VEHICLE_READ = 'VEHICLE-READ';
    const VEHICLE_CREATE = 'VEHICLE-CREATE';
    const VEHICLE_TESTING_STATION_CREATE = 'VEHICLE-TESTING-STATION-CREATE';
    const VEHICLE_TESTING_STATION_SEARCH = 'VEHICLE-TESTING-STATION-SEARCH';
    const MOT_DEMO_READ = 'MOT-DEMO-READ';
    const MOT_TEST_TYPE_READ = 'MOT-TEST-TYPE-READ';
    const MOT_TEST_COMPARE = 'MOT-TEST-COMPARE';
    const MOT_TEST_READ = 'MOT-TEST-READ';
    const MOT_TEST_READ_ALL = 'MOT-TEST-READ-ALL';
    const MOT_TEST_LIST = 'MOT-TEST-LIST';
    const MOT_TEST_PERFORM = 'MOT-TEST-PERFORM'; // duplicate of PermissionAtSite::MOT_TEST_PERFORM_AT_SITE
    const MOT_DEMO_TEST_PERFORM = 'MOT-DEMO-TEST-PERFORM';
    const MOT_TEST_REINSPECTION_PERFORM = 'MOT-TEST-REINSPECTION-PERFORM';
    const MOT_TEST_START = 'MOT-TEST-START';
    const RFR_LIST = 'RFR-LIST';
    const APPLICATION_COMMENT = 'APPLICATION-COMMENT';
    const DATA_CATALOG_READ = 'DATA-CATALOG-READ';
    const ENFORCEMENT_SITE_ASSESSMENT = 'ENFORCEMENT-SITE-ASSESSMENT';
    const SPECIAL_NOTICE_READ = 'SPECIAL-NOTICE-READ';
    const SPECIAL_NOTICE_READ_CURRENT = 'SPECIAL-NOTICE-READ-CURRENT';
    const SPECIAL_NOTICE_READ_REMOVED = 'SPECIAL-NOTICE-READ-REMOVED';
    const SPECIAL_NOTICE_ACKNOWLEDGE = 'SPECIAL-NOTICE-ACKNOWLEDGE';
    const SPECIAL_NOTICE_BROADCAST = 'SPECIAL-NOTICE-BROADCAST';
    const SPECIAL_NOTICE_CREATE = 'SPECIAL-NOTICE-CREATE';
    const SPECIAL_NOTICE_UPDATE = 'SPECIAL-NOTICE-UPDATE';
    const SPECIAL_NOTICE_REMOVE = 'SPECIAL-NOTICE-REMOVE';
    const TESTER_RFR_ITEMS_NOT_TESTED = 'TESTER-RFR-ITEMS-NOT-TESTED';
    const VE_RFR_ITEMS_NOT_TESTED = 'VE-RFR-ITEMS-NOT-TESTED';
    const FULL_VEHICLE_MOT_TEST_HISTORY_VIEW = 'FULL-VEHICLE-MOT-TEST-HISTORY-VIEW';
    const LATEST_VEHICLE_MOT_TEST_HISTORY_VIEW = 'LATEST-VEHICLE-MOT-TEST-HISTORY-VIEW';
    const ENFORCEMENT_DEMO_TEST = 'ENFORCEMENT-MOT-DEMO-TEST';
    const VISIT_CREATE = 'VISIT-CREATE';
    const CERTIFICATE_REPLACEMENT = 'CERTIFICATE-REPLACEMENT';
    const CERTIFICATE_REPLACEMENT_FULL = 'CERTIFICATE-REPLACEMENT-FULL';
    const CERTIFICATE_REPLACEMENT_SPECIAL_FIELDS = 'CERTIFICATE-REPLACEMENT-SPECIAL-FIELDS';
    const CERTIFICATE_REPLACEMENT_NO_MISMATCH_ON_VIN_AND_VRN_CHANGE =
        'CERTIFICATE-REPLACEMENT-NO-MISMATCH-VIN-VRN-CHANGE';
    const MOT_CAN_READ_OTHER_PERSON_PROFILE = 'MOT-CAN-READ-OTHER-PERSON-PROFILE';
    const MOT_CAN_EDIT_OTHER_PERSON_PROFILE = 'MOT-CAN-EDIT-OTHER-PERSON-PROFILE';
    const MOT_CAN_ASSIGN_TESTER_PENDING_DEMO_ROLE = 'MOT-CAN-ASSIGN-TESTER-PENDING-DEMO-ROLE';
    const ENFORCEMENT_NON_MOT_TEST_PERFORM = 'ENFORCEMENT-NON-MOT-TEST-PERFORM';
    const MOT_CAN_VIEW_EQUIPMENT = 'MOT-CAN-VIEW-EQUIPMENT';
    const MOT_TEST_WITHOUT_OTP = 'MOT-TEST-WITHOUT-OTP';
    const TESTER_EXPIRY_JOB = 'TESTER-EXPIRY-JOB';
    const VE_MOT_TEST_ABORT = 'VE-MOT-TEST-ABORT';
    const NOMINATE_AEDM = 'NOMINATE-AEDM';
    const SKIP_NOMINATION_REQUEST = 'SKIP-NOMINATION-REQUEST';
    const CERTIFICATE_READ = 'CERTIFICATE-READ';
    const CERTIFICATE_READ_FROM_ANY_SITE = 'CERTIFICATE-READ-FROM-ANY-SITE';
    const MOT_TEST_REFUSE_TEST = 'MOT-TEST-REFUSE-TEST'; # Duplicated permission
    const MOT_TEST_CONFIRM = 'MOT-TEST-CONFIRM'; // duplicate of PermissionAtSite::MOT_TEST_CONFIRM_AT_SITE
    const MOT_TEST_ABANDON = 'MOT-TEST-ABANDON'; # Duplicated permission
    const USER_SEARCH = 'USER-SEARCH';
    const TEST_WITHOUT_BRAKE_TESTS = 'TEST-WITHOUT-BRAKE-TESTS';
    const CREATE_MESSAGE_FOR_OTHER_USER = 'CREATE-MESSAGE-FOR-OTHER-USER';
    const VIEW_OTHER_USER_PROFILE = 'VIEW-OTHER-USER-PROFILE';
    const VIEW_OTHER_USER_PROFILE_DVSA_USER = 'VIEW-OTHER-USER-PROFILE-DVSA-USER';
    const CREATE_USER_ACCOUNT = 'CREATE-USER-ACCOUNT';
    const LIST_EVENT_HISTORY = 'LIST-EVENT-HISTORY';
    const EVENT_READ = 'EVENT-READ';
    const DVSA_SITE_SEARCH = 'DVSA-SITE-SEARCH';
    const PERSON_BASIC_DATA_READ = 'PERSON-BASIC-DATA-READ';
    const EMERGENCY_TEST_READ = 'EMERGENCY-TEST-READ';
    const SECURITY_QUESTION_READ_USER = 'SECURITY-QUESTION-READ-USER';
    const NOTIFICATION_READ = 'NOTIFICATION-READ';
    const NOTIFICATION_UPDATE = 'NOTIFICATION-UPDATE';
    const NOTIFICATION_ACTION = 'NOTIFICATION-ACTION';
    const NOTIFICATION_DELETE = 'NOTIFICATION-DELETE';
    const VEHICLE_MOT_TEST_HISTORY_READ = 'VEHICLE-MOT-TEST-HISTORY-READ';
    const USER_SEARCH_EXTENDED = 'USER-SEARCH-EXTENDED';
    const ASSESS_DEMO_TEST = 'ASSESS-DEMO-TEST';
    const SLOTS_PURCHASE_INSTANT_SETTLEMENT = 'SLOTS-PURCHASE-INSTANT-SETTLEMENT';
    const SLOTS_TRANSACTION_READ_FULL = 'SLOTS-TRANSACTION-READ-FULL';
    const SLOTS_CHARGEBACK = 'SLOTS-CHARGEBACK';
    const SLOTS_TXN_ADJUSTMENT = 'SLOTS-TXN-ADJUSTMENT';
    const SLOTS_REFUND = 'SLOTS-REFUND';
    const SLOTS_REPORTS_GENERATE = 'SLOTS-REPORTS-GENERATE';
    const SLOTS_REPORTS_DOWNLOAD = 'SLOTS-REPORTS-DOWNLOAD';
    const SLOTS_DIRECT_DEBIT_SEARCH = 'SLOTS-DIRECT-DEBIT-SEARCH';
    const SLOTS_TRANSITION = 'SLOTS-TRANSITION';
    const DISPLAY_DVSA_ADMIN_BOX = 'DISPLAY-DVSA-ADMIN-BOX';
    const DISPLAY_TESTER_STATS_BOX = 'DISPLAY-TESTER-STATS-BOX';
    const DISPLAY_TESTER_CONTINGENCY_BOX = 'DISPLAY-TESTER-CONTINGENCY-BOX';
    const VEHICLE_TESTING_STATION_LIST = 'VEHICLE-TESTING-STATION-LIST';
    const PROFILE_EDIT_OTHERS_PERSONAL_DETAILS = 'PROFILE-EDIT-OTHERS-PERSONAL-DETAILS';
    const PROFILE_EDIT_OWN_CONTACT_DETAILS = 'PROFILE-EDIT-OWN-CONTACT-DETAILS';
    const PROFILE_EDIT_OTHERS_EMAIL_ADDRESS = 'PROFILE-EDIT-OTHERS-EMAIL-ADDRESS';
    const CERTIFICATE_PRINT_ANY = 'CERTIFICATE-PRINT-ANY';
    const CERTIFICATE_SEARCH = 'CERTIFICATE-SEARCH';
    const MANAGE_ROLE_CSCO =  'MANAGE-ROLE-CUSTOMER-SERVICE-CENTRE-OPERATIVE';
    const MANAGE_ROLE_CSM = 'MANAGE-ROLE-CUSTOMER-SERVICE-MANAGER';
    const MANAGE_ROLE_DVLA_MANAGER = 'MANAGE-ROLE-DVLA-MANAGER';
    const MANAGE_ROLE_DVLA_OPERATIVE = 'MANAGE-ROLE-DVLA-OPERATIVE';
    const MANAGE_ROLE_DVSA_AREA_OFFICE_1 = 'MANAGE-ROLE-DVSA-AREA-OFFICE-1';
    const MANAGE_ROLE_DVSA_AREA_OFFICE_2 = 'MANAGE-ROLE-DVSA-AREA-OFFICE-2';
    const MANAGE_ROLE_DVSA_SCHEME_MANAGER = 'MANAGE-ROLE-DVSA-SCHEME-MANAGEMENT';
    const MANAGE_ROLE_DVSA_SCHEME_USER = 'MANAGE-ROLE-DVSA-SCHEME-USER';
    const MANAGE_ROLE_FINANCE = 'MANAGE-ROLE-FINANCE';
    const MANAGE_ROLE_VEHICLE_EXAMINER = 'MANAGE-ROLE-VEHICLE-EXAMINER';
    const MANAGE_DVSA_ROLES = 'MANAGE-DVSA-ROLES';
    const USERNAME_VIEW = 'USERNAME-VIEW';

    /**
     * @return array
     */
    public static function all()
    {
        return [
            self::SLOTS_VIEW,
            self::SLOTS_INCREMENT_BALANCE,
            self::AUTHORISED_EXAMINER_CREATE,
            self::AUTHORISED_EXAMINER_STATUS_UPDATE,
            self::AUTHORISED_EXAMINER_LIST,
            self::AUTHORISED_EXAMINER_READ_FULL,
            self::TESTER_READ,
            self::TESTER_READ_OTHERS,
            self::VEHICLE_READ,
            self::VEHICLE_CREATE,
            self::VEHICLE_TESTING_STATION_CREATE,
            self::VEHICLE_TESTING_STATION_SEARCH,
            self::MOT_DEMO_READ,
            self::MOT_TEST_TYPE_READ,
            self::MOT_TEST_COMPARE,
            self::MOT_TEST_READ,
            self::MOT_TEST_READ_ALL,
            self::MOT_TEST_LIST,
            self::MOT_TEST_PERFORM,
            self::MOT_DEMO_TEST_PERFORM,
            self::MOT_TEST_START,
            self::RFR_LIST,
            self::APPLICATION_COMMENT,
            self::DATA_CATALOG_READ,
            self::ENFORCEMENT_SITE_ASSESSMENT,
            self::SPECIAL_NOTICE_READ,
            self::SPECIAL_NOTICE_READ_CURRENT,
            self::SPECIAL_NOTICE_READ_REMOVED,
            self::SPECIAL_NOTICE_ACKNOWLEDGE,
            self::SPECIAL_NOTICE_BROADCAST,
            self::SPECIAL_NOTICE_CREATE,
            self::SPECIAL_NOTICE_UPDATE,
            self::SPECIAL_NOTICE_REMOVE,
            self::TESTER_RFR_ITEMS_NOT_TESTED,
            self::VE_RFR_ITEMS_NOT_TESTED,
            self::FULL_VEHICLE_MOT_TEST_HISTORY_VIEW,
            self::LATEST_VEHICLE_MOT_TEST_HISTORY_VIEW,
            self::ENFORCEMENT_DEMO_TEST,
            self::VISIT_CREATE,
            self::CERTIFICATE_PRINT_ANY,
            self::CERTIFICATE_REPLACEMENT,
            self::CERTIFICATE_REPLACEMENT_FULL,
            self::CERTIFICATE_REPLACEMENT_SPECIAL_FIELDS,
            self::CERTIFICATE_REPLACEMENT_NO_MISMATCH_ON_VIN_AND_VRN_CHANGE,
            self::MOT_CAN_READ_OTHER_PERSON_PROFILE,
            self::MOT_CAN_EDIT_OTHER_PERSON_PROFILE,
            self::MOT_CAN_ASSIGN_TESTER_PENDING_DEMO_ROLE,
            self::ENFORCEMENT_NON_MOT_TEST_PERFORM,
            self::MOT_CAN_VIEW_EQUIPMENT,
            self::MOT_TEST_WITHOUT_OTP,
            self::TESTER_EXPIRY_JOB,
            self::VE_MOT_TEST_ABORT,
            self::NOMINATE_AEDM,
            self::SKIP_NOMINATION_REQUEST,
            self::CERTIFICATE_READ,
            self::CERTIFICATE_READ_FROM_ANY_SITE,
            self::MOT_TEST_REFUSE_TEST,
            self::MOT_TEST_CONFIRM,
            self::USER_SEARCH,
            self::MOT_TEST_ABANDON,
            self::MOT_TEST_REINSPECTION_PERFORM,
            self::TEST_WITHOUT_BRAKE_TESTS,
            self::CREATE_MESSAGE_FOR_OTHER_USER,
            self::VIEW_OTHER_USER_PROFILE,
            self::VIEW_OTHER_USER_PROFILE_DVSA_USER,
            self::CREATE_USER_ACCOUNT,
            self::VEHICLE_MOT_TEST_HISTORY_READ,
            self::LIST_EVENT_HISTORY,
            self::EVENT_READ,
            self::DVSA_SITE_SEARCH,
            self::PERSON_BASIC_DATA_READ,
            self::EMERGENCY_TEST_READ,
            self::SECURITY_QUESTION_READ_USER,
            self::NOTIFICATION_READ,
            self::NOTIFICATION_ACTION,
            self::NOTIFICATION_UPDATE,
            self::NOTIFICATION_DELETE,
            self::USER_SEARCH_EXTENDED,
            self::SLOTS_PURCHASE_INSTANT_SETTLEMENT,
            self::SLOTS_TRANSACTION_READ_FULL,
            self::SLOTS_CHARGEBACK,
            self::SLOTS_TXN_ADJUSTMENT,
            self::SLOTS_REFUND,
            self::SLOTS_TXN_ADJUSTMENT,
            self::SLOTS_REPORTS_GENERATE,
            self::SLOTS_REPORTS_DOWNLOAD,
            self::SLOTS_DIRECT_DEBIT_SEARCH,
            self::SLOTS_TRANSITION,
            self::DISPLAY_DVSA_ADMIN_BOX,
            self::DISPLAY_TESTER_STATS_BOX,
            self::DISPLAY_TESTER_CONTINGENCY_BOX,
            self::ASSESS_DEMO_TEST,
            self::VEHICLE_TESTING_STATION_LIST,
            self::PROFILE_EDIT_OTHERS_PERSONAL_DETAILS,
            self::PROFILE_EDIT_OTHERS_EMAIL_ADDRESS,
            self::PROFILE_EDIT_OWN_CONTACT_DETAILS,
            self::CERTIFICATE_SEARCH,
            self::MANAGE_ROLE_CSCO,
            self::MANAGE_ROLE_CSM,
            self::MANAGE_ROLE_DVLA_MANAGER,
            self::MANAGE_ROLE_DVLA_OPERATIVE,
            self::MANAGE_ROLE_DVSA_AREA_OFFICE_1,
            self::MANAGE_ROLE_DVSA_AREA_OFFICE_2,
            self::MANAGE_ROLE_DVSA_SCHEME_MANAGER,
            self::MANAGE_ROLE_DVSA_SCHEME_USER,
            self::MANAGE_ROLE_FINANCE,
            self::MANAGE_ROLE_VEHICLE_EXAMINER,
            self::MANAGE_DVSA_ROLES,
            self::USERNAME_VIEW,
        ];
    }
}
