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
     * @param $vin
     * @param $reg
     * @param $similarCharacterMapping
     * @param $limit
     * @return DvlaVehicle[]
     */
    public function fuzzySearch($vin, $reg, $similarCharacterMapping, $limit)
    {
        $qb = $this->createQueryBuilder("v");

        $this->addVinCondition($qb, $vin, $similarCharacterMapping);
        $this->addRegCondition($qb, $reg,  $similarCharacterMapping);

        return $qb
            ->getQuery()
            ->setMaxResults($limit)
            ->getResult()
            ;
    }

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
