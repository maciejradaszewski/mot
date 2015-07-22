<?php
namespace OrganisationApiTest\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Constants\OrganisationType as OrganisationTypeConst;
use DvsaCommon\Dto\Organisation\OrganisationContactDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommonApi\Service\ContactDetailsService;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\Address;
use DvsaEntities\Entity\AuthForAeStatus;
use DvsaEntities\Entity\AuthorisationForAuthorisedExaminer;
use DvsaEntities\Entity\CompanyType;
use DvsaEntities\Entity\ContactDetail;
use DvsaEntities\Entity\Organisation;
use DvsaEntities\Entity\OrganisationContact;
use DvsaEntities\Entity\OrganisationContactType;
use DvsaEntities\Entity\OrganisationType;
use DvsaEntities\Entity\Person;
use DvsaEntities\Repository\AuthForAeStatusRepository;
use DvsaEntities\Repository\AuthorisationForAuthorisedExaminerRepository;
use DvsaEntities\Repository\CompanyTypeRepository;
use DvsaEntities\Repository\OrganisationContactTypeRepository;
use DvsaEntities\Repository\OrganisationRepository;
use DvsaEntities\Repository\OrganisationTypeRepository;
use DvsaEntities\Repository\PersonRepository;
use OrganisationApi\Service\AuthorisedExaminerService;
use OrganisationApi\Service\Mapper\OrganisationMapper;
use OrganisationApi\Service\OrganisationService;
use PHPUnit_Framework_MockObject_MockObject as MockObj;

/**
 * Class OrganisationServiceTest
 *
 * @package OrganisationApiTest\Service
 */
class OrganisationServiceTest extends AbstractServiceTestCase
{
    const ORG_ID = 9;

    /** @var  OrganisationRepository|MockObj */
    private $mockOrganisationRepo;
    /** @var  EntityManager|MockObj */
    private $entityManager;
    /** @var OrganisationService */
    private $service;
    /** @var Organisation */
    private $organisation;

    public function setUp()
    {
        $this->entityManager = XMock::of(EntityManager::class);
        $this->mockOrganisationRepo = XMock::of(OrganisationRepository::class);

        $this->service = new OrganisationService(
            $this->entityManager,
            $this->mockOrganisationRepo,
            new OrganisationMapper()
        );

        $this->organisation = (new Organisation())
            ->setId(self::ORG_ID);
    }

    public function testIncrementSlotBalance()
    {
        $this->mockOrganisationRepo->expects($this->once())
            ->method('updateSlotBalance')
            ->with(self::ORG_ID, 1);

        $this->service->incrementSlotBalance($this->organisation);
    }

    public function testDecrementSlotBalance()
    {
        $this->mockOrganisationRepo->expects($this->once())
            ->method('updateSlotBalance')
            ->with(self::ORG_ID, -1);

        $this->service->decrementSlotBalance($this->organisation);
    }
}
