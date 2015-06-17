<?php

namespace DvsaMotApi\Helper\Odometer;

use Api\Check\CheckResultExceptionTranslator;
use DvsaCommon\Dto\Common\OdometerReadingDTO;
use DvsaEntities\Entity\OdometerReading;
use DvsaMotApi\Service\Validator\Odometer\OdometerReadingValidator;

/**
 * Class OdometerHolderUpdater
 *
 * @package DvsaMotApi\Helper\Odometer
 */
class OdometerHolderUpdater
{
    /**
     * @var \DvsaMotApi\Service\Validator\Odometer\OdometerReadingValidator
     */
    private $odometerReadingValidator;

    /**
     * @param OdometerReadingValidator $odometerReadingValidator
     */
    public function __construct(OdometerReadingValidator $odometerReadingValidator)
    {
        $this->odometerReadingValidator = $odometerReadingValidator;
    }

    /**
     * @param OdometerHolderInterface $odometerHolder
     * @param OdometerReadingDTO      $readingDTO
     */
    public function update(OdometerHolderInterface $odometerHolder, OdometerReadingDTO $readingDTO)
    {
        $checkResult = $this->odometerReadingValidator->validate($readingDTO);
        CheckResultExceptionTranslator::tryThrowDataValidationException($checkResult);

        $readingEntity = OdometerReading::create()
            ->setValue($readingDTO->getValue())
            ->setUnit($readingDTO->getUnit())
            ->setResultType($readingDTO->getResultType());
        $odometerHolder->setOdometerReading($readingEntity);
    }
}
