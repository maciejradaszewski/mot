<?php

namespace DvsaCommonTest\Mocking\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\LockMode;
use DvsaCommon\Enum\PhoneContactTypeCode;
use DvsaCommon\Exception\NotImplementedException;
use DvsaCommon\Utility\ArrayUtils;
use DvsaEntities\Entity\PhoneContactType;
use DvsaEntities\Repository\PhoneContactTypeRepository;

class PhoneContactTypeRepositoryFake extends PhoneContactTypeRepository
{
    private $types = [];

    public function __construct()
    {
        $personal = (new PhoneContactType())
            ->setCode(PhoneContactTypeCode::PERSONAL)
            ->setId(1);

        $business = (new PhoneContactType())
            ->setCode(PhoneContactTypeCode::BUSINESS)
            ->setId(2);

        $fax = (new PhoneContactType())
            ->setCode(PhoneContactTypeCode::FAX)
            ->setId(3);

        $this->types = [
            PhoneContactTypeCode::PERSONAL => $personal,
            PhoneContactTypeCode::BUSINESS => $business,
            PhoneContactTypeCode::FAX      => $fax,

        ];
    }

    public function getByCode($code)
    {
        return ArrayUtils::get($this->types, $code);
    }


    public function persist($entity)
    {
        throw new NotImplementedException();
    }

    public function save($entity)
    {
        throw new NotImplementedException();
    }

    public function remove($entity)
    {
        throw new NotImplementedException();
    }

    public function flush($entity = null)
    {
        throw new NotImplementedException();
    }

    public function getReference($id)
    {
        throw new NotImplementedException();
    }

    public function createQueryBuilder($alias, $index = null)
    {
        throw new NotImplementedException();
    }

    public function createResultSetMappingBuilder($alias)
    {
        throw new NotImplementedException();
    }

    public function createNamedQuery($queryName)
    {
        throw new NotImplementedException();
    }

    public function createNativeNamedQuery($queryName)
    {
        throw new NotImplementedException();
    }

    public function clear()
    {
        throw new NotImplementedException();
    }

    public function find($id, $lockMode = LockMode::NONE, $lockVersion = null)
    {
        throw new NotImplementedException();
    }

    public function findAll()
    {
        throw new NotImplementedException();
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        throw new NotImplementedException();
    }

    public function findOneBy(array $criteria, array $orderBy = null)
    {
        throw new NotImplementedException();
    }

    protected function getEntityName()
    {
        throw new NotImplementedException();
    }

    public function getClassName()
    {
        throw new NotImplementedException();
    }

    protected function getEntityManager()
    {
        throw new NotImplementedException();
    }

    protected function getClassMetadata()
    {
        throw new NotImplementedException();
    }

    public function matching(Criteria $criteria)
    {
        throw new NotImplementedException();
    }
}
