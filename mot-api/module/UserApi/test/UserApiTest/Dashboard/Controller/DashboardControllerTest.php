<?php

namespace UserApiTest\Dashboard\Controller;

use DvsaCommonTest\Bootstrap;
use UserApi\Dashboard\Controller\DashboardController;
use UserApi\Dashboard\Dto\DashboardData;
use UserApi\Dashboard\Service\DashboardService;

/**
 * Class DashboardControllerTest
 *
 * @package UserApiTest\Dashboard\Controller
 */
class DashboardControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAction()
    {
        $dashboardDataMock = \DvsaCommonTest\TestUtils\XMock::of(DashboardData::class);

        $dashboardDataMock->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue([]));

        $mock = \DvsaCommonTest\TestUtils\XMock::of(DashboardService::class);

        $mock->expects($this->once())
            ->method('getDataForDashboardByPersonId')
            ->will($this->returnValue($dashboardDataMock));

        $sm = Bootstrap::getServiceManager();
        $sm->setAllowOverride(true);
        $sm->setService(DashboardService::class, $mock);

        $controller = new DashboardController();
        $controller->setServiceLocator($sm);
        $json = $controller->get(1);

        $this->assertEquals(get_class($json), \Zend\View\Model\JsonModel::class);
    }
}
