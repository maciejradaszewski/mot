<?php
namespace OrganisationApi\Controller;

use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use OrganisationApi\Service\AuthorisedExaminerService;

/**
 * Api controller for AuthorisedExaminers
 */
class AuthorisedExaminerController extends AbstractDvsaRestfulController
{
    const AE_NUMBER_PARAM = 'number';

    /**
     * @var AuthorisedExaminerService
     */
    private $service;

    /**
     * @param AuthorisedExaminerService $service
     */
    public function __construct(AuthorisedExaminerService $service)
    {
        $this->service = $service;
    }

    public function create($data)
    {
        /** @var OrganisationDto $dto */
        $dto    = DtoHydrator::jsonToDto($data);
        $result = $this->service->create($dto);

        return ApiResponse::jsonOk($result);
    }

    public function update($id, $data)
    {
        /** @var OrganisationDto $dto */
        $dto    = DtoHydrator::jsonToDto($data);
        $result = $this->service->update($id, $dto);

        return ApiResponse::jsonOk($result);
    }

    public function get($id)
    {
        return ApiResponse::jsonOk($this->service->get($id));
    }

    /**
     * Given an identifying string, locate the associated AE (Organisation)
     * or return a 404 indicating no match or an error occurred.
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function getAuthorisedExaminerByNumberAction()
    {
        $aeNumber = $this->params()->fromRoute('number', $this->getRequest()->getQuery(self::AE_NUMBER_PARAM));

        $orgData = $this->service->getByNumber($aeNumber);

        return ApiResponse::jsonOk($orgData);
    }
}
