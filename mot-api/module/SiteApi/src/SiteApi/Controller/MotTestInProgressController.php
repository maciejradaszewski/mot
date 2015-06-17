<?php

namespace SiteApi\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use SiteApi\Service\MotTestInProgressService;

/**
 * Class MotTestInProgressController
 *
 * @package SiteApi\Controller
 */
class MotTestInProgressController extends AbstractDvsaRestfulController
{

    public function get($siteId)
    {
        $service = $this->getMotTestInProgressService();

        return ApiResponse::jsonOk($service->getAllForSite($siteId));
    }

    /**
     * @return MotTestInProgressService
     */
    private function getMotTestInProgressService()
    {
        return $this->getServiceLocator()->get(MotTestInProgressService::class);
    }
}
