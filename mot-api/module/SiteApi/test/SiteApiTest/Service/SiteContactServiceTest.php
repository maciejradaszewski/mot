<?php

namespace SiteApiTest\Service;

use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\Assertion\UpdateVtsAssertion;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Dto\Site\SiteContactDto;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaCommon\Utility\Hydrator;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommonApi\Service\AddressService;
use DvsaCommonApi\Service\ContactDetailsService;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaCommonApi\Service\Validator\AddressValidator;
use DvsaCommonApi\Service\Validator\ContactDetailsValidator;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\ContactDetail;
use DvsaEntities\Entity\PhoneContactType;
use DvsaEntities\Entity\Site;
use DvsaEntities\Entity\SiteContact;
use DvsaEntities\Entity\SiteContactType;
use DvsaEntities\Mapper\AddressMapper;
use DvsaEntities\Repository\PhoneContactTypeRepository;
use DvsaEntities\Repository\SiteContactRepository;
use DvsaEntities\Repository\SiteContactTypeRepository;
use PHPUnit_Framework_MockObject_MockObject as MockObj;
use SiteApi\Service\SiteContactService;

/**
 * Class SiteContactServiceTest
 */
class SiteContactServiceTest extends AbstractServiceTestCase
{
    const SITE_ID = 99999;
    const CONTACT_ID = 88888;

    /** @var SiteContactService */
    private $siteContactSrv;
    /** @var SiteContactRepository|MockObj */
    private $mockSiteContactRepo;
    /**@var SiteContactTypeRepository|MockObj */
    private $mockSiteContactTypeRepo;
    /** @var  AuthorisationServiceInterface|MockObj */
    private $mockAuthService;

    public function setup()
    {
        $this->mockSiteContactRepo = XMock::of(SiteContactRepository::class);
        $this->mockSiteContactTypeRepo = XMock::of(SiteContactTypeRepository::class);
        $this->mockAuthService = $this->getMockAuthorizationService();

        $this->siteContactSrv = new SiteContactService(
            $this->getMockEntityManager(),
            $this->createContactDetailsService(),
            $this->createXssFilterMock(),
            new UpdateVtsAssertion($this->mockAuthService)
        );

        XMock::mockClassField($this->siteContactSrv, 'siteContactRepo', $this->mockSiteContactRepo);
        XMock::mockClassField($this->siteContactSrv, 'siteContactTypeRepo', $this->mockSiteContactTypeRepo);
    }

    /**
     * @dataProvider dataProviderTestMethodsPermissionsAndResults
     */
    public function testGetDataMethodsPermissionsAndResults($method, $params, $repoMocks, $permissions, $expect)
    {
        /** @var Site $result */
        $result = null;

        if ($repoMocks !== null) {
            foreach ($repoMocks as $repo) {
                $this->mockMethod(
                    $this->{$repo['class']}, $repo['method'], $this->once(), $repo['result'], $repo['params']
                );
            }
        }

        //  --  check permission    --
        if ($permissions !== null) {
            $this->assertGrantedAtSite($this->mockAuthService, $permissions, $params['siteId']);
        }

        //  --  set expected exception  --
        if (!empty($expect['exception'])) {
            $exception = $expect['exception'];
            $this->setExpectedException($exception['class'], $exception['message']);
        }

        //  --  call and check result --
        $actual = XMock::invokeMethod($this->siteContactSrv, $method, $params);

        $this->assertSame($expect['result'], $actual);
    }

    public function dataProviderTestMethodsPermissionsAndResults()
    {
        $site = (new Site())->setId(self::SITE_ID);
        $siteContactType = (new SiteContactType())
            ->setId(7777)
            ->setCode(SiteContactTypeCode::BUSINESS);

        $siteContact = new SiteContact(new ContactDetail(), $siteContactType, $site);
        $siteContact->setId(self::CONTACT_ID);

        $unauthException = [
            'class'   => UnauthorisedException::class,
            'message' => 'You not have permissions',
        ];

        return [
            //  --  updateContactFromDto method  --
            [
                'method'      => 'updateContactFromDto',
                'params'      => [
                    'siteId' => self::SITE_ID,
                    'dto'    => (new SiteContactDto)->setType(SiteContactTypeCode::BUSINESS),
                ],
                'repo'        => null,
                'permissions' => [],
                'expect'      => [
                    'exception' => $unauthException,
                ],
            ],
            [
                'method'      => 'updateContactFromDto',
                'params'      => [
                    'siteId' => self::SITE_ID,
                    'dto'    => (new SiteContactDto)->setType(SiteContactTypeCode::BUSINESS),
                ],
                'repos'        => [
                    [
                        'class'  => 'mockSiteContactRepo',
                        'method' => 'getHydratedByTypeCode',
                        'params' => [self::SITE_ID, SiteContactTypeCode::BUSINESS],
                        'result' => new NotFoundException('SiteContact'),
                    ],
                ],
                'permissions' => [PermissionAtSite::VTS_UPDATE_BUSINESS_DETAILS],
                'expect'      => [
                    'exception' => [
                        'class'   => NotFoundException::class,
                        'message' => 'SiteContact not found',
                    ],
                ],
            ],
            [
                'method'      => 'updateContactFromDto',
                'params'      => [
                    'siteId' => self::SITE_ID,
                    'dto'    => (new SiteContactDto)->setType(SiteContactTypeCode::BUSINESS),
                ],
                'repos'        => [
                    [
                        'class'  => 'mockSiteContactRepo',
                        'method' => 'getHydratedByTypeCode',
                        'params' => [self::SITE_ID, SiteContactTypeCode::BUSINESS],
                        'result' => $siteContact,
                    ],
                    [
                        'class'  => 'mockSiteContactRepo',
                        'method' => 'save',
                        'params' => [$siteContact->getDetails()],
                        'result' => null,
                    ],
                ],
                'permissions' => [PermissionAtSite::VTS_UPDATE_BUSINESS_DETAILS],
                'expect'      => [
                    'result' => ['id' => self::CONTACT_ID],
                ],
            ],
        ];
    }

    /**
     * @return ContactDetailsService
     */
    private function createContactDetailsService()
    {
        $entityManager = $this->getMockEntityManager();

        /** @var PhoneContactTypeRepository|MockObj $phoneContactTypeRepository */
        $phoneContactTypeRepository = XMock::of(PhoneContactTypeRepository::class);
        $phoneContactTypeRepository
            ->expects($this->any())->method('getByCode')
            ->willReturn(new PhoneContactType());

        $addressService = new AddressService(
            $entityManager,
            new Hydrator(),
            new AddressValidator(),
            new AddressMapper()
        );

        $contactDetailsService = new ContactDetailsService(
            $entityManager,
            $addressService,
            $phoneContactTypeRepository,
            new ContactDetailsValidator(new AddressValidator())
        );

        return $contactDetailsService;
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

        return $xssFilterMock;
    }
}
