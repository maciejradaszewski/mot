<?php
namespace OrganisationApi\Controller;

use DvsaCommon\Utility\DtoHydrator;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use OrganisationApi\Service\AuthorisedExaminerService;
use Zend\View\Model\JsonModel;

/**
 * Api controller for AuthorisedExaminers
 */
class AuthorisedExaminerController extends AbstractDvsaRestfulController
{
    const AE_NUMBER_PARAM = 'number';

    public function create($data)
    {
        $service = $this->getAuthorisedExaminerService();

        return ApiResponse::jsonOk($service->create($data));
    }

    public function get($id)
    {
        $service = $this->getAuthorisedExaminerService();

        return ApiResponse::jsonOk($service->get($id));
    }

    /**
     * Given an identifying string, locate the associated AE (Organisation)
     * or return a 404 indicating no match or an error occurred.
     *
     * @return JsonModel
     */
    public function getAuthorisedExaminerByNumberAction()
    {
        $service  = $this->getAuthorisedExaminerService();
        $aeNumber = $this->params()->fromRoute('number', $this->getRequest()->getQuery(self::AE_NUMBER_PARAM));

        $orgData = $service->getByNumber($aeNumber);

        return ApiResponse::jsonOk($orgData);
    }

    public function update($id, $data)
    {
        $data = $this->sanitize($data);

        $dto    = DtoHydrator::jsonToDto($data);
        $result = $this->getAuthorisedExaminerService()->update($id, $dto);

        return ApiResponse::jsonOk($result);
    }

    /**
     * @return AuthorisedExaminerService
     */
    private function getAuthorisedExaminerService()
    {
        return $this->getServiceLocator()->get(AuthorisedExaminerService::class);
    }

    private function sanitize($data)
    {
        $sanitiseKeys = [
            'addressLine1',
            'addressLine2',
            'addressLine3',
            'fullAddressString',
        ];

        foreach($data as $key=>$value) {
            if (is_array($value)) {
                $data[$key] = $this->sanitize($value);
            }

            if (in_array($key, $sanitiseKeys)) {
                if (!is_array($value)) {
                    $data[ $key ] = htmlentities($value);
                }
            }
        }

        return $data;
    }
}
