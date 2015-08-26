<?php

namespace Dvsa\Mot\Behat\Support\Helper;

use DvsaCommon\Enum\MotTestTypeCode;

class MotTestTypeHelper
{
    protected $testTypes = [
        MotTestTypeCode::NORMAL_TEST                           => 'Normal Test',
        MotTestTypeCode::PARTIAL_RETEST_LEFT_VTS               => 'Partial Retest Left VTS',
        MotTestTypeCode::PARTIAL_RETEST_REPAIRED_AT_VTS        => 'Partial Retest Repaired at VTS',
        MotTestTypeCode::TARGETED_REINSPECTION                 => 'Targeted Reinspection',
        MotTestTypeCode::MOT_COMPLIANCE_SURVEY                 => 'MOT Compliance Survey',
        MotTestTypeCode::INVERTED_APPEAL                       => 'Inverted Appeal',
        MotTestTypeCode::STATUTORY_APPEAL                      => 'Statutory Appeal',
        MotTestTypeCode::OTHER                                 => 'Other',
        MotTestTypeCode::RE_TEST                               => 'Re-Test',
        MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING => 'Demonstration Test following training',
        MotTestTypeCode::ROUTINE_DEMONSTRATION_TEST            => 'Routine Demonstration Test',
        MotTestTypeCode::NON_MOT_TEST                          => 'Non-Mot Test',
    ];

    public function getTestNameByCode($code)
    {
        return $this->testTypes[$code];
    }

    public function getTestCodeByName($name)
    {
        return array_search($name, $this->testTypes);
    }
}
