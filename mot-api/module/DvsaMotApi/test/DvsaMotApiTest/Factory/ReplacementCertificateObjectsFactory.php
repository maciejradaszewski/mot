<?php

namespace DvsaMotApiTest\Factory;

use DvsaCommon\Constants\OdometerReadingResultType;
use DvsaCommon\Constants\OdometerUnit;
use DvsaCommon\Date\DateUtils;
use DvsaEntities\Entity\CertificateChangeDifferentTesterReason;
use DvsaEntities\Entity\ReplacementCertificateDraft;
use DvsaMotApi\Dto\ReplacementCertificateDraftChangeDTO;

/**
 * Class ReplacementCertificateObjectsFactory
 */
class ReplacementCertificateObjectsFactory
{
    /**
     * @return ReplacementCertificateDraft
     */
    public static function replacementCertificateDraft()
    {
        $motTest = MotTestObjectsFactory::motTest();

        return (new ReplacementCertificateDraft())
            ->setCountryOfRegistration(VehicleObjectsFactory::countryOfRegistration(1, "cor"))
            ->setPrimaryColour(VehicleObjectsFactory::colour(2, "R", "red"))
            ->setSecondaryColour(VehicleObjectsFactory::colour(3, "G", "green"))
            ->setExpiryDate(DateUtils::toDate("2014-05-01"))
            ->setMake(VehicleObjectsFactory::make(2, 'BMW', "BMW"))
            ->setModel(VehicleObjectsFactory::model(3, "M3", "M3"))
            ->setOdometerReading(
                MotTestObjectsFactory::odometerReading(1, OdometerUnit::MILES, OdometerReadingResultType::OK)
            )
            ->setVin("vin")
            ->setMotTest($motTest)
            ->setVrm("vrm")
            ->setVehicleTestingStation(MotTestObjectsFactory::vts(1))
            ->setId(123)
            ->setMotTestVersion($motTest->getVersion());
    }

    /**
     * @param $idSeed
     *
     * @return ReplacementCertificateDraftChangeDTO
     */
    public static function fullReplacementCertificateDraftChange($idSeed)
    {
        return self::partialReplacementCertificateDraftChange($idSeed)
            ->setMake(999 + $idSeed)
            ->setModel(999 + $idSeed)
            ->setCountryOfRegistration(999 + $idSeed)
            ->setVin("NEW_VIN")
            ->setVrm("NEW_VRM")
            ->setVtsSiteNumber("NEW_SITE_NUMBER")
            ->setExpiryDate("2014-05-01");
    }

    /**
     * @param $idSeed
     *
     * @return ReplacementCertificateDraftChangeDTO
     */
    public static function partialReplacementCertificateDraftChange($idSeed)
    {
        return ReplacementCertificateDraftChangeDTO::create()

            ->setPrimaryColour("C" + $idSeed)
            ->setSecondaryColour("D" + $idSeed)
            ->setOdometerReading(
                MotTestObjectsFactory::odometerReadingDTO(
                    999 + $idSeed,
                    OdometerUnit::KILOMETERS,
                    OdometerReadingResultType::OK
                )
            );
    }

    /**
     * @param $code
     *
     * @return CertificateChangeDifferentTesterReason
     */
    public static function reasonForDifferentTester($code)
    {
        return (new CertificateChangeDifferentTesterReason())->setCode($code);
    }
}
