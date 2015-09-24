<?php

namespace DvsaMotApi\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaEntities\Repository\MotTestRecentCertificateRepository;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaEntities\Entity\MotTestRecentCertificate;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaAuthorisation\Service\AuthorisationService;
use DvsaCommon\Exception\UnauthorisedException;

/**
 * Class CertificateStorageService
 * @package DvsaMotApi\Service
 */
class CertificateStorageService
{
    protected $serviceLocator;
    private $s3;
    private $motTestRecentCertificateRepository;
    private $auth;

    /**
     * @param AmazonS3Service $s3
     * @param MotTestRecentCertificateRepository $motTestRecentCertificateRepository
     * @param AuthorisationService $auth
     */
    public function __construct(
        AmazonS3Service $s3,
        MotTestRecentCertificateRepository $motTestRecentCertificateRepository,
        AuthorisationService $auth
    )
    {
        $this->s3 = $s3;
        $this->motTestRecentCertificateRepository = $motTestRecentCertificateRepository;
        $this->auth = $auth;
    }

    /**
     * @param $motRecentCertificateId
     * @return \Psr\Http\Message\RequestInterface
     * @throws NotFoundException
     * @throws UnauthorisedException
     */
    public function getSignedPdfLink($motRecentCertificateId)
    {
        $result = $this->motTestRecentCertificateRepository->getById($motRecentCertificateId);

        if (!$this->auth->assertGrantedAtSite(PermissionAtSite::RECENT_CERTIFICATE_PRINT, $result->getVtsId())) {
            throw new UnauthorisedException('You are not authorised to print this certificate');
        }

        return $this->s3->getSignedUrlByKey($result->getCertificateStorageKey());
    }

    /**
     * @param $motRecentCertificateId
     * @return \Aws\ResultInterface|mixed
     * @throws NotFoundException
     * @throws UnauthorisedException
     */
    public function getCertificateContent($motRecentCertificateId)
    {
        $result = $this->motTestRecentCertificateRepository->getById($motRecentCertificateId);

        if (!$this->auth->assertGrantedAtSite(PermissionAtSite::RECENT_CERTIFICATE_PRINT, $result->getVtsId())) {
            throw new UnauthorisedException('You are not authorised to print this certificate');
        }
        
        return $this->s3->getObject($result->getCertificateStorageKey());
    }

    /**
     * @param $motRecentCertificateId
     * @return string
     * @throws NotFoundException
     */
    public function getFriendlyCertificateName($motRecentCertificateId)
    {
        $cert = $this->motTestRecentCertificateRepository->getById($motRecentCertificateId);

        $name = "%s-%s-%s";
        return sprintf($name, strlen($cert->getRegistration()) > 0? $cert->getRegistration() : $cert->getVin()
            , $cert->getStatus()->getName(), $cert->getId());
    }
}
