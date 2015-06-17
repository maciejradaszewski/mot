<?php

namespace DvsaMotApi\Helper\ReplacementCertificate;

use DvsaCommon\Date\DateTimeApiFormat;
use DvsaEntities\Entity\OdometerReading;
use DvsaEntities\Entity\ReplacementCertificateDraft;

/**
 * Class ReplacementCertificateDraftDiffHelper
 *
 * @package DvsaMotApi\Helper\ReplacementCertificate
 */
class ReplacementCertificateDraftDiffHelper
{

    /**
     * @param OdometerReading $x
     * @param OdometerReading $y
     *
     * @return bool
     */
    private static function areOdometerReadingsEqual(OdometerReading $x, OdometerReading $y)
    {
        return $x->getValue() === $y->getValue()
        && $x->getUnit() === $y->getUnit()
        && $x->getResultType() === $y->getResultType();
    }

    /**
     * Returns a list of properties on a draft that are different
     * from these on a MOT test associaciated with it
     *
     * @param ReplacementCertificateDraft $draft
     *
     * @return array
     */
    public static function getDiff(ReplacementCertificateDraft $draft)
    {
        $data = [];
        $motTest = $draft->getMotTest();
        // colour
        if ($draft->getPrimaryColour()->getId() !== $motTest->getPrimaryColour()->getId()
        ) {
            $data [] = 'primaryColour';
        }
        $draftSecondary = $draft->getSecondaryColour() !== null ? $draft->getSecondaryColour()->getId() : null;
        $testSecondary = $motTest->getSecondaryColour() !== null ? $motTest->getSecondaryColour()->getId() : null;
        if ($draftSecondary !== $testSecondary) {
            $data [] = 'secondaryColour';
        }
        if (!self::areOdometerReadingsEqual($draft->getOdometerReading(), $motTest->getOdometerReading())) {
            $data [] = 'odometerReading';
        }

        if ($draft->getVin() !== $motTest->getVin()) {
            $data [] = 'vin';
        }

        if ($draft->getVrm() !== $motTest->getRegistration()) {
            $data [] = 'registration';
        }


        if ($motTest->getMake() && $motTest->getModel()) {
            $testMakeId = $motTest->getMake() ? $motTest->getMake()->getCode() : null;
            $draftMakeId = $draft->getMake() ? $draft->getMake()->getCode() : null;
            if ($draftMakeId !== $testMakeId) {
                $data [] = 'make';
            }
            $testModelId = $motTest->getModel() ? $motTest->getModel()->getId() : null;
            $draftModelId = $draft->getModel() ? $draft->getModel()->getId() : null;
            if ($draftModelId !== $testModelId) {
                $data [] = 'model';
            }
        } else {
            $testMake = $motTest->getMakeName();
            $draftMake = $draft->getMakeName();
            $testModel = $motTest->getModelName();
            $draftModel = $draft->getModelName();

            if ($draftMake !== $testMake) {
                $data[] = 'make';
            }

            if ($draftModel !== $testModel) {
                $data[] = 'model';
            }
        }


        $testCorId = $motTest->getCountryOfRegistration() ? $motTest->getCountryOfRegistration()->getId() : null;
        $draftCorId = $draft->getCountryOfRegistration() ? $draft->getCountryOfRegistration()->getId() : null;

        if ($draftCorId !== $testCorId) {
            $data [] = 'countryOfRegistration';
        }

        if ($draft->getExpiryDate() && DateTimeApiFormat::date($draft->getExpiryDate())
            !== DateTimeApiFormat::date($motTest->getExpiryDate())
        ) {
            $data [] = 'expiryDate';
        }

        if ($draft->getVehicleTestingStation()
            && ($draft->getVehicleTestingStation()->getId()
                !== $motTest->getVehicleTestingStation()->getId()
            )
        ) {
            $data [] = 'vehicleTestingStation';
        }

        return $data;
    }
}
