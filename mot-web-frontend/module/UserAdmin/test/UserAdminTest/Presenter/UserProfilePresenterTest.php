<?php

namespace UserAdminTest\Presenter;

use Application\Service\CatalogService;
use DvsaClient\Entity\TesterAuthorisation;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Person\PersonHelpDeskProfileDto;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilderWeb;
use DvsaCommon\UrlBuilder\VehicleTestingStationUrlBuilderWeb;
use DvsaCommonTest\TestUtils\XMock;
use UserAdmin\Presenter\UserProfilePresenter;
use UserAdmin\ViewModel\UserProfile\TesterAuthorisationViewModel;

/**
 * Unit tests for UserProfilePresenter
 */
class UserProfilePresenterTest extends \PHPUnit_Framework_TestCase
{
    /** @var UserProfilePresenter $presenter */
    private $presenter;

    public function testDisplayInformation()
    {
        $catalogService = XMock::of(CatalogService::class);
        $catalogMockMethod = $this->buildSiteAndOrganisationCatalog();

        $catalogService->expects($this->atLeastOnce())
            ->method("getBusinessRoles")
            ->willReturn($catalogMockMethod);

        $this->presenter = new UserProfilePresenter(
            $this->buildPersonHelpDeskProfileDto(),
            new TesterAuthorisationViewModel(
                1,
                new TesterAuthorisation(),
                XMock::of(MotAuthorisationServiceInterface::class)
            ),
            $catalogService,
            true
        );
        $this->presenter->setPersonId(1);
        $this->assertEquals(1, $this->presenter->getPersonId());
        $this->assertEquals('Username', $this->presenter->displayUserName());
        $this->assertEquals('Mrs Harriet Jones', $this->presenter->displayTitleAndFullName());
        $this->assertEquals('29 May 1992', $this->presenter->displayDateOfBirth());
        $this->assertEquals('Harriet Jones', $this->presenter->displayFullName());
        $this->assertEquals('drivingLicenceNumber', $this->presenter->displayDrivingLicenceNumber());
        $this->assertEquals(
            'Address line 1, Address line 2, Address line 3, Address line 4, Bristol, Postcode',
            $this->presenter->displayAddressLine()
        );
        $this->assertEquals('Address line 1', $this->presenter->displayAddressLine1());
        $this->assertEquals('Address line 2', $this->presenter->displayAddressLine2());
        $this->assertEquals('Address line 3', $this->presenter->displayAddressLine3());
        $this->assertEquals('Address line 4', $this->presenter->displayAddressLine4());
        $this->assertEquals('Postcode', $this->presenter->displayPostcode());
        $this->assertEquals('Bristol', $this->presenter->displayTown());
        $this->assertEquals(
            'Address line 1, Address line 2, Address line 3, Address line 4, Bristol, Postcode',
            $this->presenter->displayFullAddress()
        );
        $this->assertEquals('dummy@email.com', $this->presenter->displayEmail());
        $this->assertEquals('+768-45-4433630', $this->presenter->displayTelephone());

        $this->assertEquals('/event/list/person/1', $this->presenter->displayEventsHistoryLink());

        $roles = [
            0 => [
                'roles' => ['Authorised Examiner Designated Manager'],
                'route' => AuthorisedExaminerUrlBuilderWeb::of(0)
            ],
            1 => [
                'roles' => ['Tester'],
                'route' => VehicleTestingStationUrlBuilderWeb::byId(0)
            ],
    ];
        $this->assertEquals($roles, $this->presenter->getSiteAndOrganisationRoles());
        $this->assertEquals('user-admin/user-profile/dvsa-profile.phtml', $this->presenter->getTemplate());
    }

    public function testGetTemplate()
    {
        $catalogService = XMock::of(CatalogService::class);

        $this->presenter = new UserProfilePresenter(
            $this->buildPersonHelpDeskProfileDto(),
            new TesterAuthorisationViewModel(1,
                new TesterAuthorisation(),
                XMock::of(MotAuthorisationServiceInterface::class)
            ),
            $catalogService,
            false
        );
        $this->assertEquals('user-admin/user-profile/unrestricted-profile.phtml', $this->presenter->getTemplate());
    }

    /**
     * Generates a mock array of the catalog Site and Organisation response
     * @return array
     */
    private function buildSiteAndOrganisationCatalog()
    {
        return [
                [
                    'id' => 1,
                    'code' => 'AEDM',
                    'name' => 'Authorised Examiner Designated Manager',
                ],
                [
                    'id' => 1,
                    'code' => 'TESTER',
                    'name' => 'Tester',
                ],
        ];
    }

    private function buildPersonHelpDeskProfileDto()
    {
        $address = (new AddressDto())
            ->setAddressLine1('Address line 1')
            ->setAddressLine2('Address line 2')
            ->setAddressLine3('Address line 3')
            ->setAddressLine4('Address line 4')
            ->setTown('Bristol')
            ->setPostcode('Postcode');

        $roles = [
            'system' => [
                'roles' => ['AE']
            ],
            'organisations' => [
                [
                    'roles' => ['AEDM']
                ]
            ],
            'sites' => [
                [
                    'roles' => ['TESTER']
                ]
            ],
        ];

        return (new PersonHelpDeskProfileDto())
            ->setRoles($roles)
            ->setUserName('Username')
            ->setDrivingLicenceNumber('drivingLicenceNumber')
            ->setTitle('Mrs')
            ->setFirstName('Harriet')
            ->setLastName('Jones')
            ->setDateOfBirth('1992-05-29')
            ->setAddress($address)
            ->setEmail('dummy@email.com')
            ->setTelephone('+768-45-4433630');
    }
}
