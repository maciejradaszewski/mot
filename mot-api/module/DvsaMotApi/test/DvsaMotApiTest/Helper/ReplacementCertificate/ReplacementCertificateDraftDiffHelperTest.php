<?php

namespace DvsaMotApiTest\Helper\ReplacementCertificate;

use DvsaCommon\Constants\OdometerReadingResultType;
use DvsaCommon\Constants\OdometerUnit;
use DvsaCommon\Date\DateUtils;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\ReplacementCertificateDraft;
use DvsaMotApi\Helper\ReplacementCertificate\ReplacementCertificateDraftDiffHelper;
use DvsaMotApiTest\Factory\MotTestObjectsFactory;
use DvsaMotApiTest\Factory\VehicleObjectsFactory as VOF;
use DvsaMotApiTest\Factory\VehicleObjectsFactory;
use PHPUnit_Framework_TestCase;

/**
 * Class ReplacementCertificateDraftDiffHelperTest
 */
class ReplacementCertificateDraftDiffHelperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider data
     */
    public function testGetDiff_givenDraft_correctDiffReturned($input, $output)
    {
        $draftEntity = self::mapData($input['draft'], $input['test']);
        $diff = ReplacementCertificateDraftDiffHelper::getDiff($draftEntity);

        $this->assertEquals(0, count(array_diff($diff, $output)), "Incorrect diff detected");
    }

    /**
     * @param array $draftData
     * @param array $testData
     *
     * @return ReplacementCertificateDraft
     */
    private function mapData($draftData, $testData)
    {
        $draft = ReplacementCertificateDraft::create();
        $isDraftKey = self::isKey($draftData);
        $isTestKey = self::isKey($testData);

        if ($isDraftKey('cor')) {
            $draft->setCountryOfRegistration($draftData['cor']);
        }
        if ($isDraftKey('primaryColour')) {
            $draft->setPrimaryColour($draftData['primaryColour']);
        }
        if ($isDraftKey('secondaryColour')) {
            $draft->setSecondaryColour($draftData['secondaryColour']);
        }
        if ($isDraftKey('expiryDate')) {
            $draft->setExpiryDate($draftData['expiryDate']);
        }
        if ($isDraftKey('vts')) {
            $draft->setVehicleTestingStation($draftData['vts']);
        }
        if ($isDraftKey('vin')) {
            $draft->setVin($draftData['vin']);
        }
        if ($isDraftKey('vrm')) {
            $draft->setVrm($draftData['vrm']);
        }
        if ($isDraftKey('odometer')) {
            $draft->setOdometerReading($draftData['odometer']);
        }
        if ($isDraftKey('make')) {
            $draft->setMake($draftData['make']);
        }
        if ($isDraftKey('model')) {
            $draft->setModel($draftData['model']);
        }

        $test = new MotTest();
        if ($isTestKey('cor')) {
            $test->setCountryOfRegistration($testData['cor']);
        }
        if ($isTestKey('primaryColour')) {
            $test->setPrimaryColour($testData['primaryColour']);
        }
        if ($isTestKey('secondaryColour')) {
            $test->setSecondaryColour($testData['secondaryColour']);
        }
        if ($isTestKey('expiryDate')) {
            $test->setExpiryDate($testData['expiryDate']);
        }
        if ($isTestKey('vts')) {
            $test->setVehicleTestingStation($testData['vts']);
        }
        if ($isTestKey('vin')) {
            $test->setVin($testData['vin']);
        }
        if ($isTestKey('vrm')) {
            $test->setRegistration($testData['vrm']);
        }
        if ($isTestKey('odometer')) {
            $test->setOdometerReading($testData['odometer']);
        }
        if ($isTestKey('make')) {
            $test->setMake($testData['make']);
        }
        if ($isTestKey('model')) {
            $test->setModel($testData['model']);
        }

        return $draft->setMotTest($test);
    }

    public static function data()
    {
        return [
            [
                "input"  => [
                    "draft" => [
                        'odometer' => MotTestObjectsFactory::odometerReading(11, OdometerUnit::KILOMETERS),
                        'primaryColour'   => VOF::colour(1)
                    ],
                    "test"  => [
                        'odometer' => MotTestObjectsFactory::odometerReading(11, OdometerUnit::MILES),
                        'primaryColour'   => VOF::colour(1)
                    ]
                ],
                "output" => [
                    'odometerReading'
                ]
            ],
            [
                "input"  => [
                    "draft" => [
                        'odometer' => MotTestObjectsFactory::odometerReading(11, OdometerUnit::MILES),
                        'primaryColour'   => VOF::colour(1)
                    ],
                    "test"  => [
                        'odometer' => MotTestObjectsFactory::odometerReading(11, OdometerUnit::MILES),
                        'primaryColour'   => VOF::colour(1)
                    ]
                ],
                "output" => [
                    'odometerReading'
                ]
            ],
            [
                "input"  => [
                    'draft' => [
                        'cor'             => VOF::countryOfRegistration(1),
                        'primaryColour'   => VOF::colour(1),
                        'secondaryColour' => VOF::colour(2),
                        'expiryDate'      => DateUtils::toDate("2014-05-01"),
                        'make'            => VOF::make(1),
                        'model'           => VOF::model(1),
                        'vts'             => MotTestObjectsFactory::vts(5),
                        'vrm'             => "VRM",
                        'vin'             => 'VIN',
                        'odometer'        => MotTestObjectsFactory::odometerReading(12, OdometerUnit::KILOMETERS)
                    ],
                    'test'  => [
                        'cor'             => VOF::countryOfRegistration(2),
                        'primaryColour'   => VOF::colour(11),
                        'secondaryColour' => VOF::colour(22),
                        'expiryDate'      => DateUtils::toDate("2014-05-02"),
                        'make'            => VOF::make(2),
                        'model'           => VOF::make(2),
                        'vts'             => MotTestObjectsFactory::vts(55),
                        'vrm'             => "XXX",
                        'vin'             => 'YYY',
                        'odometer'        => MotTestObjectsFactory::odometerReading(1212, OdometerUnit::KILOMETERS)
                    ]
                ],
                "output" => [
                    'countryOfRegistration',
                    'primaryColour',
                    'secondaryColour',
                    'make',
                    'model',
                    'expiryDate',
                    'vehicleTestingStation',
                    'odometerReading',
                    'registration',
                    'vin'
                ]
            ]
        ];
    }

    private static function isKey(&$array)
    {
        return function ($key) use (&$array) {
            return array_key_exists($key, $array);
        };
    }
}
