<?php

namespace DvsaMotApi\Controller;


use DvsaCommon\Dto\Mailer\MailerDto;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaMotApi\Service\CertificateStorageService;
use DvsaMotApi\Service\MotTestCertificatesService;
use MailerApi\Logic\CustomerCertificateMail;
use MailerApi\Model\Attachment;
use MailerApi\Service\MailerService;

class MotCertificateEmailController extends AbstractDvsaRestfulController
{

    protected $certificatesService;

    /**
     * MotCertificateEmailController constructor.
     * @param MotTestCertificatesService $motTestCertificatesService
     */
    public function __construct(MotTestCertificatesService $motTestCertificatesService)
    {
        $this->certificatesService = $motTestCertificatesService;
    }

    /**
     * @param mixed $data
     * @return \Zend\View\Model\JsonModel
     */
    public function create($data)
    {
        $cid = $this->params()->fromRoute('id');
        if($this->certificatesService->sendCertificateToCustomerAndSaveEmailData($cid, $data)) {
            return ApiResponse::jsonOk($data);
        }

        return ApiResponse::jsonError($data);
    }
}