<?php

namespace UserApi\Person\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use UserApi\Person\Service\PersonService;

/**
 * Class PersonSiteCountController
 *
 */
class PersonSiteCountController extends AbstractDvsaRestfulController
{
    /**
     * @param mixed $personId
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function get($personId)
    {
        $service = $this->getPersonService();
        $data = $service->getPersonSiteCountAsTester($personId);

        return ApiResponse::jsonOk($data);
    }

    /**
     * @return PersonService
     */
    protected function getPersonService()
    {
        return $this->getServiceLocator()->get(PersonService::class);
    }
}
