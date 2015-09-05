<?php


namespace DvsaCommonTest\Enum;

use DvsaCommon\Enum\EventTypeCode;

class EventTypeCodeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAll()
    {
        $res = [
            EventTypeCode::MOT_MANAGEMENT_TRAINING,
            EventTypeCode::APPEAL_AGAINST_DISCIPLINARY_ACTION,
            EventTypeCode::CONVICTIONS_MOT_MOTOR_TRADE_CRIMINAL,
            EventTypeCode::DESK_BASED_ASSESSMENT,
            EventTypeCode::DBA_REFERRAL_TO_AREA_OFFICE,
            EventTypeCode::VT6_8_IN_ROLLOUT,
            EventTypeCode::DISCIPLINARY_ACTION,
            EventTypeCode::DIRECTED_SITE_VISIT,
            EventTypeCode::MYSTERY_SHOPPER,
            EventTypeCode::LEVEL_1_ACTION,
            EventTypeCode::MEMO,
            EventTypeCode::APPEAL_DISALLOWED,
            EventTypeCode::APPEALS_BR_APPEAL_INC_FORMAL_WARNING,
            EventTypeCode::APPEALS_BR_APPEAL_REJECT_OUT_OF_TIME,
            EventTypeCode::APPEALS_BR_APPEAL_WITHDRAWN,
            EventTypeCode::APPEALS_BR_APPEAL_UPHELD,
            EventTypeCode::FAILURE_TO_NOTIFY_VTS_CLOSURE_CHANGE_OWNER,
            EventTypeCode::MIGRATED_NTT_CLASSES_UNSPECIFIED,
            EventTypeCode::NTT_INITIAL_CLASSES_3457,
            EventTypeCode::NTT_DIRECTED_RETRAINING_CLASSES_3457,
            EventTypeCode::NTT_MOTORCYCLE,
            EventTypeCode::NTT_REFRESHER_CLASSES_3457,
            EventTypeCode::MIGRATED_NTT_REFRESHER_CLASSES_3457,
            EventTypeCode::LOSS_OF_REPUTE_CONVICTIONS,
            EventTypeCode::REVIEW_OF_FORMAL_WARNING,
            EventTypeCode::SITE_ASSESSMENT,
            EventTypeCode::SPECIAL_INVESTIGATION,
            EventTypeCode::TRANSFER_SITE_ASSESSMENT_TO_NEW_AE_VTS_LINK,
            EventTypeCode::APPEAL_AGAINST_VT30_ISSUE,
            EventTypeCode::APPEAL_AGAINST_VT20_ISSUE,
            EventTypeCode::SCHEDULED_VTS_VISIT,
            EventTypeCode::TARGETTED_VTS_VISIT,
            EventTypeCode::TARGETED_RE_INSPECTION,
            EventTypeCode::MOT_COMPLIANCE_SURVEY,
            EventTypeCode::DEMONSTRATION_TEST,
            EventTypeCode::VT7,
            EventTypeCode::USER_CLAIMS_ACCOUNT,
            EventTypeCode::ROLE_ASSOCIATION_CHANGE,
            EventTypeCode::USER_RECLAIMS_ACCOUNT,
            EventTypeCode::USER_ACCOUNT_RESET,
            EventTypeCode::GROUP_A_TESTER_QUALIFICATION,
            EventTypeCode::GROUP_B_TESTER_QUALIFICATION,
            EventTypeCode::DVSA_ADMINISTRATOR_CREATE_AE,
            EventTypeCode::DVSA_ADMINISTRATOR_CREATE_SITE,
            EventTypeCode::DVSA_ADMINISTRATOR_UPDATE_SITE,
            EventTypeCode::UPDATE_AE,
            EventTypeCode::UNLINK_AE_SITE,
            EventTypeCode::DVSA_ADMINISTRATOR_LINK_A_SITE_TO_AN_AE,
        ];
        $this->assertEquals($res, EventTypeCode::getAll());
    }

    public function testExists()
    {
        $this->assertTrue(EventTypeCode::exists(EventTypeCode::APPEAL_AGAINST_DISCIPLINARY_ACTION));
        $this->assertFalse(EventTypeCode::exists('invalid'));
    }
}
