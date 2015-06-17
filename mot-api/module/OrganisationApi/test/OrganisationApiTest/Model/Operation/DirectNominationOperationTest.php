<?php

namespace OrganisationApiTest\Model\Operation;

use DvsaCommon\Enum\BusinessRoleStatusCode;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\OrganisationBusinessRoleMap;
use DvsaEntities\Entity\Person;
use OrganisationApi\Model\NominationVerifier;
use OrganisationApi\Model\Operation\DirectNominationOperation;
use OrganisationApi\Service\OrganisationNominationService;

/**
 * unit tests for DirectNominationOperation
 */
class DirectNominationOperationTest extends \PHPUnit_Framework_TestCase
{
    public function testNominate()
    {
        $this->markTestSkipped();
        $directNomination = $this->createDirectNominationOperation();

        $nomination = $directNomination->nominate(
            new Person(),
            new OrganisationBusinessRoleMap()
        );

        $this->assertTrue($nomination->getStatus()->getCode() === BusinessRoleStatusCode::ACCEPTED);
    }

    private function createDirectNominationOperation()
    {
        $em                   = $this->createMock(\Doctrine\ORM\EntityManager::class);
        $verifier             = $this->createMock(NominationVerifier::class);
        $orgNominationService = $this->createMock(OrganisationNominationService::class);

        return new DirectNominationOperation($em, $verifier, $orgNominationService);
    }

    private function createMock($classPath)
    {
        return XMock::of($classPath);
    }
}
