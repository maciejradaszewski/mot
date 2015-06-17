<?php
namespace SiteApi\Controller;

use DvsaEntities\Entity\Site;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use DvsaEntities\DqlBuilder\SearchParam\VehicleTestingStationSearchParam;
use SiteApi\Service\SiteService;
use Zend\Http\Request;
use Zend\View\Model\JsonModel;

/**
 * Class VehicleTestingStationAuthorisedClassesController
 *
 * @package SiteApi\Controller
 */
class VehicleTestingStationAuthorisedClassesController extends AbstractDvsaRestfulController
{
    public function get($vtsId)
    {
        $authorisedClasses = $this->getSiteService()->getSiteAuthorisedClasses($vtsId);

        return ApiResponse::jsonOk($authorisedClasses);
    }

    /**
     * @return SiteService
     */
    private function getSiteService()
    {
        return $this->getServiceLocator()->get(SiteService::class);
    }
}
