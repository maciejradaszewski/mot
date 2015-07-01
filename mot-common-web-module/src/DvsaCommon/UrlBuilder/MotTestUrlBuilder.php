<?php

namespace DvsaCommon\UrlBuilder;

/**
 * Build url related to Mot Test
 */
class MotTestUrlBuilder extends AbstractUrlBuilder
{
    const MOTTEST = 'mot-test[/:motTestNumber]';
    const MOT_TEST_STATUS = '/status';
    const FIND_MOT_TEST_NUMBER = '/find-mot-test-number';

    const RETEST = 'mot-retest[/:id]';
    const DEMO_TEST = 'mot-demo-test';
    const MINIMAL = '/minimal';

    const MOT_VALIDATE_RETEST = 'mot-retest-validate[/:motTestNumber]';
    const SEARCH = 'mot-test-search';
    const REASONS_FOR_REJECTION = '/reasons-for-rejection[/:motTestRfrId]';

    const ODOMETER_READING = '/odometer-reading';
    const ODOMETER_READING_MODIFY_CHECK = '/modify-check';
    const ODOMETER_READING_NOTICES = '/notices';

    const REFUSAL = 'mot-test-refusal[/:id]';

    protected $routesStructure
        = [
            self::MOTTEST   => [
                self::MOT_TEST_STATUS       => '',
                self::FIND_MOT_TEST_NUMBER  => '',
                self::REASONS_FOR_REJECTION => '',
                self::ODOMETER_READING      => [
                    self::ODOMETER_READING_MODIFY_CHECK => '',
                    self::ODOMETER_READING_NOTICES      => '',
                ],
                self::MINIMAL               => '',
            ],
            self::MOT_VALIDATE_RETEST => '',
            self::SEARCH    => '',
            self::RETEST    => '',
            self::DEMO_TEST => '',
            self::REFUSAL   => '',
        ];

    /**
     * @return $this
     */
    public static function motTest($motTestNr = null)
    {
        $url = self::of()
            ->appendRoutesAndParams(self::MOTTEST);

        if ($motTestNr !== null) {
            $url->routeParam('motTestNumber', (int)$motTestNr);
        }

        return $url;
    }

    /**
     * @return $this
     */
    public static function motValidateRetest($motTestNr)
    {
        $url = self::of()
            ->appendRoutesAndParams(self::MOT_VALIDATE_RETEST);

            $url->routeParam('motTestNumber', (int)$motTestNr);

        return $url;
    }

    public static function minimal($motTestNumber)
    {
        return self::of()->motTest($motTestNumber)->appendRoutesAndParams(self::MINIMAL);
    }

    public static function retest()
    {
        return self::of()->appendRoutesAndParams(self::RETEST);
    }

    public static function demoTest()
    {
        return self::of()->appendRoutesAndParams(self::DEMO_TEST);
    }

    public static function motTestStatus($motTestNr)
    {
        return self::motTest($motTestNr)->appendRoutesAndParams(self::MOT_TEST_STATUS);
    }

    /**
     * @return $this
     */
    public static function search()
    {
        return self::of()->appendRoutesAndParams(self::SEARCH);
    }

    public static function reasonForRejection($motTestNr, $rfrId = null)
    {
        return self::motTest($motTestNr)
            ->appendRoutesAndParams(self::REASONS_FOR_REJECTION)
            ->routeParam('motTestRfrId', $rfrId);
    }

    public static function odometerReading($motTestNr)
    {
        return self::motTest($motTestNr)->appendRoutesAndParams(self::ODOMETER_READING);
    }

    public static function odometerReadingModifyCheck($motTestNr)
    {
        return self::odometerReading($motTestNr)->appendRoutesAndParams(self::ODOMETER_READING_MODIFY_CHECK);
    }

    public static function odometerReadingNotices($motTestNr)
    {
        return self::odometerReading($motTestNr)->appendRoutesAndParams(self::ODOMETER_READING_NOTICES);
    }

    public static function findByMotTestIdAndV5c($motTestId, $v5c)
    {
        return self::findMotTestNumber(
            [
                'motTestId' => $motTestId,
                'v5c'       => $v5c,
            ]
        );
    }

    public static function findByMotTestIdAndMotTestNumber($motTestId, $motTestNumber)
    {
        return self::findMotTestNumber(
            [
                'motTestId'     => $motTestId,
                'motTestNumber' => $motTestNumber,
            ]
        );
    }

    private static function findMotTestNumber(array $queryParams)
    {
        return self::motTest()
            ->appendRoutesAndParams(self::FIND_MOT_TEST_NUMBER)
            ->queryParams($queryParams);
    }

    public static function refusal()
    {
        return self::of()->appendRoutesAndParams(self::REFUSAL);
    }
}
