<?php

namespace DvsaMotApiTest\Controller;

use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Dto\Common\MotTestDto;
use DvsaCommonApiTest\Controller\AbstractRestfulControllerTestCase;
use DvsaCommonApiTest\Transaction\TestTransactionExecutor;
use DvsaCommonTest\TestUtils\ArgCapture;
use DvsaMotApi\Controller\ReplacementCertificateDraftController;
use DvsaMotApi\Dto\ReplacementCertificateDraftChangeDTO;
use DvsaMotApi\Service\CertificateCreationService;
use DvsaMotApi\Service\ReplacementCertificate\ReplacementCertificateService;
use DvsaMotApiTest\Factory\ReplacementCertificateObjectsFactory;
use Zend\Http\Header\Authorization;

/**
 * Class ReplacementCertificateDraftControllerTest
 */
class ReplacementCertificateDraftControllerTest extends AbstractMotApiControllerTestCase
{

    public function testCreate_givenMotTestIdAsInput_shouldReturnIdOfTheCreatedDraft()
    {
        // given
        $motTestNumber = 4;
        $createdDraft = ReplacementCertificateObjectsFactory::replacementCertificateDraft();
        $this->request->setMethod('post');
        $this->setJsonRequestContent(['motTestNumber' => $motTestNumber]);

        $this->getMockServiceManagerClass(
            'ReplacementCertificateService',
            ReplacementCertificateService::class
        )->expects($this->any())->method("createDraft")
            ->with($motTestNumber)
            ->will($this->returnValue($createdDraft));

        // when
        $jsonData = $this->dispatch();

        //then
        $this->assertEquals(
            ['id' => $createdDraft->getId()], $jsonData, "Returned draft id is incorrect"
        );
    }

    public function testUpdate_givenDraftChangeAsInput_shouldCallUpdateService()
    {
        // given
        $draftChangeCapture = ArgCapture::create();
        $draftIdCapture = ArgCapture::create();
        $draftId = 4;
        $this->routeMatch->setParam('id', $draftId);
        $this->request->setMethod('put');
        $this->setJsonRequestContent(['primaryColour' => "Y"]);

        $this->getMockServiceManagerClass(
            'ReplacementCertificateService',
            ReplacementCertificateService::class
        )->expects($this->any())->method("updateDraft")
            ->with($draftIdCapture(), $draftChangeCapture());

        // when
        $this->dispatch();

        // then
        $this->assertEquals($draftId, $draftIdCapture->get(), "Passed in draftId is not correct");
        $this->assertThat(
            $draftChangeCapture->get(), $this->isInstanceOf(ReplacementCertificateDraftChangeDTO::class),
            "Wrong parameter type is passed in to the service"
        );
    }

    public function testGet_givenDraftIdAsInput_shouldCallServiceMethod()
    {
        // given
        $returnedDraft = ReplacementCertificateObjectsFactory::replacementCertificateDraft();
        $draftIdCapture = ArgCapture::create();
        $draftId = 4;
        $this->routeMatch->setParam('id', $draftId);
        $this->request->setMethod('get');

        $this->getMockServiceManagerClass(
            'DvsaAuthorisationService',
            AuthorisationServiceInterface::class
        )->expects($this->any())->method("isGranted")
            ->will($this->returnValue(true));

        $this->getMockServiceManagerClass(
            'ReplacementCertificateService',
            ReplacementCertificateService::class
        )->expects($this->any())->method("getDraft")
            ->with($draftIdCapture())
            ->will($this->returnValue($returnedDraft));

        // when
        $jsonData = $this->dispatch();

        // then
        $this->assertEquals($draftId, $draftIdCapture->get(), "Passed in draftId is not correct");
        $this->assertEquals(
            $returnedDraft->getPrimaryColour()->getCode(), $jsonData['primaryColour']['code'],
            "Invalid data returned from the controller"
        );
    }

    public function testDiffAction_givenDraftIdAsInput_shouldReturnDraftDiff()
    {
        // given
        $returnedDraft = ReplacementCertificateObjectsFactory::replacementCertificateDraft();
        $returnedDraft->getPrimaryColour()->setCode("Y");
        $returnedDraft->getMotTest()->getPrimaryColour()->setCode("Z");
        $draftIdCapture = ArgCapture::create();
        $draftId = 4;
        $this->routeMatch->setParam('id', $draftId)
            ->setParam('action', 'diff');
        $this->request->setMethod('get');

        $this->getMockServiceManagerClass(
            'ReplacementCertificateService',
            ReplacementCertificateService::class
        )->expects($this->any())->method("getDraft")
            ->with($draftIdCapture())
            ->will($this->returnValue($returnedDraft));

        // when
        $jsonData = $this->dispatch();

        // then
        $this->assertEquals($draftId, $draftIdCapture->get(), "Passed in draftId is not correct");
        $this->assertEquals(
            $returnedDraft->getPrimaryColour()->getId(), in_array('primaryColour', $jsonData),
            "Though primary colour different, it isnt included in the diff"
        );
    }

    public function testApplyAction_givenDraftIdAsInput_shouldCallApplyService()
    {
        // given
        $draftIdCapture = ArgCapture::create();
        $draftId = 4;
        $this->routeMatch->setParam('id', $draftId)
            ->setParam('action', 'apply');
        $this->request->setMethod('post');

        $mockEntity = $this->getMock(\stdClass::class, ['getNumber']);
        $mockEntity->expects($this->once())
            ->method('getNumber')
            ->will($this->returnValue(123));

        $this->getMockServiceManagerClass(
            'ReplacementCertificateService',
            ReplacementCertificateService::class
        )->expects($this->any())->method("applyDraft")
            ->with($draftIdCapture())
            ->will($this->returnValue($mockEntity));

        $motMock = $this->getMockServiceManagerClass(
            'MotTestService',
            \DvsaMotApi\Service\MotTestService::class
        );

        $motData = new MotTestDto();

        $motMock->expects($this->once())
            ->method('getMotTestData')
            ->with(123)
            ->will($this->returnValue($motData));

        $certificateMock = $this->getMockServiceManagerClass(
            CertificateCreationService::class, CertificateCreationService::class
        );

        $certificateMock->expects($this->once())
            ->method('create')
            ->with(123, $motData, AbstractRestfulControllerTestCase::MOCK_USER_ID);

        // when
        $this->dispatch();

        // then
        $this->assertEquals($draftId, $draftIdCapture->get(), "Passed in draftId is not correct");
    }

    protected function setUp()
    {
        $this->controller = new ReplacementCertificateDraftController();
        TestTransactionExecutor::inject($this->controller);
        parent::setUp();

        $this->mockValidAuthorization();
    }

    private function dispatch()
    {
        return $this->controller->dispatch($this->request)->getVariables()['data'];
    }
}
