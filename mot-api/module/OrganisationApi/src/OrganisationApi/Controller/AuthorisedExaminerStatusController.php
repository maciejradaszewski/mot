<?php
namespace OrganisationApi\Controller;

use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use OrganisationApi\Service\AuthorisedExaminerStatusService;

class AuthorisedExaminerStatusController extends AbstractDvsaRestfulController
{
    /**
     * @var AuthorisedExaminerStatusService
     */
    private $service;

    /**
     * @param AuthorisedExaminerStatusService $service
     */
    public function __construct(AuthorisedExaminerStatusService $service)
    {
        $this->service = $service;
    }

    public function update($id, $data)
    {
        /** @var OrganisationDto $dto */
        $dto    = DtoHydrator::jsonToDto($data);
        $result = $this->service->updateStatus($id, $dto);

        return ApiResponse::jsonOk($result);
    }

    public function getAreaOfficesAction()
    {
        return ApiResponse::jsonOk($this->service->getAllAreaOffices());
    }
}
