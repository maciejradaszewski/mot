<?php

namespace DvsaMotApi\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Dto\Mailer\MailerDto;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaEntities\Repository\MotTestRecentCertificateRepository;
use DvsaMotApi\Service\Mapper\MotTestRecentCertificateMapper;
use MailerApi\Logic\CustomerCertificateMail;
use MailerApi\Model\Attachment;
use MailerApi\Model\PdfAttachment;
use MailerApi\Service\MailerService;

class MotTestCertificatesService
{

    private $recentCertificatesRepository;
    private $auth;
    private $storageService;
    private $mailerService;

    /**
     * @param MotTestRecentCertificateRepository $recentCertificatesRepository
     * @param MotAuthorisationServiceInterface $authorisationService
     * @param CertificateStorageService $storageService
     * @param MailerService $mailerService
     */
    public function __construct(
        MotTestRecentCertificateRepository $recentCertificatesRepository,
        MotAuthorisationServiceInterface $authorisationService,
        CertificateStorageService $storageService,
        MailerService $mailerService
    ) {
        $this->recentCertificatesRepository = $recentCertificatesRepository;
        $this->auth = $authorisationService;
        $this->storageService = $storageService;
        $this->mailerService = $mailerService;
    }

    /**
     * @param $vtsId
     * @return array
     * @throws UnauthorisedException
     */
    public function getCertificatesByVtsId($vtsId)
    {
        $this->auth->assertGrantedAtSite(PermissionAtSite::RECENT_CERTIFICATE_PRINT, $vtsId);

        $results = [];
        $certs = $this->recentCertificatesRepository->findByVtsId($vtsId);

        if (is_array($certs)) {
            $mapper = new MotTestRecentCertificateMapper();
            foreach ($certs as $cert) {
                $results[] = $mapper->mapMotRecentCertificate($cert);
            }
        }

        return $results;
    }

    /**
     * Retrieves a recent certificate data @see MotTestRecentCertificatesDto
     * @param string $id
     * @return \DvsaCommon\Dto\Common\MotTestRecentCertificatesDto
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     */
    public function getCertificateDetails($id)
    {
        $cert = $this->recentCertificatesRepository->getById($id);
        $this->auth->assertGrantedAtSite(PermissionAtSite::RECENT_CERTIFICATE_PRINT, $cert->getVtsId());

        return (new MotTestRecentCertificateMapper())->mapMotRecentCertificate($cert);
    }


    /**
     * @param $cid
     * @param $data
     * @return bool
     * @throws UnauthorisedException
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     */
    public function sendCertificateToCustomerAndSaveEmailData($cid, $data)
    {
        $cert = $this->recentCertificatesRepository->getById($cid);
        $this->auth->assertGrantedAtSite(PermissionAtSite::RECENT_CERTIFICATE_PRINT, $cert->getVtsId());

        $pdf = $this->storageService->getCertificateContent($cid);
        //using detach() here to get a hold of the raw stream that is necessary for the MIME Message
        $data["attachment"] = new PdfAttachment($pdf["Body"]->detach(), $this->storageService->getFriendlyCertificateName($cid));

        $mail = new CustomerCertificateMail($this->mailerService);
        $mailData = new MailerDto();
        $mailData->setData($data);

        if($mail->send($mailData)) {
            $cert->setRecipientEmail($data["email"]);
            $cert->setRecipientFirstName($data["firstName"]);
            $cert->setRecipientFamilyName($data["familyName"]);

            $this->recentCertificatesRepository->save($cert);
            return true;
        }

        return false;
    }
}
