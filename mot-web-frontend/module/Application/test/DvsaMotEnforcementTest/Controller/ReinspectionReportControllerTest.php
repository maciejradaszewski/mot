<?php

namespace DvsaMotEnforcementTest\Controller;

use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommonTest\Bootstrap;
use DvsaMotEnforcement\Controller\ReinspectionReportController;
use DvsaMotTestTest\Controller\AbstractDvsaMotTestTestCase;
use Zend\View\Model\ViewModel;

/**
 * Class ReinspectionReportControllerTest
 *
 * @package DvsaMotEnforcementTest\Controller
 */
class ReinspectionReportControllerTest extends AbstractDvsaMotTestTestCase
{
    protected $mockTrait;

    protected function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->setAllowOverride(true);
        $this->setServiceManager($serviceManager);

        $this->setController(new ReinspectionReportController());

        $this->getRestClientMockForServiceManager();

        parent::setUp();
    }

    public function testRecordAssessmentConfirmationAction()
    {
        $this->setupAuthorizationService([PermissionInSystem::MOT_TEST_REINSPECTION_PERFORM]);
        $this->getResponseForAction('recordAssessmentConfirmation');
        $this->assertResponseStatus(self::HTTP_OK_CODE);
    }

    public function testRecordAssessmentConfirmationNotSavedAction()
    {
        $this->setupAuthorizationService([PermissionInSystem::MOT_TEST_REINSPECTION_PERFORM]);

        $restMock = $this->getRestClientMockForServiceManager();
        $restMock
            ->expects($this->any())
            ->method('get')
            ->will($this->throwException(new \Exception()));

        $this->getResponseForAction('recordAssessmentConfirmation');
        $this->assertResponseStatus(self::HTTP_OK_CODE);
    }
}
