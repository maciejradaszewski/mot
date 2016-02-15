<?php

namespace SiteApiTest\Service;

use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\Assertion\UpdateVtsAssertion;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Dto\Contact\ContactDto;
use DvsaCommon\Dto\Site\SiteContactDto;
use DvsaCommon\Enum\PhoneContactTypeCode;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaCommon\Utility\Hydrator;
use DvsaCommon\Validator\EmailAddressValidator;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommonApi\Service\AddressService;
use DvsaCommonApi\Service\ContactDetailsService;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaCommonApi\Service\Validator\AddressValidator;
use DvsaCommonApi\Service\Validator\ContactDetailsValidator;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\MethodSpy;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\ContactDetail;
use DvsaEntities\Entity\Email;
use DvsaEntities\Entity\Phone;
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
    /** @var  UpdateVtsAssertion */
    private $updateVtsAssertion;
    /** @var  UpdateVtsAssertion */
    private $mockUpdateVtsAssertion;

    public function setup()
    {
        $this->mockSiteContactRepo = XMock::of(SiteContactRepository::class);
        $this->mockSiteContactTypeRepo = XMock::of(SiteContactTypeRepository::class);
        $this->mockAuthService = $this->getMockAuthorizationService();

        $this->updateVtsAssertion = new UpdateVtsAssertion($this->mockAuthService);
        $this->mockUpdateVtsAssertion = Xmock::of(UpdateVtsAssertion::class);

        $this->siteContactSrv = new SiteContactService(
            $this->getMockEntityManager(),
            $this->createContactDetailsService(),
            $this->createXssFilterMock(),
            $this->updateVtsAssertion
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

    private function createMockSiteContactServiceWithMockedUpdateVtsAssertion()
    {
        $siteContactService = new SiteContactService(
            $this->getMockEntityManager(),
            $this->createContactDetailsService(),
            $this->createXssFilterMock(),
            $this->mockUpdateVtsAssertion
        );
        XMock::mockClassField($siteContactService, 'siteContactRepo', $this->mockSiteContactRepo);
        XMock::mockClassField($siteContactService, 'siteContactTypeRepo', $this->mockSiteContactTypeRepo);

        return $siteContactService;
    }

    private function createSiteContact()
    {
        $site = new Site();

        $contactDetails = new ContactDetail();
        $contactDetails->addEmail(
            (new Email())
                ->setIsPrimary(true)
        );
        $contactDetails->addPhone(
            (new Phone())
                ->setIsPrimary(true)
                ->setContactType(
                    (new PhoneContactType())
                        ->setCode(PhoneContactTypeCode::BUSINESS)
                )
        );

        $siteContactType = new SiteContactType();
        $siteContactType->setCode(SiteContactTypeCode::BUSINESS);

        return new SiteContact($contactDetails, $siteContactType, $site);
    }

    /**
     * @dataProvider dataProvideTestPatchContactFromJson
     */
    public function testPatchContactFromJson($data, $expectedAsserts = null, $expectedException = null)
    {
        $this->siteContactSrv = $this->createMockSiteContactServiceWithMockedUpdateVtsAssertion();
        $siteContact = $this->createSiteContact();
        $this->mockSiteContactRepo->expects($this->any())->method('getHydratedByTypeCode')
            ->willReturn($siteContact);

        if(!empty($expectedException)) {
            $this->setExpectedException($expectedException);
        } else {
            $siteContactRepoSpy = new MethodSpy($this->mockSiteContactRepo, 'save');
        }

        if(is_array($expectedAsserts)) {
            $spies = [];
            foreach($expectedAsserts as $expectedAssert) {
                $spies[]= new MethodSpy($this->mockUpdateVtsAssertion, $expectedAssert);
            }
        }

        $this->siteContactSrv->patchContactFromJson(1, $data);

        if(isset($spies) && is_array($spies)) {
            /** @var MethodSpy $spy */
            foreach($spies as $spy) {
                $this->assertEquals(1, $spy->invocationCount());
            }
        }

        if(isset($siteContactRepoSpy)) {
            /** @var ContactDetail $savedContact */
            $savedContact = $siteContactRepoSpy->paramsForLastInvocation()[0];

            if(array_key_exists('email', $data)) {
                $this->assertEquals($data['email'], $savedContact->getPrimaryEmail()->getEmail());
                $this->assertCount(1, $savedContact->getEmails());
            }

            if(array_key_exists('phone', $data)) {
                $this->assertEquals($data['phone'], $savedContact->getPrimaryPhone()->getNumber());
                $this->assertCount(1, $savedContact->getPhones());
            }

            if(array_key_exists('address', $data)) {
                $address = $data['address'];
                $addressFields= [
                    'addressLine1' => 'getAddressLine1',
                    'addressLine2' => 'getAddressLine2',
                    'addressLine3' => 'getAddressLine3',
                    'postcode' => 'getPostcode',
                    'town' => 'getTown',
                ];

                foreach($addressFields as $field => $getter) {
                    if(array_key_exists($field, $address)) {
                        $this->assertEquals($address[$field], $savedContact->getAddress()->$getter());
                    }
                }
            }
        }
    }

    public function dataProvideTestPatchContactFromJson()
    {
        return [
            //Check if correct asserts are runned depending on data
            [
                'data' => [
                    'email' => 'sitecontactservicetest@' . EmailAddressValidator::TEST_DOMAIN,
                ],
                'expectedAsserts' => [
                    'assertUpdateEmail',
                ],
            ],
            [
                'data' => [
                    'phone' => '100200300',
                ],
                'expectedAsserts' => [
                    'assertUpdatePhone',
                ],
            ],
            [
                'data' => [
                    'address' => [
                        'town' => 'New Town',
                        'postcode' => '10-200',
                        'addressLine1' => 'Streeet'
                    ],
                ],
                'expectedAsserts' => [
                    'assertUpdateAddress'
                ],
            ],
            //Check asserts runned for full dataset
            [
                'data' => [
                    'email' => 'sitecontactservicetest@' . EmailAddressValidator::TEST_DOMAIN,
                    'phone' => '100200300',
                    'address' => [
                        'town' => 'New Town',
                        'postcode' => '10-200',
                        'addressLine1' => 'Streeet'
                    ],
                ],
                'expectedAsserts' => [
                    'assertUpdateEmail',
                    'assertUpdatePhone',
                    'assertUpdateAddress'
                ],
            ],
            //Check if exception is thrown if address is incomplete
            [
                'data' => [
                    'address' => [
                        'town' => 'New Town',
                        'postcode' => '10-200',
                    ],
                ],
                'expectedAsserts' =>
                    []
                ,
                'expectedException' =>
                    'DvsaCommonApi\Service\Exception\RequiredFieldException'
                ,
            ],
        ];
    }
}
