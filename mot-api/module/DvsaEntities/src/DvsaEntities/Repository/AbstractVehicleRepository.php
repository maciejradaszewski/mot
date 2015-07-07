<?php
namespace DvsaEntities\Repository;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaEntities\Entity\MotTest;
use DvsaMotApi\Helper\FuzzySearchRegexHelper as FuzzyHelper;

/**
 * Class AbstractVehicleRepository.
 *
 * @codeCoverageIgnore
 */
abstract class AbstractVehicleRepository extends AbstractMutableRepository
{

    /**
     * @param $id
     *
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     *
     * @return null|object
     */
    public function get($id)
    {
        $vehicle = $this->find($id);
        if ($vehicle === null) {
            throw new NotFoundException($this->getClassName(), $id);
        }

        return $vehicle;
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
        $queryBuilder = $this->createQueryBuilder('vehicle');

        if (!empty($vin)) {
            $preparedVin = $this->sanitize($vin);
            if ($isFullVin) {
                $queryBuilder->andWhere('vehicle.vin = :vin');
                $queryBuilder->setParameter('vin', $preparedVin);
            } else {
                $queryBuilder->andWhere('vehicle.vin LIKE :partialVin');
                $queryBuilder->setParameter('partialVin', '%' . $preparedVin . '%');
            }
        }

        if (!empty($reg)) {
            $queryBuilder->andWhere('vehicle.registration = :reg');
            $queryBuilder->setParameter('reg', $this->sanitize($reg));
        }

        if (is_int($limit)) {
            $queryBuilder->setMaxResults($limit);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param string $vin       VIN number
     * @param string $reg       Registration number
     * @param bool   $isFullVin Indicates whether passed VIN number is full
     * @param int    $limit
     *
     * @return array
     */
    public function searchVehicle($vin, $reg, $isFullVin, $limit = null)
    {
        $queryBuilder = $this->createQueryBuilder('vehicle');

        $preparedVin = $this->sanitize($vin);
        $preparedReg = $this->sanitize($reg);
        $isVinEmpty = is_null($vin) || $vin === '';
        $isRegEmpty = is_null($reg) || $reg === '';
        if ($isFullVin) {
            if ($isVinEmpty) {
                $queryBuilder->andWhere('vehicle.vin IS NULL');
            } else {
                $queryBuilder->andWhere('vehicle.vin = :vin');
                $queryBuilder->setParameter('vin', $preparedVin);
            }
        } else {
            $queryBuilder->andWhere('vehicle.vin = :vin OR vehicle.vin LIKE :partialVin');
            $queryBuilder->setParameter('vin', $preparedVin);
            $queryBuilder->setParameter('partialVin', '%' . $preparedVin);
        }

        if ($isRegEmpty) {
            $queryBuilder->andWhere('vehicle.registration IS NULL');
        } else {
            $queryBuilder->andWhere('vehicle.registration = :reg');
            $queryBuilder->setParameter('reg', $preparedReg);
        }

        if (is_int($limit)) {
            $queryBuilder->setMaxResults($limit);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Sanitize vin or reg.
     *
     * @param $string
     *
     * @return string
     */
    protected function sanitize($string)
    {
        return strtoupper($string);
    }


    /**
     * @param string $alias
     * @param string $vin       VIN number
     * @param string $reg       Registration number
     * @param bool   $isFullVin Indicates whether passed VIN number is full
     * @param int    $limit
     *
     * @return QueryBuilder
     */
    protected function createSearchQueryBuilder($alias, $vin, $reg, $isFullVin, $limit)
    {
        $queryBuilder = $this->createQueryBuilder($alias);

        if (!empty($vin)) {
            $preparedVin = $this->sanitize($vin);
            if ($isFullVin) {
                $queryBuilder->andWhere('vehicle.vin = :vin');
                $queryBuilder->setParameter('vin', $preparedVin);
            } else {
                $queryBuilder->andWhere('vehicle.vin LIKE :partialVin');
                $queryBuilder->setParameter('partialVin', '%' . $preparedVin . '%');
            }
        }

        if (!empty($reg)) {
            $queryBuilder->andWhere('vehicle.registration = :reg');
            $queryBuilder->setParameter('reg', $this->sanitize($reg));
        }

        if (is_null($limit)) {
            $queryBuilder->setMaxResults($limit);
        }

        return $queryBuilder;
    }
}
