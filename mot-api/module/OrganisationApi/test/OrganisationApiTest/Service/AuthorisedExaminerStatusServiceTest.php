<?php
namespace OrganisationApiTest\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\MotIdentityInterface;
use DvsaCommon\Date\DateTimeHolder;
use DvsaCommon\Dto\Common\AuthForAeStatusDto;
use DvsaCommon\Dto\Organisation\AuthorisedExaminerAuthorisationDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\AuthorisationForAuthorisedExaminerStatusCode;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\TestCasePermissionTrait;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\AuthForAeStatus;
use DvsaEntities\Entity\AuthorisationForAuthorisedExaminer;
use DvsaEntities\Entity\Organisation;
use DvsaEntities\Repository\AuthForAeStatusRepository;
use DvsaEntities\Repository\OrganisationRepository;
use DvsaEntities\Repository\SiteRepository;
use DvsaEventApi\Service\EventService;
use OrganisationApi\Service\AuthorisedExaminerStatusService;
use OrganisationApi\Service\Validator\AuthorisedExaminerValidator;
use PHPUnit_Framework_MockObject_MockObject as MockObj;

/**
 * Class AuthorisedExaminerStatusServiceTest
 *
 * @package OrganisationApiTest\Service
 */
class AuthorisedExaminerStatusServiceTest extends AbstractServiceTestCase
{
    use TestCasePermissionTrait;

    const AE_ID = 51;
    const AE_USERNAME = 'tester';
    const OLD_STATUS = 'Approved';

    /**
     * @var EntityManager|MockObj
     */
    private $entityManager;
    /**
     * @var AuthorisationServiceInterface|MockObj
     */
    private $authService;
    /**
     * @var MotIdentityInterface|MockObj
     */
    private $identity;
    /**
     * @var EventService|MockObj
     */
    private $eventService;
    /**
     * @var OrganisationRepository|MockObj
     */
    private $organisationRepository;
    /**
     * @var SiteRepository|MockObj
     */
    private $mockSiteRepo;
    /**
     * @var AuthForAeStatusRepository|MockObj
     */
    private $authForAeStatusRepository;
    /**
     * @var XssFilter|MockObj
     */
    private $xssFilter;
    /**
     * @var AuthorisedExaminerValidator|MockObj
     */
    private $validator;

    /**
     * @var AuthorisedExaminerStatusService
     */
    private $service;

    public function setUp()
    {
        $this->entityManager = XMock::of(EntityManager::class);
        $this->authService = XMock::of(AuthorisationServiceInterface::class);
        $this->identity = XMock::of(MotIdentityInterface::class);
        $this->eventService = XMock::of(EventService::class);
        $this->organisationRepository = XMock::of(OrganisationRepository::class);
        $this->authForAeStatusRepository = XMock::of(AuthForAeStatusRepository::class);
        $this->xssFilter = XMock::of(XssFilter::class, ['filter']);
        $this->validator = XMock::of(AuthorisedExaminerValidator::class);
        $this->mockSiteRepo = XMock::of(SiteRepository::class, ['getAllAreaOffices', 'find']);


        $this->service = new AuthorisedExaminerStatusService(
            $this->entityManager,
            $this->authService,
            $this->identity,
            $this->eventService,
            $this->organisationRepository,
            $this->authForAeStatusRepository,
            $this->xssFilter,
            $this->validator,
            new DateTimeHolder(),
            $this->mockSiteRepo
        );

        $aoList = $this->fakedAreaOfficeList();
        $this->mockSiteRepo->expects($this->any())
            ->method('getAllAreaOffices')
            ->willReturn($aoList);

        $this->mockMethod($this->validator, 'validateStatusAndAO', $this->any(), true);
        $this->mockMethod($this->identity, 'getUsername', $this->any(), self::AE_USERNAME);
        $this->mockMethod(
            $this->xssFilter,
            'filter',
            $this->any(),
            function ($dto) {
                return $dto;
            }
        );
    }

    public function testValidateUpdateStatus()
    {
        $this->assertNull($this->service->updateStatus(self::AE_ID, $this->getOrganisation()->setIsValidateOnly(true)));
    }

    public function testUpdateStatus()
    {
        $this->mockMethod(
            $this->organisationRepository,
            'getAuthorisedExaminer',
            $this->once(),
            $this->getOrganisationEntity(),
            self::AE_ID
        );
        $this->mockMethod($this->authForAeStatusRepository, 'getByCode', $this->once(), $this->getAuthForAeStatus());

        $this->assertEquals(
            ['id' => self::AE_ID],
            $this->service->updateStatus(self::AE_ID, $this->getOrganisation())
        );
    }

    private function getAuthForAeStatus()
    {
        return (new AuthForAeStatus());
    }

    private function getOrganisationEntity()
    {
        return (new Organisation())
            ->setId(self::AE_ID)
            ->setAuthorisedExaminer(
                (new AuthorisationForAuthorisedExaminer())
                    ->setStatus(
                        (new AuthForAeStatus())->setName(self::OLD_STATUS)
                    )
            );
    }

    private function getOrganisation()
    {
        return (new OrganisationDto())
            ->setAuthorisedExaminerAuthorisation(
                (new AuthorisedExaminerAuthorisationDto())
                    ->setStatus(
                        (new AuthForAeStatusDto())
                            ->setCode(AuthorisationForAuthorisedExaminerStatusCode::APPROVED)
                    )
            );
    }

    protected function fakedAreaOfficeList()
    {
        return [
            [
                "id" => "3000",
                "name" => "Area Office 01",
                "siteNumber" => "01FOO",
                "areaOfficeNumber" => "01"
            ],
            [
                "id" => "3001",
                "name" => "Area Office 02",
                "siteNumber" => "02BAR",
                "areaOfficeNumber" => "02"
            ]
        ];
    }}
