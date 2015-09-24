<?php

namespace OrganisationApiTest\Controller;

use DvsaCommonApiTest\Controller\AbstractRestfulControllerTestCase;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\TestUtils\XMock;
use OrganisationApi\Controller\SiteController;
use OrganisationApi\Service\SiteService;

/**
 * Class SiteControllerTest
 *
 * @package OrganisationApiTest\Controller
 */
class SiteControllerTest extends AbstractRestfulControllerTestCase
{
    private $organisationId = 1;
    private $siteServiceMock;

    protected function setUp()
    {
        $this->controller      = new SiteController();
        $this->siteServiceMock = $this->getSiteServiceMock();
        $this->setupServiceManager();

        parent::setUp();
    }

    public function testGetListWithOrganisationIdCanBeAccessed()
    {
        //given
        $this->routeMatch->setParam('organisationId', $this->organisationId);

        //when
        $result = $this->controller->getList();

        //then
        $this->assertInstanceOf("Zend\View\Model\JsonModel", $result);
    }

    private function getSiteServiceMock()
    {
        $siteServiceMock = XMock::of(SiteService::class);

        $siteServiceMock->expects($this->once())
            ->method('getListForOrganisation')
            ->with($this->organisationId)
            ->will($this->returnValue([]));

        return $siteServiceMock;
    }

    private function setupServiceManager()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService(SiteService::class, $this->siteServiceMock);

        $this->controller->setServiceLocator($serviceManager);
    }
}
