<?php

namespace DvsaMotApiTest\Controller;

use DvsaCommon\Auth\AbstractMotAuthorisationService;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommonApi\Service\Exception\ForbiddenException;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\MotTest;
use DvsaMotApi\Controller\NonMotInspectionController;
use DvsaMotApi\Service\MotTestService;
use Zend\Http\Response;
use Zend\Stdlib\Parameters;

class NonMotInspectionControllerTest extends AbstractMotApiControllerTestCase
{
    private $authService;
    private $motTestService;

    public function setUp()
    {
        $this->authService = XMock::of(AbstractMotAuthorisationService::class);
        $this->motTestService = XMock::of(MotTestService::class);

        parent::setUp();
    }

    public function testForbiddenThrownWhenNotGrantedNonMotPermission()
    {
        $this->setExpectedException(ForbiddenException::class);

        $this
            ->withoutPerformNonMotTestPermission()
            ->buildController()
            ->dispatchPostRequest([]);
    }

    public function testMotTestNumberReturnedIfToggleOnAndPermissionGranted()
    {
        $motTestNumber = 12345;

        $this
            ->withPerformNonMotTestPermission()
            ->withCreatedMotTest((new MotTest())->setNumber($motTestNumber))
            ->buildController();

        $viewModel = $this->dispatchPostRequest([]);
        $viewVariables = $viewModel->getVariables();

        $this->assertEquals(200, $this->getResponse()->getStatusCode());
        $this->assertEquals($motTestNumber, $viewVariables['data']['motTestNumber']);
    }

    private function withCreatedMotTest(MotTest $motTest)
    {
        $this->motTestService
            ->expects($this->any())
            ->method('createMotTest')
            ->willReturn($motTest);

        return $this;
    }

    private function withPerformNonMotTestPermission()
    {
        $this->authService
            ->expects($this->any())
            ->method('isGranted')
            ->with(PermissionInSystem::ENFORCEMENT_NON_MOT_TEST_PERFORM)
            ->willReturn(true);

        return $this;
    }

    private function withoutPerformNonMotTestPermission()
    {
        $this->authService
            ->expects($this->any())
            ->method('isGranted')
            ->with(PermissionInSystem::ENFORCEMENT_NON_MOT_TEST_PERFORM)
            ->willReturn(false);

        return $this;
    }

    private function dispatchPostRequest(array $data)
    {
        $this->request->setMethod('post');
        $this->request->setPost(new Parameters($data));

        return $this->controller->dispatch($this->request);
    }

    /**
     * @return Response
     */
    private function getResponse()
    {
        return $this->controller->getResponse();
    }

    private function buildController()
    {
        $this->controller = new NonMotInspectionController(
            $this->motTestService,
            $this->authService
        );
        $this->setUpController($this->controller);

        return $this;
    }
}
