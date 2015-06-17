<?php

namespace DvsaCommonApiTest\Listener;

use DvsaCommonApi\Listener\ErrorHandlingListener;
use DvsaCommonApi\Service\Exception\NotFoundException;
use PHPUnit_Framework_TestCase;
use Zend\EventManager\EventManager;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;

/**
 * Class ErrorHandlingListenerTest
 */
class ErrorHandlingListenerTest extends PHPUnit_Framework_TestCase
{
    private $errorHandlingListener;

    protected function setUp()
    {
        $this->errorHandlingListener = new ErrorHandlingListener(
            [
                MvcEvent::EVENT_DISPATCH => 0,
                MvcEvent::EVENT_RENDER   => 1
            ]
        );
    }

    public function testServiceExceptionOccurred()
    {
        $this->markTestSkipped('This has to be skipped as we cannot boot a fake application without provided config');

        $eventManager = new EventManager();

        $this->errorHandlingListener->attach($eventManager);
        $e = new MvcEvent();
        $e->setName(MvcEvent::EVENT_DISPATCH_ERROR);
        $e->setParam("exception", new NotFoundException("w", 404));
        $e->setError("error");
        $e->setResponse(new Response());

        $applicationMock = $this->getApplicationMock();

        $e->setApplication($applicationMock);
        $eventManager->trigger($e);

        $this->assertEquals(Response::STATUS_CODE_404, $e->getResponse()->getStatusCode());
        $this->assertInstanceOf(\Zend\View\Model\JsonModel::class, $e->getResult());
    }

    protected function getApplicationMock()
    {
        \DvsaCommonTest\Bootstrap::init();
        $sm = \DvsaCommonTest\Bootstrap::getServiceManager();

        $loggerMock = \DvsaCommonTest\TestUtils\XMock::of(\Zend\Log\Logger::class);

        $sm->setAllowOverride(true);
        $sm->setService('Application/Logger', $loggerMock);

        $applicationMock = \DvsaCommonTest\TestUtils\XMock::of(\Zend\Mvc\Application::class);
        $applicationMock->expects($this->any())
            ->method('getServiceManager')
            ->will($this->returnValue($sm));

        return $applicationMock;
    }

    public function testUnknownExceptionOccurred()
    {
        $this->markTestSkipped('This has to be skipped as we cannot boot a fake application without provided config');

        $eventManager = new EventManager();

        $this->errorHandlingListener->attach($eventManager);
        $e = new MvcEvent();
        $e->setName(MvcEvent::EVENT_DISPATCH_ERROR);
        $e->setParam("exception", new \Exception());
        $e->setError("theError");
        $response = new Response();
        $response->setStatusCode(410);
        $e->setResponse($response);

        $applicationMock = $this->getApplicationMock();
        $e->setApplication($applicationMock);

        $eventManager->trigger($e);

        $this->assertEquals(Response::STATUS_CODE_500, $e->getResponse()->getStatusCode());
        $this->assertInstanceOf(\Zend\View\Model\JsonModel::class, $e->getResult());

        $foundError = $e->getResult()->getVariables()['errors'][0];
        $this->assertEquals($foundError['error'], 'theError');
        $this->assertEquals($foundError['exception']['class'], "Exception");
    }
}
