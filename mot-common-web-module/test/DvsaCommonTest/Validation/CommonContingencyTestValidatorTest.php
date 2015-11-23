<?php
/**
 * This file is part of the DVSA MOT Common project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaCommonTest\Validation;

use DateTime;
use DvsaCommon\Enum\EmergencyReasonCode;
use DvsaCommon\Validation\CommonContingencyTestValidator;
use DvsaCommon\Validation\ValidationResult;
use PHPUnit_Framework_TestCase;

class CommonContingencyTestValidatorTest extends PHPUnit_Framework_TestCase
{
    public function testValidateReturnsValidationResultInstance()
    {
        $validator = new CommonContingencyTestValidator();
        $validationResult = $validator->validate([]);
        $this->assertInstanceOf(ValidationResult::class, $validationResult);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testInputGroup($group, array $data, $valid, $message = null)
    {
        $validator = new CommonContingencyTestValidator();
        $result = $validator->validate($data);
        $messages = $result->getFlattenedMessages();

        if (false === $valid) {
            $this->assertArrayHasKey($group, $messages);
            $this->assertNotEmpty($messages[$group]);
            $this->assertEquals($message, $messages[$group]);
            $this->assertFalse($result->isValid());
        } else {
            $this->assertArrayNotHasKey($group, $messages);
        }
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return array_merge(
            $this->siteDataProvider(),
            $this->dateDataProvider(),
            $this->timeDataProvider(),
            $this->reasonDataProvider(),
            $this->otherReasonDataProvider(),
            $this->contingencyCodeDataProvider()
        );
    }

    /**
     * @return array
     */
    public function siteDataProvider()
    {
        return $this->prependFieldsetToDataProvider(CommonContingencyTestValidator::FIELDSET_SITE, [
            // FAIL: Site id is missing
            [
                ['site_id' => null], false, CommonContingencyTestValidator::MESSAGE_MUST_CHOOSE_A_SITE,
            ],
            // FAIL: Site id is empty
            [
                ['site_id' => ''], false, CommonContingencyTestValidator::MESSAGE_MUST_CHOOSE_A_SITE,
            ],
            // FAIL: Site id not numeric
            [
                ['site_id' => '123ABC'], false, CommonContingencyTestValidator::MESSAGE_MUST_CHOOSE_A_SITE,
            ],
            // PASS
            [
                ['site_id' => '123'], true,
            ],
        ]);
    }

    /**
     * @return array
     */
    public function dateDataProvider()
    {
        $now = new DateTime();
        $threeMonthsAgo = new DateTime('-3 months');
        $future = new DateTime('+1 day');

        return $this->prependFieldsetToDataProvider(CommonContingencyTestValidator::FIELDSET_DATE, [
            // FAIL: Date not provided
            [
                [], false, CommonContingencyTestValidator::MESSAGE_MUST_ENTER_A_DATE,
            ],
            // FAIL: Date fields are null
            [
                [
                    'performed_at_year'  => null,
                    'performed_at_month' => null,
                    'performed_at_day'   => null,
                ], false, CommonContingencyTestValidator::MESSAGE_MUST_ENTER_A_DATE,
            ],
            // FAIL: Date fields are empty
            [
                [
                    'performed_at_year'  => '',
                    'performed_at_month' => '',
                    'performed_at_day'   => '',
                ], false, CommonContingencyTestValidator::MESSAGE_MUST_ENTER_A_DATE,
            ],
            // FAIL: Date fields don't match format Y-m-d (wrong year format)
            [
                [
                    'performed_at_year'  => '15',
                    'performed_at_month' => '11',
                    'performed_at_day'   => '16',
                ], false, CommonContingencyTestValidator::MESSAGE_MUST_BE_VALID_DATE,
            ],
            // FAIL: Date fields don't match format Y-m-d (day without leading zero)
            [
                [
                    'performed_at_year'  => '2015',
                    'performed_at_month' => '11',
                    'performed_at_day'   => '1',
                ], false, CommonContingencyTestValidator::MESSAGE_MUST_BE_VALID_DATE,
            ],
            // FAIL: Older that 3 months ago
            [
                [
                    'performed_at_year'   => $threeMonthsAgo->format('Y'),
                    'performed_at_month'  => $threeMonthsAgo->format('m'),
                    'performed_at_day'    => $threeMonthsAgo->format('d'),
                    'performed_at_hour'   => $threeMonthsAgo->format('g'),
                    'performed_at_minute' => $threeMonthsAgo->format('i'),
                    'performed_at_am_pm'  => $threeMonthsAgo->format('a'),
                ], false, CommonContingencyTestValidator::MESSAGE_MUST_BE_LESS_THAN_3_MONTHS,
            ],
            // FAIL: Date in the future
            [
                [
                    'performed_at_year'   => $future->format('Y'),
                    'performed_at_month'  => $future->format('m'),
                    'performed_at_day'    => $future->format('d'),
                    'performed_at_hour'   => $future->format('g'),
                    'performed_at_minute' => $future->format('i'),
                    'performed_at_am_pm'  => $future->format('a'),
                ], false, CommonContingencyTestValidator::MESSAGE_DATE_NOT_IN_THE_FUTURE,
            ],
            // PASS: Date valid but time not provided
            [
                [
                    'performed_at_year'   => $now->format('Y'),
                    'performed_at_month'  => $now->format('m'),
                    'performed_at_day'    => $now->format('d'),
                ], true,
            ],
            // PASS: Date valid but time not valid (part 1)
            [
                [
                    'performed_at_year'   => $now->format('Y'),
                    'performed_at_month'  => $now->format('m'),
                    'performed_at_day'    => $now->format('d'),
                    'performed_at_hour'   => 'A',
                    'performed_at_minute' => 'B',
                    'performed_at_am_pm'  => 'C',
                ], true,
            ],
            // PASS: Date valid but time not valid (part 2)
            [
                [
                    'performed_at_year'   => $now->format('Y'),
                    'performed_at_month'  => $now->format('m'),
                    'performed_at_day'    => $now->format('d'),
                    'performed_at_hour'   => '23',
                    'performed_at_minute' => '01',
                    'performed_at_am_pm'  => 'am',
                ], true,
            ],
            // PASS
            [
                [
                    'performed_at_year'   => $now->format('Y'),
                    'performed_at_month'  => $now->format('m'),
                    'performed_at_day'    => $now->format('d'),
                    'performed_at_hour'   => $now->format('g'),
                    'performed_at_minute' => $now->format('i'),
                    'performed_at_am_pm'  => $now->format('a'),
                ], true,
            ],
        ]);
    }

    /**
     * @return array
     */
    public function timeDataProvider()
    {
        $now = new DateTime();

        return $this->prependFieldsetToDataProvider(CommonContingencyTestValidator::FIELDSET_TIME, [
            // FAIL: Time not provided
            [
                [], false, CommonContingencyTestValidator::MESSAGE_MUST_ENTER_A_TIME,
            ],
            // FAIL: Time fields are null
            [
                [
                    'performed_at_year'  => null,
                    'performed_at_month' => null,
                    'performed_at_day'   => null,
                ], false, CommonContingencyTestValidator::MESSAGE_MUST_ENTER_A_TIME,
            ],
            // FAIL: Time fields are empty
            [
                [
                    'performed_at_hour'   => '',
                    'performed_at_minute' => '',
                    'performed_at_am_pm'  => '',
                ], false, CommonContingencyTestValidator::MESSAGE_MUST_ENTER_A_TIME,
            ],
            // FAIL: Time fields don't match format g:ia (part 1)
            [
                [
                    'performed_at_hour'   => '17',
                    'performed_at_minute' => '57',
                    'performed_at_am_pm'  => 'pm',
                ], false, CommonContingencyTestValidator::MESSAGE_MUST_BE_VALID_TIME,
            ],
            // FAIL: Time fields don't match format g:ia (part 2)
            [
                [
                    'performed_at_hour'   => '00',
                    'performed_at_minute' => '01',
                    'performed_at_am_pm'  => 'pm',
                ], false, CommonContingencyTestValidator::MESSAGE_MUST_BE_VALID_TIME,
            ],
            // FAIL: Time fields don't match format g:ia (part 3)
            [
                [
                    'performed_at_hour'   => '00',
                    'performed_at_minute' => '01',
                    'performed_at_am_pm'  => 'am',
                ], false, CommonContingencyTestValidator::MESSAGE_MUST_BE_VALID_TIME,
            ],
            // PASS
            [
                [
                    'performed_at_year'   => $now->format('Y'),
                    'performed_at_month'  => $now->format('m'),
                    'performed_at_day'    => $now->format('d'),
                    'performed_at_hour'   => $now->format('g'),
                    'performed_at_minute' => $now->format('i'),
                    'performed_at_am_pm'  => $now->format('a'),
                ], true,
            ],
        ]);
    }

    /**
     * @return array
     */
    public function reasonDataProvider()
    {
        return $this->prependFieldsetToDataProvider(CommonContingencyTestValidator::FIELDSET_REASON, [
            // FAIL: Reason is missing
            [
                [], false, CommonContingencyTestValidator::MESSAGE_MUST_CHOOSE_A_REASON,
            ],
            // FAIL: Reason is null
            [
                ['reason_code' => null], false, CommonContingencyTestValidator::MESSAGE_MUST_CHOOSE_A_REASON,
            ],
            // FAIL: Reason is empty
            [
                ['reason_code' => ''], false, CommonContingencyTestValidator::MESSAGE_MUST_CHOOSE_A_REASON,
            ],
            // FAIL: Reason not a valid code
            [
                ['reason_code' => 'XX'], false, CommonContingencyTestValidator::MESSAGE_MUST_CHOOSE_A_REASON,
            ],
            // PASS
            [
                ['reason_code' => EmergencyReasonCode::COMMUNICATION_PROBLEM], true, ],
            // PASS
            [
                ['reason_code' => EmergencyReasonCode::SYSTEM_OUTAGE], true,
            ],
            // PASS
            [
                ['reason_code' => EmergencyReasonCode::OTHER], true,
            ],
        ]);
    }

    /**
     * @return array
     */
    public function otherReasonDataProvider()
    {
        return $this->prependFieldsetToDataProvider(CommonContingencyTestValidator::FIELDSET_OTHER_REASON_TEXT, [
            // FAIL: Other reason text is empty (and reason code is OT)
            [
                [
                    'reason_code'       => EmergencyReasonCode::OTHER,
                    'other_reason_text' => null,
                ], false, 'you must enter a reason',
            ],
            // FAIL: Other reason text is empty (and reason code is OT)
            [
                [
                    'reason_code'       => EmergencyReasonCode::OTHER,
                    'other_reason_text' => '',
                ], false, 'you must enter a reason',
            ],
            // FAIL: Other reason text is less than 6 characters (and reason code is OT)
            [
                [
                    'reason_code'       => EmergencyReasonCode::OTHER,
                    'other_reason_text' => '12345',
                ], false, 'must be longer than 5 characters',
            ],
            // PASS: Other reason text is more than 5 characters (and reason code is OT)
            [
                [
                    'reason_code'       => EmergencyReasonCode::OTHER,
                    'other_reason_text' => '123456',
                ], true,
            ],
            // PASS: Other reason text missing (and reason code not OT)
            [
                [
                    'reason_code'       => EmergencyReasonCode::COMMUNICATION_PROBLEM,
                ], true,
            ],
            // PASS: Other reason text null (and reason code not OT)
            [
                [
                    'reason_code'       => EmergencyReasonCode::COMMUNICATION_PROBLEM,
                    'other_reason_text' => null,
                ], true,
            ],
            // PASS: Other reason text empty (and reason code not OT)
            [
                [
                    'reason_code'       => EmergencyReasonCode::COMMUNICATION_PROBLEM,
                    'other_reason_text' => '',
                ], true,
            ],
        ]);
    }

    /**
     * @return array
     */
    public function contingencyCodeDataProvider()
    {
        return $this->prependFieldsetToDataProvider(CommonContingencyTestValidator::FIELDSET_CONTINGENCY_CODE, [
            // FAIL: contingency code is missing
            [
                ['contingency_code' => null], false, CommonContingencyTestValidator::MESSAGE_MUST_ENTER_CONTINGENCY_CODE,
            ],
            // FAIL: contingency code is empty
            [
                ['contingency_code' => ''], false, CommonContingencyTestValidator::MESSAGE_MUST_ENTER_CONTINGENCY_CODE,
            ],
            // PASS
            [
                ['contingency_code' => '12345A'], true,
            ],
        ]);
    }

    /**
     * @param string $fieldset
     * @param array  $dataset
     *
     * @return array
     */
    private function prependFieldsetToDataProvider($fieldset, array $dataset)
    {
        foreach (array_keys($dataset) as $k) {
            array_unshift($dataset[$k], $fieldset);
        }

        return $dataset;
    }
}
