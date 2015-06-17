<?php

namespace UserAdminTest\Controller;

use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\Plugin\Params;
use DvsaCommonTest\TestUtils\XMock;
use Zend\Mvc\Controller\PluginManager;
use Zend\View\Model\ModelInterface;
use DvsaCommonTest\TestUtils\Auth\AuthorisationServiceMock;
use Zend\Mvc\Controller\Plugin\Url;
use UserAdmin\Controller\UserProfileController;
use Zend\Mvc\Controller\Plugin\Redirect;

/**
 * Approach to unit-test web controllers without full zend bootstrap.
 */
abstract class AbstractLightWebControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Controller under test.
     * @var AbstractActionController
     */
    private $controller;

    /** @var MotAuthorisationServiceInterface|AuthorisationServiceMock */
    protected $authorisationMock;

    /** @var Params|MockObject */
    protected $paramsPluginMock;

    /** @var Url|MockObject */
    protected $urlPluginMock;

    /** @var Redirect|MockObject */
    protected $redirectPluginMock;

    /** @var ModelInterface|MockObject */
    protected $layoutPluginMock;

    /** @var PluginManager|MockObject */
    private $pluginManagerMock;

    protected function setUp()
    {
        $this->authorisationMock = AuthorisationServiceMock::denyAll();
        $this->setUpPluginMocks();
    }

    /**
     * Get service (controller) under test.
     * @return AbstractActionController
     */
    protected function sut()
    {
        return $this->controller;
    }

    /**
     * @param UserProfileController $controller
     * @return $this
     */
    protected function setController($controller)
    {
        $this->controller = $controller;
        $this->controller->setPluginManager($this->pluginManagerMock);
        return $this;
    }

    /**
     * @param array $params name => value map
     */
    protected function setRouteParams($params)
    {
        $paramsForMock = [];
        foreach ($params as $key => $value) {
            $paramsForMock[] = [$key, null, $value];
        }

        $this->paramsPluginMock
            ->expects(\PHPUnit_Framework_TestCase::any())
            ->method('fromRoute')
            ->will(\PHPUnit_Framework_TestCase::returnValueMap($paramsForMock));
    }

    protected function setUpPluginMocks()
    {
        $this->paramsPluginMock = $this->createPluginMock(Params::class);
        $this->redirectPluginMock = $this->createPluginMock(Redirect::class);
        $this->urlPluginMock = $this->createPluginMock(Url::class);
        $this->layoutPluginMock = $this->createPluginMock(ModelInterface::class);

        $this->pluginManagerMock = XMock::of(PluginManager::class);
        $this->pluginManagerMock
            ->expects(\PHPUnit_Framework_TestCase::any())
            ->method('get')
            ->will(
                \PHPUnit_Framework_TestCase::returnValueMap(
                    [
                        ['layout', null, true, $this->layoutPluginMock],
                        ['params', null, true, $this->paramsPluginMock],
                        ['redirect', null, true, $this->redirectPluginMock],
                        ['url', null, true, $this->urlPluginMock],
                    ]
                )
            );
        if (!is_null($this->controller)) {
            $this->controller->setPluginManager($this->pluginManagerMock);
        }
    }

    protected function createPluginMock($className)
    {
        $mock = XMock::of($className);
        $mock
            ->expects(\PHPUnit_Framework_TestCase::any())
            ->method('__invoke')
            ->willReturnSelf();
        return $mock;
    }

    protected function expectRedirect($route, $parameters = [], $options = [], $reuseMatchParams = false)
    {
        $this->redirectPluginMock
            ->expects(\PHPUnit_Framework_TestCase::once())
            ->method('toRoute')
            ->with($route, $parameters, $options, $reuseMatchParams);
    }
}
