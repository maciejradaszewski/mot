<?php

namespace Application\Service;

use Doctrine\Entity;
use DvsaCommon\UrlBuilder\UrlBuilder;
use Zend\Cache\Storage\StorageInterface;

/**
 * This service gets a list of the currently available PDF documents for a VTS station
 */
class MotTestCertificatesService
{
    /**
     * @var \DvsaCommon\HttpRestJson\Client
     */
    private $restClient;

    /**
     * @param restClient $restClient
     */
    public function __construct($restClient)
    {
        $this->restClient = $restClient;
    }

    public function getMOTCertificate($certificateId)
    {
        $url = (new UrlBuilder())->motRecentCertificate($certificateId)->toString();

        $result = $this->restClient->get($url);

        return isset($result['data']) ? $result['data'] : [];
    }

    public function getMOTCertificates($vtsId)
    {
        $url = (new UrlBuilder())->motTestCertificates($vtsId)->toString();

        $result = $this->restClient->get($url);

        return isset($result['data']) ? $result['data'] : [];
    }

    public function getCertificatePdfUrl($motCertificateId)
    {
        $url = (new UrlBuilder())->motPdfDownloadLink($motCertificateId);
        $result = $this->restClient->get($url);
        return isset($result['data']) ? $result['data'] : [];
    }

    public function saveEmailCertificate($certificateId, array $data)
    {
        $url = (new UrlBuilder())->motRecentCertificateEmail($certificateId)->toString();

        return $this->restClient->post($url, $data)['data'];
    }
}
