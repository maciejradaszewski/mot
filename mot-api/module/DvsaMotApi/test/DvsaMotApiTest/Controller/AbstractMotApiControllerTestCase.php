<?php
namespace DvsaMotApiTest\Controller;

use DvsaCommonApiTest\Controller\AbstractRestfulControllerTestCase;
use SiteApi\Service\SiteService;
use Zend\Http\Header\ContentType;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Mvc\Router\RouteMatch;

/**
 * Class AbstractMotApiControllerTestCase
 *
 * @package DvsaMotApiTest\Controller
 */
abstract class AbstractMotApiControllerTestCase extends AbstractRestfulControllerTestCase
{
    protected function getMockMotTestService()
    {
        return $this->getMockServiceManagerClass('MotTestService', \DvsaMotApi\Service\MotTestService::class);
    }

    protected function getMockVehicleTestingStationService()
    {
        return $this->getMockServiceManagerClass(
            SiteService::class,
            SiteService::class
        );
    }

    protected function setJsonHeader()
    {
        $header = ContentType::fromString("content-type: application/json");
        $this->request->getHeaders()->addHeader($header);
    }

    protected function setJsonRequestContent($jsonArray)
    {
        $this->setJsonHeader();
        $this->request->setContent(json_encode($jsonArray));
    }
}
