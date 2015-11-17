<?php

namespace DvsaCommon\Enum;

/**
 * Enum class generated from the 'event_type_lookup' table
 *
 * DO NOT EDIT! -- THIS CLASS IS GENERATED BY mot-common-web-module/generate_enums.php
 * @codeCoverageIgnore
 */
class EventTypeCode
{
    const MOT_MANAGEMENT_TRAINING = 'AE';
    const APPEAL_AGAINST_DISCIPLINARY_ACTION = 'APPL';
    const CONVICTIONS_MOT_MOTOR_TRADE_CRIMINAL = 'CONVC';
    const DESK_BASED_ASSESSMENT = 'DBA';
    const DBA_REFERRAL_TO_AREA_OFFICE = 'DBARF';
    const VT6_8_IN_ROLLOUT = 'DEMOR';
    const DISCIPLINARY_ACTION = 'DISCP';
    const DIRECTED_SITE_VISIT = 'DSV';
    const MYSTERY_SHOPPER = 'INC';
    const LEVEL_1_ACTION = 'LEV1';
    const MEMO = 'MEMO';
    const APPEAL_DISALLOWED = 'NAD';
    const APPEALS_BR_APPEAL_INC_FORMAL_WARNING = 'NAFW';
    const APPEALS_BR_APPEAL_REJECT_OUT_OF_TIME = 'NAPRJ';
    const APPEALS_BR_APPEAL_WITHDRAWN = 'NAPWD';
    const APPEALS_BR_APPEAL_UPHELD = 'NAU';
    const FAILURE_TO_NOTIFY_VTS_CLOSURE_CHANGE_OWNER = 'NOTFY';
    const MIGRATED_NTT_CLASSES_UNSPECIFIED = 'NTTM';
    const NTT_INITIAL_CLASSES_3457 = 'NTT2';
    const NTT_DIRECTED_RETRAINING_CLASSES_3457 = 'NTTD';
    const NTT_MOTORCYCLE = 'NTTMC';
    const NTT_REFRESHER_CLASSES_3457 = 'NTTR';
    const MIGRATED_NTT_REFRESHER_CLASSES_3457 = 'REFM';
    const LOSS_OF_REPUTE_CONVICTIONS = 'REPUT';
    const REVIEW_OF_FORMAL_WARNING = 'RFW';
    const SITE_ASSESSMENT = 'SA';
    const SPECIAL_INVESTIGATION = 'SI';
    const TRANSFER_SITE_ASSESSMENT_TO_NEW_AE_VTS_LINK = 'TRSA';
    const APPEAL_AGAINST_VT30_ISSUE = 'VT19';
    const APPEAL_AGAINST_VT20_ISSUE = 'VT19I';
    const SCHEDULED_VTS_VISIT = 'VT25';
    const TARGETTED_VTS_VISIT = 'VT50';
    const TARGETED_RE_INSPECTION = 'VT55';
    const MOT_COMPLIANCE_SURVEY = 'VT55R';
    const DEMONSTRATION_TEST = 'VT6';
    const VT7 = 'VT7';
    const USER_CLAIMS_ACCOUNT = 'UCA';
    const ROLE_ASSOCIATION_CHANGE = 'RAC';
    const USER_RECLAIMS_ACCOUNT = 'URA';
    const USER_ACCOUNT_RESET = 'UAR';
    const GROUP_A_TESTER_QUALIFICATION = 'GATQ';
    const GROUP_B_TESTER_QUALIFICATION = 'GBTQ';
    const DVSA_ADMINISTRATOR_CREATE_AE = 'CAE';
    const DVSA_ADMINISTRATOR_CREATE_SITE = 'CS';
    const UPDATE_AE = 'UAE';
    const UNLINK_AE_SITE = 'AEULS';
    const DVSA_ADMINISTRATOR_LINK_A_SITE_TO_AN_AE = 'LAES';
    const DVSA_ADMINISTRATOR_UPDATE_SITE = 'US';
    const INTELLIGENCE_MARKER = 'INTM';
    const VTS_COMPLAINT = 'VTSCO';
    const TESTER_TRAINING_ASSESSMENT = 'NTTA';
    const NTT_DIRECTED_RETRAINING_CLASSES_12 = 'NTTDA';
    const NTT_REFRESHER_CLASSES_12 = 'NTTRA';
    const UPDATE_SITE_ASSESSMENT_RISK_SCORE = 'USARS';

    /**
     * @return array of values for the type EventTypeCode
     */
    public static function getAll()
    {
        return [
            self::MOT_MANAGEMENT_TRAINING,
            self::APPEAL_AGAINST_DISCIPLINARY_ACTION,
            self::CONVICTIONS_MOT_MOTOR_TRADE_CRIMINAL,
            self::DESK_BASED_ASSESSMENT,
            self::DBA_REFERRAL_TO_AREA_OFFICE,
            self::VT6_8_IN_ROLLOUT,
            self::DISCIPLINARY_ACTION,
            self::DIRECTED_SITE_VISIT,
            self::MYSTERY_SHOPPER,
            self::LEVEL_1_ACTION,
            self::MEMO,
            self::APPEAL_DISALLOWED,
            self::APPEALS_BR_APPEAL_INC_FORMAL_WARNING,
            self::APPEALS_BR_APPEAL_REJECT_OUT_OF_TIME,
            self::APPEALS_BR_APPEAL_WITHDRAWN,
            self::APPEALS_BR_APPEAL_UPHELD,
            self::FAILURE_TO_NOTIFY_VTS_CLOSURE_CHANGE_OWNER,
            self::MIGRATED_NTT_CLASSES_UNSPECIFIED,
            self::NTT_INITIAL_CLASSES_3457,
            self::NTT_DIRECTED_RETRAINING_CLASSES_3457,
            self::NTT_MOTORCYCLE,
            self::NTT_REFRESHER_CLASSES_3457,
            self::MIGRATED_NTT_REFRESHER_CLASSES_3457,
            self::LOSS_OF_REPUTE_CONVICTIONS,
            self::REVIEW_OF_FORMAL_WARNING,
            self::SITE_ASSESSMENT,
            self::SPECIAL_INVESTIGATION,
            self::TRANSFER_SITE_ASSESSMENT_TO_NEW_AE_VTS_LINK,
            self::APPEAL_AGAINST_VT30_ISSUE,
            self::APPEAL_AGAINST_VT20_ISSUE,
            self::SCHEDULED_VTS_VISIT,
            self::TARGETTED_VTS_VISIT,
            self::TARGETED_RE_INSPECTION,
            self::MOT_COMPLIANCE_SURVEY,
            self::DEMONSTRATION_TEST,
            self::VT7,
            self::USER_CLAIMS_ACCOUNT,
            self::ROLE_ASSOCIATION_CHANGE,
            self::USER_RECLAIMS_ACCOUNT,
            self::USER_ACCOUNT_RESET,
            self::GROUP_A_TESTER_QUALIFICATION,
            self::GROUP_B_TESTER_QUALIFICATION,
            self::DVSA_ADMINISTRATOR_CREATE_AE,
            self::DVSA_ADMINISTRATOR_CREATE_SITE,
            self::UPDATE_AE,
            self::UNLINK_AE_SITE,
            self::DVSA_ADMINISTRATOR_LINK_A_SITE_TO_AN_AE,
            self::DVSA_ADMINISTRATOR_UPDATE_SITE,
            self::INTELLIGENCE_MARKER,
            self::VTS_COMPLAINT,
            self::TESTER_TRAINING_ASSESSMENT,
            self::NTT_DIRECTED_RETRAINING_CLASSES_12,
            self::NTT_REFRESHER_CLASSES_12,
            self::UPDATE_SITE_ASSESSMENT_RISK_SCORE,
        ];
    }

    /**
     * @param mixed $key a candidate EventTypeCode value
     *
     * @return true if $key is in the list of known values for the type
     */
    public static function exists($key)
    {
        return in_array($key, self::getAll(), true);
    }
}
