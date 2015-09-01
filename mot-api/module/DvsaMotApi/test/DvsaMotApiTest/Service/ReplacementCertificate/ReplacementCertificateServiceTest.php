<?php

namespace DvsaMotApiTest\Service\ReplacementCertificate;

use DvsaCommonApiTest\Transaction\TestTransactionExecutor;
use DvsaCommonTest\TestUtils\ArgCapture;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\ReplacementCertificateDraft;
use DvsaEntities\Repository\CertificateReplacementRepository;
use DvsaEntities\Repository\MotTestRepository;
use DvsaEntities\Repository\ReplacementCertificateDraftRepository;
use DvsaAuthentication\Service\OtpService;
use DvsaMotApi\Service\ReplacementCertificate\ReplacementCertificateUpdater;
use DvsaMotApi\Service\ReplacementCertificate\ReplacementCertificateDraftCreator;
use DvsaMotApi\Service\ReplacementCertificate\ReplacementCertificateDraftUpdater;
use DvsaMotApi\Service\ReplacementCertificate\ReplacementCertificateService;
use DvsaMotApiTest\Factory\MotTestObjectsFactory;
use DvsaMotApiTest\Factory\ReplacementCertificateObjectsFactory;
use PHPUnit_Framework_TestCase;
use DvsaMotApi\Service\CertificateCreationService;

/**
 * Class ReplacementCertificateServiceTest
 */
class ReplacementCertificateServiceTest extends PHPUnit_Framework_TestCase
{
    private $authorizationService;
    private $motTestRepository;
    private $draftCreator;
    private $draftUpdater;
    private $certificateCreator;
    private $certificateReplacementRepository;
    private $draftRepository;
    private $otpService;
    private $certificateCreationService;

    public function setUp()
    {
        $this->motTestRepository = XMock::of(MotTestRepository::class);
        $this->draftCreator = XMock::of(ReplacementCertificateDraftCreator::class);
        $this->draftUpdater = XMock::of(ReplacementCertificateDraftUpdater::class);
        $this->certificateCreator = XMock::of(ReplacementCertificateUpdater::class);
        $this->certificateReplacementRepository = XMock::of(CertificateReplacementRepository::class);
        $this->draftRepository = XMock::of(ReplacementCertificateDraftRepository::class);
        $this->authorizationService = XMock::of('DvsaAuthorisation\Service\AuthorisationServiceInterface', ['isGranted']);
        $this->authorizationService->expects($this->any())->method("isGranted")->will($this->returnValue(true));
        $this->otpService = XMock::of(OtpService::class);
        $this->certificateCreationService = XMock::of(CertificateCreationService::class);
    }

    private function createSUT()
    {
        $sut = new ReplacementCertificateService(
            $this->draftRepository,
            $this->draftCreator,
            $this->draftUpdater,
            $this->certificateCreator,
            $this->certificateReplacementRepository,
            $this->authorizationService,
            $this->motTestRepository,
            $this->otpService,
            $this->certificateCreationService
        );

        TestTransactionExecutor::inject($sut);
        return $sut;
    }

    public function testCreateDraft_givenMotTestId_returnCreatedDraftInstance()
    {
        $motTest = MotTestObjectsFactory::motTest()->setNumber(123456789012);
        $replacementDraft = ReplacementCertificateObjectsFactory::replacementCertificateDraft();

        $this->motTestRepository->expects($this->any())
            ->method("getMotTestByNumber")
            ->with($motTest->getNumber())
            ->will($this->returnValue($motTest));
        $this->draftCreator->expects($this->any())
            ->method("create")
            ->with($motTest)
            ->will($this->returnValue($replacementDraft));
        $this->draftRepository->expects($this->any())->method("save");

        $draft = $this->createSUT()->createDraft($motTest->getNumber());

        $this->assertInstanceOf(
            ReplacementCertificateDraft::class, $draft,
            "expected draft not returned!"
        );
    }

    public function testCreateAndUpdateDraft_givenMotTestId_callUpdaterAndReturnCreatedDraftInstance()
    {
        $motTest = MotTestObjectsFactory::motTest()->setNumber(123456789012);
        $replacementDraft = ReplacementCertificateObjectsFactory::replacementCertificateDraft();

        $this->motTestRepository->expects($this->any())
            ->method("getMotTestByNumber")
            ->with($motTest->getNumber())
            ->will($this->returnValue($motTest));
        $this->draftCreator->expects($this->any())
            ->method("create")
            ->with($motTest)
            ->will($this->returnValue($replacementDraft));
        $this->draftRepository->expects($this->any())->method("save");

        $changeData = ReplacementCertificateObjectsFactory::partialReplacementCertificateDraftChange(1);
        $this->draftUpdater->expects($this->once())
            ->method("updateDraft")
            ->with($replacementDraft, $changeData);

        $draft = $this->createSUT()->createAndUpdateDraft($motTest->getNumber(), '', $changeData);

        $this->assertInstanceOf(
            ReplacementCertificateDraft::class, $draft,
            "expected draft not returned!"
        );
    }

    public function testGetDraft_givenDraftId_returnDraftInstance()
    {
        $draft = ReplacementCertificateObjectsFactory::replacementCertificateDraft()->setId(12345);

        $this->returnsDraftForId($draft->getId(), $draft);

        $draft = $this->createSUT()->getDraft($draft->getId());

        $this->assertNotNull($draft, "retrieved draft instance should not be null");
    }

    public function testUpdateDraft_givenDraftIdAndChangeData_callUpdater()
    {
        $draft = ReplacementCertificateObjectsFactory::replacementCertificateDraft()->setId(12345);
        $changeData = ReplacementCertificateObjectsFactory::partialReplacementCertificateDraftChange(1);
        $this->returnsDraftForId($draft->getId(), $draft);

        $this->draftUpdater->expects($this->once())
            ->method("updateDraft")
            ->with($draft, $changeData);

        $this->createSUT()->updateDraft($draft->getId(), $changeData);
    }

    public function testApplyDraft_givenDraftId_createCorrectCertificateReplacement()
    {
        $exampleReason = "EXAMPLE_REASON";
        $draft = ReplacementCertificateObjectsFactory::replacementCertificateDraft()
            ->setId(12345)->setReplacementReason($exampleReason);
        $this->returnsDraftForId($draft->getId(), $draft);
        $certificateReplacementCapture = ArgCapture::create();

        $this->certificateCreator->expects($this->any())->method("create");
        $this->certificateReplacementRepository->expects($this->any())->method("save")
            ->with($certificateReplacementCapture());

        $data = ['oneTimePassword' => '123456'];

        $this->createSUT()->applyDraft($draft->getId(), $data);

        $certReplacement = $certificateReplacementCapture->get();
        $this->assertEquals(
            $exampleReason, $certReplacement->getReplacementReason(),
            "replacement reason of the draft should be copied to certificate replacement instance!"
        );
        $this->assertNotNull(
            $certReplacement->getMotTest(), "MOT test instance attached to certificate replacement cannot be null!"
        );
        $this->assertEquals(
            $draft->getMotTestVersion(), $certReplacement->getMotTestVersion(),
            'Version of MOT test attached to certificate replacement should point to the changed MOT test version'
        );
    }

    private function returnsDraftForId($inputDraftId, $returnedDraft)
    {
        $this->draftRepository->expects($this->any())
            ->method("get")
            ->with($inputDraftId)
            ->will($this->returnValue($returnedDraft));
    }
}
