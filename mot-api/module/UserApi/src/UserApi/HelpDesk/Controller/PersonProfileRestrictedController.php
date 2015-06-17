<?php

namespace UserApi\HelpDesk\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use UserApi\HelpDesk\Service\HelpDeskPersonService;

/**
 * Class PersonProfileRestrictedController
 * @package UserApi\HelpDesk\Controller
 */
class PersonProfileRestrictedController extends AbstractDvsaRestfulController
{
    /**
     * @param int $personId
     * @return \Zend\View\Model\JsonModel
     */
    public function get($personId)
    {
        $service = $this->getHelpDeskPersonService();
        $person = $service->getPersonProfile($personId, true);

        return ApiResponse::jsonOk($person->toArray());
    }

    /**
     * @return HelpDeskPersonService
     */
    private function getHelpDeskPersonService()
    {
        return $this->getServiceLocator()->get(HelpDeskPersonService::class);
    }
}
