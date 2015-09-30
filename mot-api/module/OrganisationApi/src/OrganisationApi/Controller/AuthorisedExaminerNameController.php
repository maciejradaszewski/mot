<?php

namespace OrganisationApi\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use OrganisationApi\Service\AuthorisedExaminerService;
use Zend\Http\Response;

class AuthorisedExaminerNameController extends AbstractDvsaRestfulController
{
    /**
     * @var AuthorisedExaminerService
     */
    private $authorisedExaminerService;

    public function __construct(
        AuthorisedExaminerService $authorisedExaminerService
    ) {
        $this->authorisedExaminerService = $authorisedExaminerService;
        $this->setIdentifierName('organisationId');
    }

    public function get($id)
    {
        return ApiResponse::jsonOk($this->authorisedExaminerService->getName($id));
    }
}
