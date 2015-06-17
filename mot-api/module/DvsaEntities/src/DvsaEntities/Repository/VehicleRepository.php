<?php

namespace DvsaEntities\Repository;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\Tools\Pagination\Paginator;
use DvsaEntities\Entity\Vehicle;

/**
 * Class VehicleRepository
 * @method Vehicle get(int $id)
 * @method Vehicle|null find($id, $lockMode = LockMode::NONE, $lockVersion = null)
 * @codeCoverageIgnore
 */
class VehicleRepository extends AbstractVehicleRepository
{

    public function getAll()
    {
        return $this->findAll();
    }

    /**
     * This function allow us to paginate all the database to avoid memory limit
     *
     * @param int $start
     * @param int $offset
     * @param string $orderBy
     * @param int $hydrateMode
     * @return array
     */
    public function getAllDataForEsIngestion($start, $offset, $orderBy = 'vehicle.id', $hydrateMode = \Doctrine\ORM\Query::HYDRATE_OBJECT)
    {
        $qb = $this
            ->createQueryBuilder('vehicle')
            ->orderBy($orderBy);

        $paginate = new Paginator($qb, $fetchJoinCollection = true);
        $paginate
            ->getQuery()
            ->setFirstResult($start)
            ->setMaxResults($offset)
            ->setHydrationMode($hydrateMode);

        return $paginate;
    }

    /**
     * @param $vin
     * @param $reg
     * @param $similarCharacterMapping
     * @param $limit
     * @return Vehicle[]
     */
    public function fuzzySearch($vin, $reg, $similarCharacterMapping, $limit)
    {
        $qb = $this->createQueryBuilder("v")
            ->addSelect([ "vc", "md", "mk", "mdd", "sc", "ft", "pc", "bt", "tt"])
            ->leftJoin("v.vehicleClass", "vc")
            ->leftJoin("v.model", "md")
            ->leftJoin("md.make", "mk")
            ->leftJoin("v.modelDetail", "mdd")
            ->leftJoin("v.colour", "pc")
            ->leftJoin("v.secondaryColour", "sc")
            ->leftJoin("v.fuelType", "ft")
            ->leftJoin("v.bodyType", "bt")
            ->leftJoin("v.transmissionType", "tt")
        ;

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
            ->select([
                'vehicle', 'model', 'make', 'class', 'colour', 'fuel', 'body',
                'secondary_colour', 'country', 'transmission'
            ])
            ->leftJoin('vehicle.model', 'model')
            ->leftJoin('vehicle.make', 'make')
            ->leftJoin('vehicle.vehicleClass', 'class')
            ->leftJoin('vehicle.colour', 'colour')
            ->leftJoin('vehicle.secondaryColour', 'secondary_colour')
            ->leftJoin('vehicle.fuelType', 'fuel')
            ->leftJoin('vehicle.bodyType', 'body')
            ->leftJoin('vehicle.countryOfRegistration', 'country')
            ->leftJoin('vehicle.transmissionType', 'transmission')
            ->getQuery()
            ->getResult();
    }
}
