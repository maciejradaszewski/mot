<?php

namespace DvsaMotApi\Controller;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use DvsaMotApi\Service\CertificateStorageService;
use Zend\Form\Element\DateTime;

class MotCertificatePdfController extends AbstractDvsaRestfulController
{

    protected $storageService;

    public function __construct(CertificateStorageService $storageService) {
        $this->storageService = $storageService;
    }

    /**
     * @return \Zend\View\Model\JsonModel
     * @throws NotFoundException
     */
    public function get($id)
    {
        $url = $this->storageService->getSignedPdfLink($id);

        return ApiResponse::jsonOk((string) $url->getUri());
    }
}
