<?php
namespace OrganisationApiTest\Service;

use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Constants\OrganisationType as OrganisationTypeConst;
use DvsaCommon\Dto\Organisation\OrganisationContactDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\Hydrator;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommonApi\Service\AddressService;
use DvsaCommonApi\Service\ContactDetailsService;
use DvsaCommonApi\Service\Validator\AddressValidator;
use DvsaCommonApi\Service\Validator\ContactDetailsValidator;
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
use DvsaEntities\Entity\PhoneContactType;
use DvsaEntities\Mapper\AddressMapper;
use DvsaEntities\Repository\AuthForAeStatusRepository;
use DvsaEntities\Repository\CompanyTypeRepository;
use DvsaEntities\Repository\OrganisationContactTypeRepository;
use DvsaEntities\Repository\OrganisationRepository;
use DvsaEntities\Repository\OrganisationTypeRepository;
use DvsaEntities\Repository\PersonRepository;
use DvsaEntities\Repository\PhoneContactTypeRepository;
use OrganisationApi\Service\AuthorisedExaminerService;
use OrganisationApi\Service\Mapper\OrganisationMapper;
use OrganisationApi\Service\OrganisationService;
use OrganisationApi\Service\Validator\AuthorisedExaminerValidator;
use PHPUnit_Framework_MockObject_MockObject as MockObj;

/**
 * Class AuthorisedExaminerServiceTest
 *
 * @package OrganisationApiTest\Service
 */
class AuthorisedExaminerServiceTest extends AbstractServiceTestCase
{
    /** @var AuthorisedExaminerService */
    private $authorisedExaminerService;
    /** @var  PersonRepository|MockObj */
    private $mockPersonRepo;
    /** @var  OrganisationRepository|MockObj */
    private $mockOrganisationRepo;
    /** @var  OrganisationTypeRepository|MockObj */
    private $mockOrganisationTypeRepo;
    /** @var  CompanyTypeRepository|MockObj */
    private $mockCompanyTypeRepo;
    /** @var  ContactDetailsService */
    private $contactDetailsService;
    /** @var  OrganisationService|MockObj */
    private $organisationService;
    /** @var  EntityManager|MockObj */
    private $entityManager;
    /** @var  AuthForAeStatusRepository|MockObj */
    private $authForAeRepository;

    /** @var Organisation */
    private $authorisedExaminer;
    /** @var  AuthorisationServiceInterface|MockObj */
    private $mockAuthService;

    public function setUp()
    {
        $this->mockOrganisationTypeRepo = XMock::of(OrganisationTypeRepository::class, ['findOneByName']);
        $this->mockMethod(
            $this->mockOrganisationTypeRepo,
            'findOneByName',
            $this->any(),
            function ($name) {
                return (new OrganisationType())->setName($name);
            }
        );

        $this->mockCompanyTypeRepo = XMock::of(CompanyTypeRepository::class, ['findOneByName']);
        $this->mockMethod(
            $this->mockCompanyTypeRepo,
            'findOneByName',
            $this->any(),
            function ($name) {
                return (new CompanyType())->setName($name);
            }
        );

        $mockOrgContactTypeRepo = XMock::of(OrganisationContactTypeRepository::class, ['getByCode']);
        $this->mockMethod(
            $mockOrgContactTypeRepo,
            'getByCode',
            $this->any(),
            function ($code) {
                return (new OrganisationContactType())->setCode($code);
            }
        );

        $this->mockPersonRepo        = $this->getMockWithDisabledConstructor(PersonRepository::class);
        $this->mockOrganisationRepo  = $this->getMockWithDisabledConstructor(OrganisationRepository::class);
        $this->contactDetailsService = $this->createContactDetailsService();
        $this->organisationService   = XMock::of(OrganisationService::class);
        $this->entityManager         = $this->getMockEntityManager();
        $this->authForAeRepository   = XMock::of(AuthForAeStatusRepository::class);
        $xssFilterMock               = $this->createXssFilterMock();

        $this->mockAuthService = XMock::of(AuthorisationServiceInterface::class);

        $this->authorisedExaminerService = new AuthorisedExaminerService(
            $this->entityManager,
            $this->mockAuthService,
            $this->organisationService,
            $this->contactDetailsService,
            $this->mockOrganisationRepo,
            $this->mockPersonRepo,
            $this->mockOrganisationTypeRepo,
            $this->mockCompanyTypeRepo,
            $mockOrgContactTypeRepo,
            $this->getMockWithDisabledConstructor(AuthorisedExaminerValidator::class),
            new OrganisationMapper(
                $this->mockOrganisationTypeRepo,
                $this->mockCompanyTypeRepo
            ),
            $this->authForAeRepository,
            $xssFilterMock
        );

        $this->authorisedExaminer = $this->buildAuthorisedExaminer();
    }

    public function testGetAuthorisedExaminer()
    {
        // This test doesn't assert anything. It's to check if code is not broken.
        $this->mockOrganisationRepo->expects($this->any())->method('getAuthorisedExaminer')->will(
            $this->returnValue($this->authorisedExaminer)
        );

        $this->authorisedExaminerService->get(1);
    }

    public function testGetAuthorisedExaminersForPerson()
    {
        // This test doesn't assert anything. It's to check if code is not broken.
        $person = $this->getMockWithDisabledConstructor(Person::class);
        $person->expects($this->any())->method('findAuthorisedExaminers')->will(
            $this->returnValue([$this->authorisedExaminer])
        );

        $this->mockPersonRepo->expects($this->any())->method('get')->will(
            $this->returnValue($person)
        );

        $this->authorisedExaminerService->getAuthorisedExaminersForPerson(1);
    }

    public function buildAuthorisedExaminer()
    {
        $organisation = new Organisation();

        $contactDetails = new ContactDetail();
        $contactDetails->setAddress(new Address());

        $registeredCompanyContactType = new OrganisationContactType();
        $correspondenceType = new OrganisationContactType();

        $registeredContact = new OrganisationContact($contactDetails, $registeredCompanyContactType);
        $correspondenceContact = new OrganisationContact($contactDetails, $correspondenceType);

        $organisation->addContact($registeredContact);
        $organisation->addContact($correspondenceContact);

        $authorisedExaminer = new AuthorisationForAuthorisedExaminer();
        $authorisedExaminer->setOrganisation($organisation);
        $organisation->setAuthorisedExaminer($authorisedExaminer);

        return $organisation;
    }

    public function testCreate()
    {
        $this->mockMethod($this->authForAeRepository, 'getByCode', null, new AuthForAeStatus());
        $this->mockMethod($this->organisationService, 'persist', null, new Organisation());

        // This code doesn't assert anything, it checks if code compiles.
        $this->authorisedExaminerService->create($this->getDataWithCorrespondenceContactDetails());
    }

    public function testUpdate()
    {
        $orgId = 99999;

        $orgEntity = new Organisation();
        $orgEntity
            ->setId($orgId)
            ->setName('unit test')
            ->setRegisteredCompanyNumber('utest reg nr')
            ->setTradingAs('unit trading')
            ->setOrganisationType(new OrganisationType())
            ->setContact(
                new ContactDetail(),
                (new OrganisationContactType())->setCode(OrganisationContactTypeCode::CORRESPONDENCE)
            );

        $orgDto = new OrganisationDto();
        $orgDto
            ->setName('unit test')
            ->setRegisteredCompanyNumber('utest reg nr')
            ->setTradingAs('unit trading')
            ->setOrganisationType(OrganisationTypeConst::AUTHORISED_EXAMINER)
            ->setContacts(
                [
                    (new OrganisationContactDto())
                        ->setType(OrganisationContactTypeCode::CORRESPONDENCE)
                ]
            );

        //  --  set permission  --
        $this->assertGrantedAtOrganisation(
            $this->mockAuthService, [PermissionAtOrganisation::AUTHORISED_EXAMINER_UPDATE], $orgId
        );

        //  --  mock    --
        $this->mockMethod($this->mockOrganisationRepo, 'getAuthorisedExaminer', $this->once(), $orgEntity, $orgId);
        $this->mockMethod($this->mockOrganisationRepo, 'save', $this->once(), null, $orgEntity);

        //  --  call & check    --
        $actual = $this->authorisedExaminerService->update($orgId, $orgDto);

        $this->assertSame(['id' => $orgId], $actual);
    }

    public function testHydrateOrganisation()
    {
        $org = $this->authorisedExaminerService->hydrateOrganisation(new Organisation(), self::getData());

        $organisationType = null;
        if ($org->getOrganisationType()) {
            $organisationType = $org->getOrganisationType()->getName();
        }
        $this->assertEquals($org->getName(), ArrayUtils::tryGet(self::getData(), 'organisationName'));
        $this->assertEquals($org->getTradingAs(), ArrayUtils::tryGet(self::getData(), 'tradingAs'));
        $this->assertEquals($organisationType, ArrayUtils::tryGet(self::getData(), 'organisationType'));
        $this->assertEquals(
            $org->getRegisteredCompanyNumber(),
            ArrayUtils::tryGet(self::getData(), 'registeredCompanyNumber')
        );
    }

    public static function getData()
    {
        return [
            "organisationName"                 => "Name",
            "tradingAs"                        => "my name company",
            "authorisedExaminerReference"      => "AE1234556",
            "organisationType"                 => "Examining Body",
            "registeredCompanyNumber"          => "13123123",
            "addressLine1"                     => "qqqq",
            "addressLine2"                     => "qqqq",
            "addressLine3"                     => "Qqqq",
            "town"                             => "qqqq",
            "postcode"                         => "qqqqq",
            "email"                            => "central@isis.com",
            "emailConfirmation"                => "central@isis.com",
            "phoneNumber"                      => "1111111111111",
            "faxNumber"                        => "2222222222",
            AuthorisedExaminerService::FIELD_CORRESPONDENCE_CONTACT_DETAILS_SAME => true,
        ];
    }

    public static function getDataWithCorrespondenceContactDetails()
    {
        return array_merge(
            self::getData(),
            [
                AuthorisedExaminerService::FIELD_CORRESPONDENCE_CONTACT_DETAILS_SAME => false,
                "correspondenceAddressLine1"       => "Perferendis modi quis aut qui",
                "correspondenceAddressLine2"       => "Dolore aut at illum dolorem illum ipsam mol",
                "correspondenceAddressLine3"       => "Exercitationem et tempora sapiente vitae quid",
                "correspondenceTown"               => "Odit quia tempor corrupti quasi Nam ipsum rem do",
                "correspondencePostcode"           => "Quis fugia",
                "correspondenceEmail"              => "wedat@yahoo.com",
                "correspondenceEmailConfirmation"  => "wedat@yahoo.com",
                "correspondencePhoneNumber"        => "+173-78-9018207",
                "correspondenceFaxNumber"          => "+978-77-5435043"
            ]
        );
    }

    public function testGetByNumber()
    {
        $serviceReturn = (new AuthorisationForAuthorisedExaminer())
            ->setOrganisation(new Organisation());

        $aeRepository = XMock::of(AuthorisationForAuthorisedExaminer::class, ['findOneBy']);
        $this->mockMethod($aeRepository, 'findOneBy', $this->once(), $serviceReturn);

        $this->mockMethod($this->entityManager, 'getRepository', $this->once(), $aeRepository);

        $this->assertInstanceOf(OrganisationDto::class, $this->authorisedExaminerService->getByNumber('A-12345'));
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\NotFoundException
     */
    public function testGetByNumberReturnNull()
    {
        $aeRepository = XMock::of(AuthorisationForAuthorisedExaminer::class, ['findOneBy']);
        $this->mockMethod($aeRepository, 'findOneBy', $this->once(), null);

        $this->mockMethod($this->entityManager, 'getRepository', $this->once(), $aeRepository);

        $this->authorisedExaminerService->getByNumber('INVALID');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createXssFilterMock()
    {
        $xssFilterMock = $this
            ->getMockBuilder(XssFilter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $xssFilterMock
            ->method('filter')
            ->will($this->returnArgument(0));
        $xssFilterMock
            ->method('filterMultiple')
            ->will($this->returnArgument(0));

        return $xssFilterMock;
    }

    /**
     * @return ContactDetailsService
     */
    protected function createContactDetailsService()
    {
        $entityManger = $this->getMockEntityManager();

        /** @var PhoneContactTypeRepository|\PHPUnit_Framework_MockObject_MockObject $phoneContactTypeRepository */
        $phoneContactTypeRepository = $this->getMockWithDisabledConstructor(PhoneContactTypeRepository::class);
        $phoneContactTypeRepository
            ->expects($this->any())->method('getByCode')
            ->will($this->returnValue(new PhoneContactType()));

        $addressService = new AddressService(
            $entityManger,
            new Hydrator(),
            new AddressValidator(),
            new AddressMapper()
        );

        $contactDetailsService = new ContactDetailsService(
            $entityManger,
            $addressService,
            $phoneContactTypeRepository,
            new ContactDetailsValidator(new AddressValidator())
        );

        return $contactDetailsService;
    }
}
