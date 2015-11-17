<?php
/**
 * This file is part of the DVSA MOT Common project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaCommonTest\Validation;

use DateTimeImmutable;
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
        return [
            // FAIL: Site id is missing
            [
                'site', ['site_id' => null], false, 'you must choose a site',
            ],
            // FAIL: Site id is empty
            [
                'site', ['site_id' => ''], false, 'you must choose a site',
            ],
            // FAIL: Site id not numeric
            [
                'site', ['site_id' => '123ABC'], false, 'you must choose a site',
            ],
            // PASS
            [
                'site', ['site_id' => '123'], true,
            ],
        ];
    }

    /**
     * @return array
     */
    public function dateDataProvider()
    {
        $now = new DateTimeImmutable();
        $threeMonthsAgo = new DateTimeImmutable('-3 months');

        return [
            // FAIL: Date not provided
            [
                'date', [], false, 'you must enter a date',
            ],
            // FAIL: Date fields are null
            [
                'date', [
                    'performed_at_year'  => null,
                    'performed_at_month' => null,
                    'performed_at_day'   => null,
                ], false, 'you must enter a date',
            ],
            // FAIL: Date fields are empty
            [
                'date', [
                    'performed_at_year'  => '',
                    'performed_at_month' => '',
                    'performed_at_day'   => '',
                ], false, 'you must enter a date',
            ],
            // FAIL: Date fields don't match format Y-m-d
            [
                'date', [
                    'performed_at_year'  => '15',
                    'performed_at_month' => '11',
                    'performed_at_day'   => '16',
                ], false, 'must be a valid date',
            ],
            // FAIL: Older that 3 months ago
            [
                'date', [
                    'performed_at_year'   => $threeMonthsAgo->format('Y'),
                    'performed_at_month'  => $threeMonthsAgo->format('m'),
                    'performed_at_day'    => $threeMonthsAgo->format('d'),
                    'performed_at_hour'   => $threeMonthsAgo->format('g'),
                    'performed_at_minute' => $threeMonthsAgo->format('i'),
                    'performed_at_am_pm'  => $threeMonthsAgo->format('a'),
                ], false, 'must be less than 3 months ago',
            ],
            // PASS: Date valid but time not provided
            [
                'date', [
                    'performed_at_year'   => $now->format('Y'),
                    'performed_at_month'  => $now->format('m'),
                    'performed_at_day'    => $now->format('d'),
                ], true,
            ],
            // PASS
            [
                'date', [
                    'performed_at_year'   => $now->format('Y'),
                    'performed_at_month'  => $now->format('m'),
                    'performed_at_day'    => $now->format('d'),
                    'performed_at_hour'   => $now->format('g'),
                    'performed_at_minute' => $now->format('i'),
                    'performed_at_am_pm'  => $now->format('a'),
                ], true,
            ],
        ];
    }

    /**
     * @return array
     */
    public function timeDataProvider()
    {
        $now = new DateTimeImmutable();

        return [
            // FAIL: Time not provided
            [
                'time', [], false, 'you must enter a time',
            ],
            // FAIL: Time fields are null
            [
                'time', [
                    'performed_at_year'  => null,
                    'performed_at_month' => null,
                    'performed_at_day'   => null,
                ], false, 'you must enter a time',
            ],
            // FAIL: Time fields are empty
            [
                'time', [
                    'performed_at_hour'   => '',
                    'performed_at_minute' => '',
                    'performed_at_am_pm'  => '',
                ], false, 'you must enter a time',
            ],
            // FAIL: Time fields don't match format g:ia
            [
                'time', [
                    'performed_at_hour'   => '17',
                    'performed_at_minute' => '57',
                    'performed_at_am_pm'  => 'pm',
                ], false, 'must be a valid time',
            ],
            // PASS
            [
                'time', [
                    'performed_at_year'   => $now->format('Y'),
                    'performed_at_month'  => $now->format('m'),
                    'performed_at_day'    => $now->format('d'),
                    'performed_at_hour'   => $now->format('g'),
                    'performed_at_minute' => $now->format('i'),
                    'performed_at_am_pm'  => $now->format('a'),
                ], true,
            ],
        ];
    }

    /**
     * @return array
     */
    public function reasonDataProvider()
    {
        return [
            // FAIL: Reason is missing
            [
                'reason', [], false, 'you must choose a reason',
            ],
            // FAIL: Reason is null
            [
                'reason', ['reason_code' => null], false, 'you must choose a reason',
            ],
            // FAIL: Reason is empty
            [
                'reason', ['reason_code' => ''], false, 'you must choose a reason',
            ],
            // FAIL: Reason not a valid code
            [
                'reason', ['reason_code' => 'XX'], false, 'you must choose a reason',
            ],
            // PASS
            [
                'reason', ['reason_code' => EmergencyReasonCode::COMMUNICATION_PROBLEM], true, ],
            // PASS
            [
                'reason', ['reason_code' => EmergencyReasonCode::SYSTEM_OUTAGE], true,
            ],
            // PASS
            [
                'reason', ['reason_code' => EmergencyReasonCode::OTHER], true,
            ],
        ];
    }

    /**
     * @return array
     */
    public function otherReasonDataProvider()
    {
        return [
            // FAIL: Other reason text is empty (and reason code is OT)
            [
                'otherReasonText', [
                    'reason_code'       => EmergencyReasonCode::OTHER,
                    'other_reason_text' => null,
                ], false, 'you must enter a reason',
            ],
            // FAIL: Other reason text is empty (and reason code is OT)
            [
                'otherReasonText', [
                    'reason_code'       => EmergencyReasonCode::OTHER,
                    'other_reason_text' => '',
                ], false, 'you must enter a reason',
            ],
            // FAIL: Other reason text is less than 6 characters (and reason code is OT)
            [
                'otherReasonText', [
                    'reason_code'       => EmergencyReasonCode::OTHER,
                    'other_reason_text' => '12345',
                ], false, 'must be longer than 5 characters',
            ],
            // PASS: Other reason text is more than 5 characters (and reason code is OT)
            [
                'otherReasonText', [
                    'reason_code'       => EmergencyReasonCode::OTHER,
                    'other_reason_text' => '123456',
                ], true,
            ],
            // PASS: Other reason text missing (and reason code not OT)
            [
                'otherReasonText', [
                    'reason_code'       => EmergencyReasonCode::COMMUNICATION_PROBLEM,
                ], true,
            ],
            // PASS: Other reason text null (and reason code not OT)
            [
                'otherReasonText', [
                    'reason_code'       => EmergencyReasonCode::COMMUNICATION_PROBLEM,
                    'other_reason_text' => null,
                ], true,
            ],
            // PASS: Other reason text empty (and reason code not OT)
            [
                'otherReasonText', [
                    'reason_code'       => EmergencyReasonCode::COMMUNICATION_PROBLEM,
                    'other_reason_text' => '',
            ], true,
            ],
        ];
    }

    /**
     * @return array
     */
    public function contingencyCodeDataProvider()
    {
        return [
            // FAIL: contingency code is missing
            [
                'contingencyCode', ['contingency_code' => null], false, 'you must enter a contingency code',
            ],
            // FAIL: contingency code is empty
            [
                'contingencyCode', ['contingency_code' => ''], false, 'you must enter a contingency code',
            ],
            // PASS
            [
                'contingencyCode', ['contingency_code' => '12345A'], true,
            ],
        ];
    }
}
