<?php

namespace DvsaEntities\Repository;

use DvsaEntities\Entity\DvlaVehicle;

/**
 * Class DvlaVehicleRepository
 * @method DvlaVehicle get(int $id)
 * @codeCoverageIgnore
 */
class DvlaVehicleRepository extends AbstractVehicleRepository
{

    /**
     * @param string $vin       VIN number
     * @param string $reg       Registration number
     * @param bool   $isFullVin Indicates whether passed VIN number is full
     * @param int    $limit
     *
     * @return array
     */
    public function search($vin, $reg, $isFullVin, $limit = null)
    {
        return $this->createSearchQueryBuilder('vehicle', $vin, $reg, $isFullVin, $limit)
            ->getQuery()
            ->getResult();
    }
}
