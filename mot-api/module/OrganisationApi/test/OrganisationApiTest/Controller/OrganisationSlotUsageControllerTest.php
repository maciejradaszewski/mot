<?php

namespace OrganisationApiTest\Controller;

use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommonApiTest\Controller\AbstractRestfulControllerTestCase;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Repository\SiteRepository;
use OrganisationApi\Controller\OrganisationSlotUsageController;
use OrganisationApi\Service\OrganisationSlotUsageService;
use Zend\Stdlib\Parameters;

class OrganisationSlotUsageControllerTest extends AbstractRestfulControllerTestCase
{

    /**
     * @var OrganisationSlotUsageController
     */
    protected $controller;

    protected function setUp()
    {
        $this->controller = new OrganisationSlotUsageController();

        parent::setUp();

        $repoMock = XMock::of(SiteRepository::class, ['searchOrgSlotUsage', 'getSlotUsage']);

        $service = new OrganisationSlotUsageService($repoMock, $this->getMockAuthService());
        $this->serviceManager->setService(OrganisationSlotUsageService::class, $service);
    }

    protected function getMockAuthService()
    {
        $mock = $this->getMockWithDisabledConstructor(MotAuthorisationServiceInterface::class);
        $this->serviceManager->setService('AuthorizationService', $mock);

        return $mock;
    }

    public function testReportWithAllParams()
    {
        $result = $this->controller->getList();

        $this->assertInstanceOf(\Zend\View\Model\JsonModel::class, $result);
    }

    public function testSlotPeriodUsage()
    {
        $periods = [
            [
                'from' => 'from',
                'to'   => 'to',
            ],
        ];

        $queryParams = new Parameters();
        $queryParams->set('period', $periods);

        $this->controller->getRequest()->setQuery($queryParams);

        $result = $this->controller->periodDataAction();

        $this->assertInstanceOf(\Zend\View\Model\JsonModel::class, $result);
    }
}
