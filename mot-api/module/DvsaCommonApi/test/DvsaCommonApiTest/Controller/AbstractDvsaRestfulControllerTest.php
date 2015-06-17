<?php
namespace DvsaCommonApiTest\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApiTest\Controller\AbstractRestfulControllerTestCase;

/**
 * Class AbstractDvsaRestfulControllerTest
 */
class AbstractDvsaRestfulControllerTest extends AbstractRestfulControllerTestCase
{
    protected function setUp()
    {
        $this->controller = new AbstractDvsaRestfulController();
        parent::setUp();
        $this->mockLogger();
    }

    public function testCreateReturns405()
    {
        $this->request->setMethod('post');
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertResponse405Error($response, $result);
    }

    public function testDeleteReturns405()
    {
        $this->request->setMethod('delete');
        $this->routeMatch->setParam('id', 1);
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertResponse405Error($response, $result);
    }

    public function testDeleteListReturns405()
    {
        $this->request->setMethod('delete');
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertResponse405Error($response, $result);
    }

    public function testGetReturns405()
    {
        $this->routeMatch->setParam('id', 1);
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertResponse405Error($response, $result);
    }

    public function testGetListReturns405()
    {
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertResponse405Error($response, $result);
    }

    public function testHeadReturns405()
    {
        $this->request->setMethod('head');
        $this->routeMatch->setParam('id', 1);
        $result  = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testOptionsReturns405()
    {
        $this->request->setMethod('options');
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testPatchReturns405()
    {
        $this->request->setMethod('patch');
        $this->routeMatch->setParam('id', 1);
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertResponse405Error($response, $result);
    }

    public function testReplaceListReturns405()
    {
        $this->request->setMethod('put');
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertResponse405Error($response, $result);
    }

    public function testUpdateReturns405()
    {
        $this->request->setMethod('put');
        $this->routeMatch->setParam('id', 1);
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertResponse405Error($response, $result);
    }
}
