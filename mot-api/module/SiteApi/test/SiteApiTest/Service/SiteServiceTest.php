<?php

namespace SiteApiTest\Service;

use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\Hydrator;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommonApi\Service\AddressService;
use DvsaCommonApi\Service\ContactDetailsService;
use DvsaCommonApi\Service\Exception\BadRequestExceptionWithMultipleErrors;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaCommonApi\Service\Validator\AddressValidator;
use DvsaCommonApi\Service\Validator\ContactDetailsValidator;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\CountryOfRegistration;
use DvsaEntities\Entity\NonWorkingDayCountry;
use DvsaEntities\Entity\PhoneContactType;
use DvsaEntities\Entity\Site;
use DvsaEntities\Entity\SiteContactType;
use DvsaEntities\Entity\SiteType;
use DvsaEntities\Mapper\AddressMapper;
use DvsaEntities\Repository\BrakeTestTypeRepository;
use DvsaEntities\Repository\NonWorkingDayCountryRepository;
use DvsaEntities\Repository\PhoneContactTypeRepository;
use DvsaEntities\Repository\SiteContactTypeRepository;
use DvsaEntities\Repository\SiteRepository;
use DvsaEntities\Repository\SiteTypeRepository;
use PHPUnit_Framework_MockObject_MockObject as MockObj;
use SiteApi\Service\Mapper\SiteBusinessRoleMapMapper;
use SiteApi\Service\Mapper\SiteMapper;
use SiteApi\Service\Mapper\VtsMapper;
use SiteApi\Service\SiteService;
use DvsaCommon\Auth\Assertion\UpdateVtsAssertion;

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
    /** @var  NonWorkingDayCountryRepository|MockObj */
    private $nonWorkingDayCountryRepository;
    /** @var BrakeTestTypeRepository|MockObj */
    private $brakeTestTypeRepo;
    /**@var SiteContactTypeRepository */
    private $siteContactTypeRepository;
    /** @var  AuthorisationServiceInterface|MockObj */
    private $mockAuthService;

    public function setup()
    {
        $this->repository = $this->getMockWithDisabledConstructor(SiteRepository::class);
        $this->siteTypeRepository = $this->getMockWithDisabledConstructor(SiteTypeRepository::class);
        $this->nonWorkingDayCountryRepository = $this->mockNonWorkingDayCountryLookupRepository();
        $this->mockSiteContactTypeRepo();
        $this->brakeTestTypeRepo = $this->getMockWithDisabledConstructor(BrakeTestTypeRepository::class);
        $mockEm = $this->getMockEntityManager();
        $xssFilterMock = $this->createXssFilterMock();

        $this->mockAuthService = $this->getMockAuthorizationService();
        $updateVtsAssertion = new UpdateVtsAssertion($this->mockAuthService);

        $this->siteService = new SiteService(
            $mockEm,
            $this->siteTypeRepository,
            $this->repository,
            $this->siteContactTypeRepository,
            $this->brakeTestTypeRepo,
            $this->nonWorkingDayCountryRepository,
            $this->getMockHydrator(),
            $this->mockAuthService,
            new SiteBusinessRoleMapMapper(
                new Hydrator()
            ),
            $this->createContactDetailsService(),
            $xssFilterMock,
            $updateVtsAssertion
        );
    }

    public function testCreateSiteCodeDoesNotBreak()
    {
        // This code doesn't assert anything, it checks if code compiles.
        $this->siteService->create($this->getSitePostData());
    }

    public function testEditSiteCodeDoesNotBreak()
    {
        //This code doesn't assert anything, it checks if code compiles.
        $this->mockMethod($this->repository, 'get', null, new Site());

        $this->siteService->create($this->getSitePostData(), 1);
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
                'method'      => 'getVehicleTestingStationDataBySiteNumber',
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
                'method'     => 'getVehicleTestingStationDataBySiteNumber',
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
                'method'      => 'getVehicleTestingStationDataBySiteNumber',
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

            //  --  getSiteData method --
            [
                'method'      => 'getSiteData',
                'params'      => [
                    'siteId'    => self::SITE_ID,
                    'isNeedDto' => false,
                ],
                'repo'        => [
                    'method' => 'find',
                    'result' => null,
                    'params' => [self::SITE_ID],
                ],
                'permissions' => null,
                'expect'      => [
                    'exception' => $notFoundExceptionById,
                ],
            ],
            [
                'method'      => 'getSiteData',
                'params'      => [
                    'siteId'    => self::SITE_ID,
                    'isNeedDto' => false,
                ],
                'repo'        => null,
                'permissions' => [],
                'expect'      => [
                    'exception' => $unauthException,
                ],
            ],
            [
                'method'      => 'getSiteData',
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
                    'result' => $siteDto,
                ],
            ],

            //  --  getVehicleTestingStationData method --
            [
                'method'     => 'getVehicleTestingStationData',
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
                'method'      => 'getVehicleTestingStationData',
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
                'method'      => 'getVehicleTestingStationData',
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

            //  --  findVehicleTestingStationsByPartialSiteNumber method  --
            [
                'method'      => 'findVehicleTestingStationsByPartialSiteNumber',
                'params'      => [
                    'partSiteNr' => '_+A9',
                    'maxResult'  => 10,
                ],
                'repo'        => null,
                'permissions' => null,
                'expect'      => [
                    'exception' => [
                        'class'   => BadRequestExceptionWithMultipleErrors::class,
                        'message' => SiteService::SITE_NUMBER_INVALID_DATA_DISPLAY_MESSAGE,
                    ],
                ],
            ],

            //  --  create  --
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

    private function mockNonWorkingDayCountryLookupRepository()
    {
        $mockRepo = XMock::of(NonWorkingDayCountryRepository::class, ["getOneByCode"]);
        $this->mockMethod(
            $mockRepo,
            'getOneByCode',
            null,
            function ($code) {
                $country = new CountryOfRegistration();
                $country->setCode($code);

                $nonWorkingDayCountryLookup = new NonWorkingDayCountry();
                $nonWorkingDayCountryLookup->setCountry($country);

                return $nonWorkingDayCountryLookup;
            }
        );

        return $mockRepo;
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
