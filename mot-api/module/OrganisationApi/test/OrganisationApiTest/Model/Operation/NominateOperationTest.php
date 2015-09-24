<?php
namespace OrganisationApiTest\Model\Operation;

use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommonApi\Service\Exception\BadRequestException;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\AuthorisationForAuthorisedExaminer;
use DvsaEntities\Entity\Organisation;
use DvsaEntities\Entity\OrganisationBusinessRole;
use DvsaEntities\Entity\OrganisationBusinessRoleMap;
use DvsaEntities\Entity\Person;
use OrganisationApi\Model\NominationVerifier;
use OrganisationApi\Model\Operation\NominateByRequestOperation;
use OrganisationApi\Model\RoleAvailability;
use OrganisationApi\Model\RoleRestriction\AedRestriction;
use OrganisationApi\Model\RoleRestrictionsSet;
use OrganisationApi\Service\OrganisationNominationService;

/**
 * Class AuthorisedExaminerPrincipalServiceTest
 *
 * @package OrganisationApiTest\Model\Operation
 */
class NominateOperationTest extends AbstractServiceTestCase
{
    /**
     * @var OrganisationPositionRepository
     */
    private $positionRepository;

    /**
     * @var NominateByRequestOperation
     */
    private $nominateOperation;
    private $larry;

    /** @var  AuthorisationServiceInterface */
    private $authorizationService;

    public function setUp()
    {
        parent::__construct();
        $this->larry                = new Person();
        $this->authorizationService = $this->getMockWithDisabledConstructor(AuthorisationServiceInterface::class);
        $janitorRequirement         = new AedRestriction($this->authorizationService);
        $roleRestrictionsSet        = new RoleRestrictionsSet([$janitorRequirement]);
        $obrRepository              = XMock::of(\Doctrine\ORM\EntityRepository::class);
        $obrRepository->expects($this->any())->method('findAll')->will($this->returnValue([]));

        $roleAvailability         = new RoleAvailability($roleRestrictionsSet, $this->authorizationService, $obrRepository);
        $nominationVerifier       = new NominationVerifier($roleAvailability);
        $this->positionRepository = $this->getMockWithDisabledConstructor(\Doctrine\ORM\EntityManager::class);
        $nominationServiceMock    = $this->getMockWithDisabledConstructor(OrganisationNominationService::class);
        $this->nominateOperation  = new NominateByRequestOperation(
            $this->positionRepository, $nominationVerifier, $nominationServiceMock
        );
    }

    public function test_adding_existing_nomination_should_fail()
    {
        $this->markTestSkipped();
        $this->positionRepository->expects($this->never())->method('persist');

        $schoolWhereLarryWillWork = $this->buildOrganisationWherePersonIsNominated();
        $existingNomination       = new OrganisationBusinessRoleMap();
        $existingNomination->setPerson($this->larry);
        $existingNomination->setOrganisationBusinessRole(new OrganisationBusinessRole());
        $existingNomination->setOrganisation($schoolWhereLarryWillWork);

        $error = 'No error';
        try {
            $this->nominateOperation->nominate(new Person(), $existingNomination);
        } catch (BadRequestException $e) {
            $error = $e->getErrors()[0]["message"];
        }

        $this->assertEquals(NominationVerifier::ERROR_ALREADY_HAS_NOMINATION, $error);
    }

    public function test_adding_nomination_when_position_exists_should_fail()
    {
        $this->markTestSkipped();
        $this->positionRepository->expects($this->never())->method('persist');

        $schoolWhereLarryWorks = $this->buildOrganisationWherePersonAlreadyWorks();
        $existingPosition      = new OrganisationBusinessRoleMap();

        $error = 'No error';
        try {
            $this->nominateOperation->nominate(new Person(), $existingPosition);
        } catch (BadRequestException $e) {
            $error = $e->getErrors()[0]["message"];
        }

        $this->assertEquals(NominationVerifier::ERROR_ALREADY_HAS_ROLE, $error);
    }

    public function test_adding_nomination_works()
    {
        $this->markTestSkipped();
        $this->positionRepository->expects($this->once())->method('persist');

        $schoolWhereLarryDoesNotWork = $this->buildOrganisationWherePersonDoesNotWork();
        $position                    = new OrganisationBusinessRoleMap();

        $this->nominateOperation->nominate(new Person(), $position);

        // No exceptions
    }

    /**
     * @return Organisation
     */
    private function buildOrganisationWherePersonIsNominated()
    {
        $organisation = new Organisation();
        $nomination   = new OrganisationBusinessRoleMap();
        $organisation->getPositions()->add($nomination);

        return $organisation;
    }

    /**
     * @return Organisation
     */
    private function buildOrganisationWherePersonAlreadyWorks()
    {
        $this->markTestSkipped();
        $organisation = new Organisation();
        $nomination   = new OrganisationBusinessRoleMap();
        $nomination->accept();
        $organisation->getPositions()->add($nomination);

        return $organisation;
    }

    /**
     * @return Organisation
     */
    private function buildOrganisationWherePersonDoesNotWork()
    {
        $organisation = new Organisation();
        $organisation->setAuthorisedExaminer(new AuthorisationForAuthorisedExaminer());

        return $organisation;
    }
}
