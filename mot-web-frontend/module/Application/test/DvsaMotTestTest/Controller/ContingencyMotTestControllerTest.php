<?php

namespace DvsaMotTestTest\Controller;

use Application\Service\LoggedInUserManager;
use CoreTest\Controller\AbstractFrontendControllerTestCase;
use DvsaAuthentication\Model\VehicleTestingStation;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\Controller\StubIdentityAdapter;
use DvsaCommonTest\TestUtils\XMock;
use DvsaMotTest\Controller\ContingencyMotTestController;
use DvsaCommon\HttpRestJson\Exception\GeneralRestException;
use DvsaCommon\HttpRestJson\Exception\RestApplicationException;

/**
 * Class ContingencyMotTestControllerTest
 */
class ContingencyMotTestControllerTest extends AbstractFrontendControllerTestCase
{
    private $loggedInUserManagerMock;

    protected function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $this->serviceManager->setAllowOverride(true);
        $this->setServiceManager($this->serviceManager);

        $this->setController(new ContingencyMotTestController());
        $this->getController()->setServiceLocator($this->serviceManager);

        $this->loggedInUserManagerMock = XMock::of(
            LoggedInUserManager::class,
            ['getAllVts', 'getTesterData', 'changeCurrentLocation']
        );

        $this->serviceManager->setService('LoggedInUserManager', $this->loggedInUserManagerMock);
        parent::setUp();
    }

    /**
     * Test has user access to page or not with/out auth and permission
     *
     * @param array         $params             Post parameters
     * @param array         $permissions        Permissions
     * @param boolean       $differentVts       If its tested at a different vts
     * @param string        $expectedUrl        Expect redirect if failure
     * @param \Exception    $expectedException  Expect exception
     *
     * @dataProvider dataProviderContingencyControllerTestCanAccessHasRight
     */
    public function testContingencyControllerCanAccessHasRight(
        $params = null,
        $permissions = [],
        $differentVts = true,
        $expectedUrl = null,
        $expectedException = null
    ) {
        $this->setupAuthenticationServiceForIdentity(StubIdentityAdapter::asTester());
        $this->setupAuthorizationService($permissions);

        if ($params !== null) {
            $this->setPostData($params, $expectedException, $differentVts);
        }

        $this->getResponseForAction('index');

        if ($expectedUrl) {
            $this->assertRedirectLocation2($expectedUrl);
        } else {
            $this->assertResponseStatus(self::HTTP_OK_CODE);
        }
    }

    protected function setPostData($params, $expectedException, $differentVts)
    {
        $this->setPostAndPostParams($params);
        if ($expectedException !== null) {
            $this->getRestClientMockThrowingSpecificException('post', $expectedException);
        } else {
            $this->getRestClientMock('post', ['data' => ['emergencyLogId' => 1]]);
            if ($differentVts === true) {
                $identity = $this->getCurrentIdentity();
                $identity->setCurrentVts(new VehicleTestingStation(['id' => 2]));
                $this->loggedInUserManagerMock->expects($this->once())
                    ->method('getTesterData')
                    ->willReturn(null);
                $this->loggedInUserManagerMock->expects($this->once())
                    ->method('changeCurrentLocation')
                    ->willReturn(null);
            } else {
                $identity = $this->getCurrentIdentity();
                $identity->setCurrentVts(new VehicleTestingStation(['id' => 1]));
            }
        }
    }

    public function dataProviderContingencyControllerTestCanAccessHasRight()
    {
        return [
            [null, [PermissionInSystem::EMERGENCY_TEST_READ]],
            [null, [], false, '/'],
            [[
                'radio-test-who-group' => 'current',
                'radio-site-group' => '1',
                'radio-test-type-group' => 'normal',
                'ct-code' => '12345A',
                'testerNumber' => '1',
                'dateTestYear' => 2014,
                'dateTestMonth' => 01,
                'dateTestDay' => 01,
                'radio-reason-group' => 'SO',
                'other-reasons' => '',
            ], [PermissionInSystem::EMERGENCY_TEST_READ], false, '/vehicle-search?contingency=1'],
            [[
                'radio-test-who-group' => 'current',
                'radio-site-group' => '1',
                'radio-test-type-group' => 'retest',
                'ct-code' => '12345A',
                'testerNumber' => '1',
                'dateTestYear' => 2014,
                'dateTestMonth' => 01,
                'dateTestDay' => 01,
                'radio-reason-group' => 'SO',
                'other-reasons' => '',
            ], [PermissionInSystem::EMERGENCY_TEST_READ], false, '/retest-vehicle-search?contingency=1'],
            [[
                'radio-test-who-group' => 'current',
                'radio-site-group' => '1',
                'radio-test-type-group' => 'normal',
                'ct-code' => '12345A',
                'testerNumber' => '1',
                'dateTestYear' => 2014,
                'dateTestMonth' => 01,
                'dateTestDay' => 01,
                'radio-reason-group' => 'SO',
                'other-reasons' => '',
            ], [PermissionInSystem::EMERGENCY_TEST_READ], true, '/vehicle-search?contingency=1'],
            [[
                'radio-test-who-group' => 'current',
                'radio-site-group' => '1',
                'radio-test-type-group' => 'normal',
                'ct-code' => '12345A',
                'testerNumber' => '1',
                'dateTestYear' => 2014,
                'dateTestMonth' => 01,
                'dateTestDay' => 01,
                'radio-reason-group' => 'SO',
                'other-reasons' => '',
            ], [PermissionInSystem::EMERGENCY_TEST_READ], false, null, new RestApplicationException('', 'post', [], 404)],
            [[
                'radio-test-who-group' => 'current',
                'radio-site-group' => '1',
                'radio-test-type-group' => 'normal',
                'ct-code' => '12345A',
                'testerNumber' => '1',
                'dateTestYear' => 2014,
                'dateTestMonth' => 01,
                'dateTestDay' => 01,
                'radio-reason-group' => 'SO',
                'other-reasons' => '',
            ], [PermissionInSystem::EMERGENCY_TEST_READ], false, null, new GeneralRestException('', 'post', [], 404)],
        ];
    }
}
