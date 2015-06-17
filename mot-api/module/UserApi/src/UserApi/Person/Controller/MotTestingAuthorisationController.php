<?php

namespace UserApi\Person\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use UserApi\Person\Service\PersonalAuthorisationForMotTestingService;

/**
 * Personal authorisation for testing mot
 */
class MotTestingAuthorisationController extends AbstractDvsaRestfulController
{
    public function get($id)
    {
        /** @var $service PersonalAuthorisationForMotTestingService */
        $service = $this->getServiceLocator()->get(PersonalAuthorisationForMotTestingService::class);

        return ApiResponse::jsonOk($service->getPersonalTestingAuthorisation($id)->toArray());
    }

    public function update($id, $data)
    {
        /** @var $service PersonalAuthorisationForMotTestingService */
        $service = $this->getServiceLocator()->get(PersonalAuthorisationForMotTestingService::class);

        return ApiResponse::jsonOk($service->updatePersonalTestingAuthorisationGroup($id, $data)->toArray());
    }
}
