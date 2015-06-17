<?php
namespace UserApi\Person\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use OrganisationApi\Service\AuthorisedExaminerService;

/**
 * Returns data about AE linked with person
 */
class AuthorisedExaminerController extends AbstractDvsaRestfulController
{
    public function get($personId)
    {
        $service = $this->getAuthorisedExaminerService();

        return ApiResponse::jsonOk($service->getAuthorisedExaminersForPerson($personId));
    }

    /**
     * @return AuthorisedExaminerService
     */
    private function getAuthorisedExaminerService()
    {
        return $this->getServiceLocator()->get(AuthorisedExaminerService::class);
    }
}
