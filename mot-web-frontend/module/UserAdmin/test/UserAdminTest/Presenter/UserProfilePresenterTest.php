<?php

namespace UserAdminTest\Presenter;

use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Person\PersonHelpDeskProfileDto;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilderWeb;
use DvsaCommon\UrlBuilder\VehicleTestingStationUrlBuilderWeb;
use DvsaCommonTest\TestUtils\XMock;
use UserAdmin\Presenter\UserProfilePresenter;
use UserAdmin\Service\TesterQualificationStatusService;
use Zend\Mvc\Controller\Plugin\Url;

/**
 * Unit tests for UserProfilePresenter
 */
class UserProfilePresenterTest extends \PHPUnit_Framework_TestCase
{
    /** @var UserProfilePresenter $presenter */
    private $presenter;

    /** @var TesterQualificationStatusService */
    private $testerQualificationStatusMock;

    public function setUp()
    {
        $this->testerQualificationStatusMock = XMock::of(TesterQualificationStatusService::class);
    }

    public function testDisplayInformation()
    {
        $this->presenter = new UserProfilePresenter(
            $this->buildPersonHelpDeskProfileDto(),
            $this->testerQualificationStatusMock->getPersonGroupQualificationStatus(5),
            true
        );
        $this->presenter->setId(1);
        $this->assertEquals(1, $this->presenter->getId());
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
        $this->assertEquals(['AE'], $this->presenter->getSystemRoles());

        $roles = [
            1 => [
                ['data' => 'data'],
                'route' => AuthorisedExaminerUrlBuilderWeb::of(1)
            ],
            2 => [
                ['data' => 'data'],
                'route' => VehicleTestingStationUrlBuilderWeb::byId(2)
            ],
        ];
        $this->assertEquals($roles, $this->presenter->getSiteAndOrganisationRoles());
        $this->assertEquals('user-admin/user-profile/dvsa-profile.phtml', $this->presenter->getTemplate());
    }

    public function testGetTemplate()
    {
        $this->presenter = new UserProfilePresenter(
            $this->buildPersonHelpDeskProfileDto(),
            $this->testerQualificationStatusMock->getPersonGroupQualificationStatus(5),
            false
        );
        $this->assertEquals('user-admin/user-profile/unrestricted-profile.phtml', $this->presenter->getTemplate());
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
                1 => [['data' => 'data']]
            ],
            'sites' => [
                2 => [['data' => 'data']]
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
