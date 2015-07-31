<?php

namespace SiteApiTest\Service;

use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\MotIdentityInterface;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Constants\FacilityTypeCode;
use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Contact\EmailDto;
use DvsaCommon\Dto\Contact\PhoneDto;
use DvsaCommon\Dto\Site\FacilityDto;
use DvsaCommon\Dto\Site\FacilityTypeDto;
use DvsaCommon\Dto\Site\SiteContactDto;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\Hydrator;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommonApi\Service\AddressService;
use DvsaCommonApi\Service\ContactDetailsService;
use DvsaCommonApi\Service\Exception\BadRequestException;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaCommonApi\Service\Validator\AddressValidator;
use DvsaCommonApi\Service\Validator\ContactDetailsValidator;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\FacilityType;
use DvsaEntities\Entity\PhoneContactType;
use DvsaEntities\Entity\Site;
use DvsaEntities\Entity\SiteContactType;
use DvsaEntities\Entity\SiteType;
use DvsaEntities\Mapper\AddressMapper;
use DvsaEntities\Repository\AuthorisationForTestingMotAtSiteStatusRepository;
use DvsaEntities\Repository\BrakeTestTypeRepository;
use DvsaEntities\Repository\FacilityTypeRepository;
use DvsaEntities\Repository\NonWorkingDayCountryRepository;
use DvsaEntities\Repository\PhoneContactTypeRepository;
use DvsaEntities\Repository\SiteContactTypeRepository;
use DvsaEntities\Repository\SiteRepository;
use DvsaEntities\Repository\SiteTestingDailyScheduleRepository;
use DvsaEntities\Repository\SiteTypeRepository;
use DvsaEntities\Repository\VehicleClassRepository;
use DvsaEventApi\Service\EventService;
use PHPUnit_Framework_MockObject_MockObject as MockObj;
use SiteApi\Service\Mapper\SiteBusinessRoleMapMapper;
use SiteApi\Service\Mapper\SiteMapper;
use SiteApi\Service\Mapper\VtsMapper;
use SiteApi\Service\SiteService;
use DvsaCommon\Auth\Assertion\UpdateVtsAssertion;
use SiteApi\Service\Validator\SiteValidator;

/**
 * SiteServiceTest
 */
class SiteServiceTest extends AbstractServiceTestCase
{
    const SITE_ID = 99999;
    const SITE_NR = 'V12345';

    /** @var SiteService */
    private $siteService;
    /** @var SiteRepository|MockObj */
    private $repository;
    /** @var  SiteTypeRepository|MockObj */
    private $siteTypeRepository;
    /** @var BrakeTestTypeRepository|MockObj */
    private $brakeTestTypeRepo;
    /**@var SiteContactTypeRepository */
    private $siteContactTypeRepository;
    /** @var  AuthorisationServiceInterface|MockObj */
    private $mockAuthService;
    /** @var  FacilityTypeRepository|MockObj */
    private $facilityTypeRepository;
    /** @var  VehicleClassRepository|MockObj */
    private $vehicleClassRepository;
    /** @var  AuthorisationForTestingMotAtSiteStatusRepository|MockObj */
    private $authForTestingMotStatusRepository;
    /** @var  SiteTestingDailyScheduleRepository|MockObj */
    private $siteTestingDailyScheduleRepository;
    /** @var  NonWorkingDayCountryRepository|MockObj */
    private $nonWorkingDayCountryRepository;
    /** @var  MotIdentityInterface|MockObj */
    private $mockIdentity;
    /** @var  EventService|MockObj */
    private $eventService;
    /** @var  Hydrator|MockObj */
    private $mockHydrator;
    /** @var  SiteValidator|MockObj */
    private $validator;

    public function setup()
    {
        $this->repository = $this->getMockWithDisabledConstructor(SiteRepository::class);
        $this->siteTypeRepository = $this->getMockWithDisabledConstructor(SiteTypeRepository::class);
        $this->mockSiteContactTypeRepo();
        $this->brakeTestTypeRepo = $this->getMockWithDisabledConstructor(BrakeTestTypeRepository::class);
        $this->facilityTypeRepository = $this->getMockWithDisabledConstructor(FacilityTypeRepository::class);
        $this->vehicleClassRepository = $this->getMockWithDisabledConstructor(VehicleClassRepository::class);
        $this->authForTestingMotStatusRepository = $this->getMockWithDisabledConstructor(
            AuthorisationForTestingMotAtSiteStatusRepository::class
        );
        $this->siteTestingDailyScheduleRepository = $this->getMockWithDisabledConstructor(
            SiteTestingDailyScheduleRepository::class
        );
        $this->nonWorkingDayCountryRepository = $this->getMockWithDisabledConstructor(
            NonWorkingDayCountryRepository::class
        );

        $mockEm = $this->getMockEntityManager();
        $xssFilterMock = $this->createXssFilterMock();

        $this->mockIdentity = XMock::of(MotIdentityInterface::class);
        $this->eventService = XMock::of(EventService::class);
        $this->mockAuthService = $this->getMockAuthorizationService();
        $updateVtsAssertion = new UpdateVtsAssertion($this->mockAuthService);
        $this->mockHydrator = XMock::of(Hydrator::class);
        $this->validator = XMock::of(SiteValidator::class);

        $this->siteService = new SiteService(
            $mockEm,
            $this->mockAuthService,
            $this->mockIdentity,
            $this->createContactDetailsService(),
            $this->eventService,
            $this->siteTypeRepository,
            $this->repository,
            $this->siteContactTypeRepository,
            $this->brakeTestTypeRepo,
            $this->facilityTypeRepository,
            $this->vehicleClassRepository,
            $this->authForTestingMotStatusRepository,
            $this->siteTestingDailyScheduleRepository,
            $this->nonWorkingDayCountryRepository,
            $xssFilterMock,
            new SiteBusinessRoleMapMapper(new Hydrator()),
            $updateVtsAssertion,
            $this->mockHydrator,
            $this->validator
        );

        $this->mockMethod($this->validator, 'validate', $this->any(), true);
    }

    public function testCreateSiteCodeDoesNotBreak()
    {
        $this->facilityTypeRepository->expects($this->once())
            ->method('getByCode')
            ->willReturn((new FacilityType())->setName('Facility'));

        $this->siteService->create($this->getSiteDto());
    }

    /**
     * @dataProvider dataProviderTestMethodsPermissionsAndResults
     */
    public function testGetDataMethodsPermissionsAndResults($method, $params, $repo, $permissions, $expect)
    {
        /** @var Site $result */
        $result = null;

        if ($repo !== null) {
            $result = $repo['result'];

            $this->repository->expects($this->once())
                ->method($repo['method'])
                ->withConsecutive($repo['params'])
                ->willReturn($result);
        }

        //  --  check permission    --
        if ($permissions !== null) {
            $siteId = ArrayUtils::tryGet($params, 'siteId');
            if ($siteId === null) {
                $siteId = $result->getId();
            }

            $this->assertGrantedAtSite($this->mockAuthService, $permissions, $siteId);
        }

        //  --  set expected exception  --
        if (!empty($expect['exception'])) {
            $exception = $expect['exception'];
            $this->setExpectedException($exception['class'], $exception['message']);
        }

        //  --  call and check result --
        $actual = XMock::invokeMethod($this->siteService, $method, $params);

        $this->assertEquals($expect['result'], $actual);
    }

    public function dataProviderTestMethodsPermissionsAndResults()
    {
        $siteEntity = $this->getSiteEntity();

        $siteDto = (new SiteMapper())->toDto($siteEntity);
        $vtsDto = (new VtsMapper())->toDto($siteEntity);

        $unauthException = [
            'class'   => UnauthorisedException::class,
            'message' => 'You not have permissions',
        ];

        $notFoundExceptionById = [
            'class'   => NotFoundException::class,
            'message' => 'Site ' . self::SITE_ID . ' not found',
        ];

        $notFoundExceptionByNr = [
            'class'   => NotFoundException::class,
            'message' => 'Site ' . self::SITE_NR . ' not found',
        ];

        return [
            //  --  getVehicleTestingStationDataBySiteNumber method --
            [
                'method'      => 'getSiteBySiteNumber',
                'params'      => [
                    'siteNumber' => self::SITE_NR,
                    'isNeedDto'  => false,
                ],
                'repo'        => [
                    'method' => 'findOneBy',
                    'result' => null,
                    'params' => [['siteNumber' => self::SITE_NR]],
                ],
                'permissions' => null,
                'expect'      => [
                    'exception' => $notFoundExceptionByNr,
                ],
            ],
            [
                'method'     => 'getSiteBySiteNumber',
                'params'     => [
                    'siteNumber' => self::SITE_NR,
                    'isNeedDto'  => false,
                ],
                'repo'       => [
                    'method' => 'findOneBy',
                    'result' => $siteEntity,
                    'params' => [['siteNumber' => self::SITE_NR]],
                ],
                'permission' => [],
                'expect'     => [
                    'exception' => $unauthException,
                ],
            ],
            [
                'method'      => 'getSiteBySiteNumber',
                'params'      => [
                    'siteNumber' => self::SITE_NR,
                    'isNeedDto'  => true,
                ],
                'repo'        => [
                    'method' => 'findOneBy',
                    'result' => $siteEntity,
                    'params' => [['siteNumber' => self::SITE_NR]],
                ],
                'permissions' => [PermissionAtSite::VEHICLE_TESTING_STATION_READ],
                'expect'      => [
                    'result' => $vtsDto,
                ],
            ],

            //  --  getVehicleTestingStationData method --
            [
                'method'     => 'getSite',
                'params'     => [
                    'siteId'    => self::SITE_ID,
                    'isNeedDto' => false,
                ],
                'repo'       => null,
                'permission' => [],
                'expect'     => [
                    'exception' => $unauthException,
                ],
            ],
            [
                'method'      => 'getSite',
                'params'      => [
                    'siteId'    => self::SITE_ID,
                    'isNeedDto' => false,
                ],
                'repo'        => [
                    'method' => 'find',
                    'result' => null,
                    'params' => [self::SITE_ID],
                ],
                'permissions' => [PermissionAtSite::VEHICLE_TESTING_STATION_READ],
                'expect'      => [
                    'exception' => $notFoundExceptionById,
                ],
            ],
            [
                'method'      => 'getSite',
                'params'      => [
                    'siteId'    => self::SITE_ID,
                    'isNeedDto' => true,
                ],
                'repo'        => [
                    'method' => 'find',
                    'result' => $siteEntity,
                    'params' => [self::SITE_ID],
                ],
                'permissions' => [PermissionAtSite::VEHICLE_TESTING_STATION_READ],
                'expect'      => [
                    'result' => $vtsDto,
                ],
            ],

            //  --  update Failed --
            [
                'method'      => 'update',
                'params' => [
                    'siteId' => self::SITE_ID,
                    'data'   => [],
                ],
                'repo'        => null,
                'permissions' => [],
                'expect'      => [
                    'exception' => [
                        'class'   => UnauthorisedException::class,
                        'message' => 'Update vts assertion failed',
                    ],
                ],
            ],

            //  --  update   --
            [
                'method'      => 'update',
                'params' => [
                    'siteId' => self::SITE_ID,
                    'data'   => [],
                ],
                'repo'        => null,
                'permissions' => [
                    PermissionAtSite::VTS_UPDATE_NAME,
                    PermissionAtSite::VTS_UPDATE_CORRESPONDENCE_DETAILS,
                    PermissionAtSite::VTS_UPDATE_BUSINESS_DETAILS,
                ],
                'expect'      => [
                    'exception' => [
                        'class'   => BadRequestException::class,
                        'message' => 'Validation errors encountered',
                    ],
                ],
            ],

            //  --  update   --
            [
                'method'      => 'update',
                'params' => [
                    self::SITE_ID,
                    $this->getSitePostData()
                ],
                'repo'        => [
                    'method' => 'get',
                    'result' => $this->getSiteEntity(),
                    'params' => [self::SITE_ID],
                ],
                'permissions' => [
                    PermissionAtSite::VTS_UPDATE_NAME,
                    PermissionAtSite::VTS_UPDATE_CORRESPONDENCE_DETAILS,
                    PermissionAtSite::VTS_UPDATE_BUSINESS_DETAILS,
                ],
                'expect'      => [
                    'result' => [
                        'id' => self::SITE_ID
                    ],
                ],
            ],
        ];
    }

    public function getSiteEntity()
    {
        $siteEntity = new Site();
        $siteEntity
            ->setId(self::SITE_ID)
            ->setSiteNumber(self::SITE_NR)
            ->setType((new SiteType())->setId(1));

        return $siteEntity;
    }

    private function getSiteDto()
    {
        $addressDto = (new AddressDto())
            ->setAddressLine1('addressLine1')
            ->setTown('town')
            ->setPostcode('postcode');
        $emailDto = (new EmailDto())
            ->setEmail('dummy@dummy.com')
            ->setIsPrimary(true);
        $phoneDto = (new PhoneDto())
            ->setNumber('0712345678')
            ->setIsPrimary(true);

        $contactDto = (new SiteContactDto())
            ->setType(SiteContactTypeCode::BUSINESS)
            ->setAddress($addressDto)
            ->setEmails([$emailDto])
            ->setPhones([$phoneDto]);

        $facility = (new FacilityDto())
            ->setName('Facility')
            ->setType((new FacilityTypeDto())->setCode(FacilityTypeCode::ONE_PERSON_TEST_LANE));

        $dto = (new VehicleTestingStationDto())
            ->setName('fantastic name')
            ->addContact($contactDto)
            ->setIsDualLanguage(true)
            ->setTestClasses([1, 2, 3])
            ->setFacilities([$facility]);

        return $dto;
    }

    private function getSitePostData()
    {
        return [
            'name'                       => 'fantastic name',
            'addressLine1'               => 'los santos',
            'town'                       => 'andreas',
            'postcode'                   => 'abs-123',
            'email'                      => 'www@www.pl',
            'phoneNumber'                => '123456789',
            'correspondenceAddressLine1' => 'los santos',
            'correspondenceTown'         => 'andreas',
            'correspondencePostcode'     => 'abs-123',
            'correspondenceEmail'        => 'www@www.pl',
            'correspondencePhoneNumber'  => '123456789',
        ];
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

    private function mockSiteContactTypeRepo()
    {
        $this->siteContactTypeRepository = $this->getMockWithDisabledConstructor(SiteContactTypeRepository::class);

        // SiteContactType param2 = $mock->getByCode(param1)
        $getByCodeValueMap = [
            [
                SiteContactTypeCode::CORRESPONDENCE,
                (new SiteContactType())->setCode(SiteContactTypeCode::CORRESPONDENCE),
            ],
            [
                SiteContactTypeCode::BUSINESS,
                (new SiteContactType())->setCode(SiteContactTypeCode::BUSINESS),
            ],
        ];

        $this->siteContactTypeRepository->expects($this->any())
            ->method('getByCode')
            ->will($this->returnValueMap($getByCodeValueMap));
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
}
