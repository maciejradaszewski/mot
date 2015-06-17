<?php

namespace UserApi\Person\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use UserApi\Person\Service\PersonService;

/**
 * Class PersonCurrentMotTestController
 *
 * @package UserApi\Person\Controller
 */
class PersonCurrentMotTestController extends AbstractDvsaRestfulController
{
    public function get($personId)
    {
        $service = $this->getCurrentMotTestService();
        $data = $service->getCurrentMotTestIdByPersonId($personId);

        return ApiResponse::jsonOk($data);
    }

    /**
     * @return PersonService
     */
    protected function getCurrentMotTestService()
    {
        return $this->getServiceLocator()->get(PersonService::class);
    }
}
