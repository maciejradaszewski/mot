<?php

namespace DvsaEntities\Repository;

use DvsaCommon\Enum\AuthorisationForTestingMotStatusCode;
use DvsaEntities\Entity\AuthorisationForTestingMot;
use DvsaEntities\Entity\AuthorisationForTestingMotStatus;
use DvsaEntities\Entity\Person;

/**
 * Class AuthorisationForTestingMotRepository
 *
 * @package DvsaEntities\Repository
 * @codeCoverageIgnore
 */
class AuthorisationForTestingMotRepository extends AbstractMutableRepository
{
    /**
     * @param AuthorisationForTestingMot $entity
     * @return AuthorisationForTestingMot
     */
    public function persist($entity)
    {
        /** @var AuthorisationForTestingMot $result */
        $result = $this->findOneBy(
            [
                'person'       => $entity->getPerson()->getId(),
                'vehicleClass' => $entity->getVehicleClass()->getId()
            ]
        );

        if ($result) {
            $ret = $result->setStatus($entity->getStatus());
        } else {
            $this->getEntityManager()->persist($entity);
            $ret = $entity;
        }
        return $ret;
    }

    public function suspendQualifiedAuthorisationsForPerson(Person $person)
    {
        $suspendedStatus = $this->getEntityManager()->getRepository(AuthorisationForTestingMotStatus::class)
            ->findOneBy(['code' => AuthorisationForTestingMotStatusCode::SUSPENDED]);
        $qualifiedStatus = $this->getEntityManager()->getRepository(AuthorisationForTestingMotStatus::class)
            ->findOneBy(['code' => AuthorisationForTestingMotStatusCode::QUALIFIED]);
        $qualifiedAuthorisations = $this->findBy(['person' => $person, 'status' => $qualifiedStatus]);
        foreach ($qualifiedAuthorisations as $auth) {
            $auth->setStatus($suspendedStatus);
            $this->getEntityManager()->persist($auth);
        }
    }

    public function activateSuspendedAuthorisationsForPerson(Person $person)
    {
        $suspendedStatus = $this->getEntityManager()->getRepository(AuthorisationForTestingMotStatus::class)
            ->findOneBy(['code' => AuthorisationForTestingMotStatusCode::SUSPENDED]);
        $qualifiedStatus = $this->getEntityManager()->getRepository(AuthorisationForTestingMotStatus::class)
            ->findOneBy(['code' => AuthorisationForTestingMotStatusCode::QUALIFIED]);
        $qualifiedAuthorisations = $this->findBy(['person' => $person, 'status' => $suspendedStatus]);
        foreach ($qualifiedAuthorisations as $auth) {
            $auth->setStatus($qualifiedStatus);
            $this->getEntityManager()->persist($auth);
        }
    }
}
