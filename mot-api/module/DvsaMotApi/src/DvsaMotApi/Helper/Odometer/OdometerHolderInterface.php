<?php

namespace DvsaMotApi\Helper\Odometer;

use DvsaEntities\Entity\OdometerReading;

/**
 * Interface OdometerHolderInterface
 *
 * @package DvsaMotApi\Helper
 */
interface OdometerHolderInterface
{
    /**
     * @param OdometerReading $odometerReading
     *
     * @return mixed
     */
    public function setOdometerReading($odometerReading);

    /**
     * @return OdometerReading
     */
    public function getOdometerReading();
}
