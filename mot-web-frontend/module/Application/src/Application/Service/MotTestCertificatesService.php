<?php

namespace Application\Service;

use Doctrine\Entity;
use DvsaCommon\UrlBuilder\UrlBuilder;
use DvsaCommon\Utility\ArrayUtils;
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

    private $pageSize;

    /**
     * @param restClient $restClient
     * @param int $pageSize
     */
    public function __construct($restClient, $pageSize)
    {
        $this->restClient = $restClient;
        $this->pageSize = $pageSize;
    }

    public function getMOTCertificate($certificateId)
    {
        $url = (new UrlBuilder())->motRecentCertificate($certificateId)->toString();

        $result = $this->restClient->get($url);

        return isset($result['data']) ? $result['data'] : [];
    }

    public function getMOTCertificates($vtsId, $page)
    {
        if ($page < 1) {
            $page = 1;
        }

        $firstResult = ($page - 1) * $this->pageSize;

        $url = (new UrlBuilder())
            ->motTestCertificates($vtsId)
            ->queryParams(["firstResult" => $firstResult, "maxResult" => $this->pageSize])
            ->toString();

        $result = $this->restClient->get($url);
        $data = isset($result['data']) ? $result['data'] : [];

        $certificates = ArrayUtils::tryGet($data, "items", []);
        $totalItemsCount = ArrayUtils::tryGet($data, "totalItemsCount", 0);
        $paginator = new PaginatorService($certificates, $totalItemsCount, $page, $this->pageSize);

        return ["certificates" => $certificates, "paginator" => $paginator];
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
