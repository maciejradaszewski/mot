<?php

namespace OrganisationApi\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use OrganisationApi\Service\SiteService;

/**
 * Class SiteController
 * @package OrganisationApi\Controller
 */
class SiteController extends AbstractDvsaRestfulController
{
    public function getList()
    {
        $organisationId = $this->params()->fromRoute('organisationId');

        $service = $this->getSiteService();

        return ApiResponse::jsonOk($service->getListForOrganisation($organisationId));
    }

    /**
     * @return SiteService
     */
    private function getSiteService()
    {
        return $this->getServiceLocator()->get(SiteService::class);
    }
}
