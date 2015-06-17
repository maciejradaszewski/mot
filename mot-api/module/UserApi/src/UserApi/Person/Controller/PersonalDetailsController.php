<?php

namespace UserApi\Person\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use UserApi\Person\Service\PersonalDetailsService;

/**
 * Class PersonalDetailsController
 */
class PersonalDetailsController extends AbstractDvsaRestfulController
{
    public function get($personId)
    {
        $service = $this->getPersonalDetailsService();
        $personalDetailsObj = $service->get($personId);

        return ApiResponse::jsonOk($personalDetailsObj->toArray());
    }

    public function update($personId, $data)
    {
        $service = $this->getPersonalDetailsService();
        $personalDetailsObj = $service->update($personId, $data);

        return ApiResponse::jsonOk($personalDetailsObj->toArray());
    }

    /**
     * @return PersonalDetailsService
     */
    protected function getPersonalDetailsService()
    {
        /** @var $this \Zend\ServiceManager\ServiceLocatorAwareInterface */
        return $this->getServiceLocator()->get(PersonalDetailsService::class);
    }
}
