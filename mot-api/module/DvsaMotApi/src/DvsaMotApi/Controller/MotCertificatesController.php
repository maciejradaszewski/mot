<?php

namespace DvsaMotApi\Controller;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use DvsaCommonApi\Service\Exception\BadRequestException;
use DvsaMotApi\Service\MotTestCertificatesService;
use Zend\Form\Element\DateTime;

class MotCertificatesController extends AbstractDvsaRestfulController
{

    protected $motTestCertificateService;

    public function __construct(MotTestCertificatesService $motTestService)
    {
        $this->motTestCertificateService = $motTestService;
    }

    /**
     * @return \Zend\View\Model\JsonModel
     * @throws BadRequestException
     * @throws \DvsaCommon\Exception\UnauthorisedException
     */
    public function getList()
    {
        $vtsId = $this->getRequest()->getQuery('vtsId');
        if (!ctype_digit($vtsId)) {
            throw new BadRequestException(
                "You must specify a valid Site Id",
                BadRequestException::ERROR_CODE_INVALID_DATA
            );
        }
        $certificates = $this->motTestCertificateService->getCertificatesByVtsId($vtsId);

        return ApiResponse::jsonOk($certificates);
    }

    /**
     * @param mixed $id
     * @return \Zend\View\Model\JsonModel
     */
    public function get($id)
    {
        $certificate = $this->motTestCertificateService->getCertificateDetails($id);

        return ApiResponse::jsonOk($certificate);
    }
}
