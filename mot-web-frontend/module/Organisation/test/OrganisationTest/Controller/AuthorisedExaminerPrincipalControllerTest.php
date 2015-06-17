<?php
namespace OrganisationTest\Controller;

use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\TestUtils\XMock;
use Core\Service\MotFrontendAuthorisationServiceInterface;
use Organisation\Controller\AuthorisedExaminerPrincipalController;

/**
 * Class AuthorisedExaminerPrincipalControllerTest
 */
class AuthorisedExaminerPrincipalControllerTest extends \PHPUnit_Framework_TestCase
{
    const VIEW_MODEL_CLASS_PATH = 'Zend\View\Model\ViewModel';

    public function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->setAllowOverride(true);

        $authorisationService = XMock::of(MotFrontendAuthorisationServiceInterface::class);
        $this->controller = new AuthorisedExaminerPrincipalController($authorisationService);
        $this->controller->setServiceLocator($serviceManager);
        parent::setUp();
    }

    public function testIndexActionReturnsViewModel()
    {
//        $return = $this->controller->indexAction();
//        $this->assertEquals(get_class($return), self::VIEW_MODEL_CLASS_PATH);
    }
}
