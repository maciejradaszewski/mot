<?php

namespace DvsaEntities\Repository;

use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaEntities\Entity\Model;

/**
 * Class ModelRepository
 * @package DvsaEntities\Repository
 * @codeCoverageIgnore
 */
class ModelRepository extends AbstractMutableRepository
{

    public function getAll()
    {
        return $this->findAll();
    }

    /**
     * @param $id
     *
     * @return Model
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     */
    public function get($id)
    {
        $result = $this->find($id);
        if (empty($result)) {
            throw new NotFoundException("Model", $id);
        }
        return $result;
    }

    /**
     * @param string $makeCode
     * @param string $modelCode
     * @return Model
     * @throws NotFoundException
     */
    public function getByCode($makeCode, $modelCode)
    {
        $result = $this->findOneBy(['makeCode' => $makeCode, 'code' => $modelCode]);
        if (is_null($result)) {
            throw new NotFoundException("Model", $makeCode . '/' . $modelCode);
        }
        return $result;
    }

    /**
     * @param string $makeCode
     * @param string $modelCode
     * @return Model|null
     */
    public function findOneByMakeAndModel($makeCode, $modelCode)
    {
        $query = $this
            ->createQueryBuilder("model")
            ->addSelect(["make"])
            ->innerJoin("model.make", "make")
            ->where("make.code = :makeCode")
            ->andWhere("model.code = :modelCode")
            ->setParameter('makeCode', $makeCode)
            ->setParameter('modelCode', $modelCode)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * @param string $makeCode
     *
     * @return Model[]
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     */
    public function getByMake($makeCode)
    {
        $result = $this->findBy(['makeCode' => $makeCode], ['name' => 'ASC']);
        if (empty($result)) {
            throw new NotFoundException("Model (by make)", $makeCode);
        }
        return $result;
    }

    /**
     * @param $name
     * @param $makeCode
     *
     * @return array
     */
    public function findByNameForMake($name, $makeCode)
    {
        $qb = $this->createQueryBuilder("m");
        $qb
            ->where($qb->expr()->eq("m.makeCode", ":makeCode"))
            ->andWhere($qb->expr()->like("m.name", ":name"))
            ->setParameter(":makeCode", $makeCode)
            ->setParameter(":name", '%' . $name . '%')
        ;

        return $qb->getQuery()->getResult();
    }
}
