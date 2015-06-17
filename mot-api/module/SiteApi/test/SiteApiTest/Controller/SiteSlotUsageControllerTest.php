<?php

namespace SiteApiTest\Controller;

use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommonApiTest\Controller\AbstractRestfulControllerTestCase;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Repository\SiteRepository;
use SiteApi\Controller\SiteSlotUsageController;
use SiteApi\Service\SiteSlotUsageService;
use Zend\Stdlib\Parameters;

/**
 * Class SiteSlotUsageControllerTest
 *
 * @package SiteApiTest\Controller
 */
class SiteSlotUsageControllerTest extends AbstractRestfulControllerTestCase
{

    /**
     * @var SiteSlotUsageController
     */
    protected $controller;

    protected function setUp()
    {
        $this->controller = new SiteSlotUsageController();

        parent::setUp();

        $repoMock = XMock::of(SiteRepository::class);
        $authorisationService = XMock::of(MotAuthorisationServiceInterface::class);

        $service = new SiteSlotUsageService($repoMock, $authorisationService);
        $this->serviceManager->setService(SiteSlotUsageService::class, $service);
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
            ]
        ];

        $queryParams = new Parameters();
        $queryParams->set('period', $periods);

        $this->controller->getRequest()->setQuery($queryParams);

        $result = $this->controller->periodDataAction();

        $this->assertInstanceOf(\Zend\View\Model\JsonModel::class, $result);
    }
}
