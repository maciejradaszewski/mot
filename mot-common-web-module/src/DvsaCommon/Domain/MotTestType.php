<?php

namespace DvsaCommon\Domain;

use DvsaCommon\Enum\MotTestTypeCode;

/**
 * Mot test type model.
 *
 * @see \DvsaCommon\Enum\MotTestTypeCode
 * @Deprecated
 */
class MotTestType
{
    /**
     * @param string $testTypeCode
     *
     * @return bool
     */
    public static function isDemo($testTypeCode)
    {
        return in_array($testTypeCode, self::getDemoTypes(), false);
    }

    private static function getDemoTypes()
    {
        return [
            MotTestTypeCode::ROUTINE_DEMONSTRATION_TEST,
            MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING,
        ];
    }

    /**
     * @param string $testTypeCode
     *
     * @return bool
     */
    public static function isSlotConsuming($testTypeCode)
    {
        return in_array($testTypeCode, self::getSlotConsumingTypes(), true);
    }

    public static function getSlotConsumingTypes()
    {
        return [
            MotTestTypeCode::NORMAL_TEST,
            MotTestTypeCode::RE_TEST,
            MotTestTypeCode::PARTIAL_RETEST_LEFT_VTS,
            MotTestTypeCode::PARTIAL_RETEST_REPAIRED_AT_VTS,
        ];
    }

    /**
     * @param string $testTypeCode
     *
     * @return bool
     *
     */
    public static function isReinspection($testTypeCode)
    {
        return in_array($testTypeCode, self::getReinspectionTypes(), true);
    }

    /**
     * @return MotTestType[]
     */
    private static function getReinspectionTypes()
    {
        return [
            MotTestTypeCode::TARGETED_REINSPECTION,
            MotTestTypeCode::MOT_COMPLIANCE_SURVEY,
            MotTestTypeCode::INVERTED_APPEAL,
            MotTestTypeCode::STATUTORY_APPEAL,
            MotTestTypeCode::OTHER,
        ];
    }

    /**
     * Normal tests that are conducted by testers at sites.
     *
     * @param $testTypeCode
     *
     * @return bool
     */
    public static function isStandard($testTypeCode)
    {
        return in_array($testTypeCode, self::getStandardTypes(), true);
    }

    /**
     * @return MotTestType[]
     */
    private static function getStandardTypes()
    {
        return [
            MotTestTypeCode::NORMAL_TEST,
            MotTestTypeCode::RE_TEST,
        ];
    }

    /**
     * @param $testTypeCode
     *
     * @return bool
     */
    public static function isNonMotTypes($testTypeCode)
    {
        return in_array($testTypeCode, self::getNonMotTypes(), true);
    }

    /**
     * @return MotTestType[]
     */
    private static function getNonMotTypes()
    {
        return [
            MotTestTypeCode::NON_MOT_TEST,
        ];
    }


    /**
     * Is Type should generate VT32 report (Advisory)
     *
     * @param $testTypeCode
     *
     * @return bool
     */
    public static function isVeAdvisory($testTypeCode)
    {
        $veAdvisoryTypes = [
            MotTestTypeCode::TARGETED_REINSPECTION => 1,
            MotTestTypeCode::MOT_COMPLIANCE_SURVEY => 1,
        ];

        return !empty($veAdvisoryTypes[$testTypeCode]);
    }
}
