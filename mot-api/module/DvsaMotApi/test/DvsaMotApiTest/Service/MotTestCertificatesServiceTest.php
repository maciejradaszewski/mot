<?php

namespace DvsaMotApiTest\Service;

use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommonTest\TestUtils\Auth\AuthorisationServiceMock;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\MotTestRecentCertificate;
use DvsaEntities\Entity\MotTestStatus;
use DvsaEntities\Repository\MotTestRecentCertificateRepository;
use DvsaMotApi\Service\CertificateStorageService;
use DvsaMotApi\Service\MotTestCertificatesService;
use GuzzleHttp\Psr7\Stream;
use MailerApi\Service\MailerService;

class MotTestCertificatesServiceTest extends \PHPUnit_Framework_TestCase
{

    private $repository;
    private $storageService;
    private $mailerService;

    private $emailData = ["email" => "nope@yep.com", "firstName" => "Nope", "familyName" => "Yep"];


    /**
     * @var AuthorisationServiceMock
     */
    private $authorisationService;


    public function setUp()
    {
        $this->repository = XMock::of(MotTestRecentCertificateRepository::class);
        $this->authorisationService = new AuthorisationServiceMock();
        $this->storageService = XMock::of(CertificateStorageService::class, ['getCertificateContent', 'getFriendlyCertificateName']);
        $this->mailerService = XMock::of(MailerService::class, ['send', 'validate']);
    }


    public function testCertificate_givenAccessGranted_shouldRetrieve()
    {
        $recentCertId = 5;
        $model = $this->recentCertificateModel();

        $this->repository->expects($this->once())->method('getById')->with($recentCertId)->willReturn($model);
        $this->authorisationService->grantedAtSite(
            PermissionAtSite::RECENT_CERTIFICATE_PRINT,
            $model->getVtsId()
        );
        $result = $this->service()->getCertificateDetails($recentCertId);

        $this->assertEquals($model->getRecipientFirstName(), $result->getRecipientFirstName());
        $this->assertEquals($model->getRecipientFamilyName(), $result->getRecipientFamilyName());
        $this->assertEquals($model->getRecipientEmail(), $result->getRecipientEmailAddress());
    }

    /**
     * @expectedException \DvsaCommon\Exception\UnauthorisedException
     */
    public function testGetCertificate_givenAccessDenied_shouldThrowException()
    {
        $model = $this->recentCertificateModel();
        $recentCertId = 5;
        $this->repository->expects($this->once())->method('getById')->with($recentCertId)->willReturn($model);
        $this->service()->getCertificateDetails($recentCertId);
    }

    public function testSendEmailAndSaveData_givenValidData_shouldSendCorrectlyAndSaveData()
    {
        $model = $this->recentCertificateModel();
        $id = 1; $name = "someCertName";
        $mockStream = XMock::of(Stream::class, ['detach']);

        $this->repository->expects($this->once())->method('getById')->with($id)->willReturn($model);
        $this->storageService->expects($this->once())->method('getCertificateContent')->with($id)->willReturn(["Body" => $mockStream]);
        $this->storageService->expects($this->once())->method('getFriendlyCertificateName')->with($id)->willReturn($name);
        $this->authorisationService->grantedAtSite(
            PermissionAtSite::RECENT_CERTIFICATE_PRINT,
            $model->getVtsId()
        );
        $this->mailerService->expects($this->once())->method('validate')->willReturn(true);
        $this->mailerService->expects($this->once())->method('send')->willReturn(true);
        $this->repository->expects($this->once())->method('save');
        $mockStream->expects($this->once())->method('detach');


        $this->assertTrue($this->service()->sendCertificateToCustomerAndSaveEmailData($id, $this->emailData));
        $this->assertEquals($this->emailData["email"], $model->getRecipientEmail());
        $this->assertEquals($this->emailData["firstName"], $model->getRecipientFirstName());
        $this->assertEquals($this->emailData["familyName"], $model->getRecipientFamilyName());
    }

    public function testSendEmail_givenSendNotSuccessful_dataShouldNotBeSaved()
    {
        $model = $this->recentCertificateModel();
        $id = 1; $name = "someCertName";
        $mockStream = XMock::of(Stream::class, ['detach']);

        $this->repository->expects($this->once())->method('getById')->with($id)->willReturn($model);
        $this->storageService->expects($this->once())->method('getCertificateContent')->with($id)->willReturn(["Body" => $mockStream]);
        $this->storageService->expects($this->once())->method('getFriendlyCertificateName')->with($id)->willReturn($name);
        $this->authorisationService->grantedAtSite(
            PermissionAtSite::RECENT_CERTIFICATE_PRINT,
            $model->getVtsId()
        );
        $this->mailerService->expects($this->once())->method('validate')->willReturn(true);
        $this->mailerService->expects($this->once())->method('send')->willReturn(false);
        $this->repository->expects($this->exactly(0))->method('save');

        $this->assertFalse($this->service()->sendCertificateToCustomerAndSaveEmailData($id, $this->emailData));
        $this->assertNotEquals($this->emailData["email"], $model->getRecipientEmail());
        $this->assertNotEquals($this->emailData["firstName"], $model->getRecipientFirstName());
        $this->assertNotEquals($this->emailData["familyName"], $model->getRecipientFamilyName());
    }

    /**
     * @return MotTestRecentCertificate
     */
    private function recentCertificateModel()
    {
        $firstName = "first Name";
        $familyName = "family Name";
        $email = "Email";
        $vtsId = 6;

        $model = (new MotTestRecentCertificate());
        $status = new MotTestStatus();
        $status->setName(MotTestStatusName::PASSED);
        $model->setStatus($status);
        $model->setRecipientFirstName($firstName);
        $model->setRecipientFamilyName($familyName);
        $model->setRecipientEmail($email);
        $model->setVtsId($vtsId);

        return $model;
    }

    private function service()
    {
        return new MotTestCertificatesService($this->repository, $this->authorisationService, $this->storageService, $this->mailerService);
    }
}
