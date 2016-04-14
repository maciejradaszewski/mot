<?php
namespace SiteApi\Controller;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use SiteApi\Service\SiteSearchService;
use Zend\Http\Request;
use Zend\View\Model\JsonModel;

/**
 * Class SiteSearchController
 *
 * @package SiteApi\Controller
 */
class SiteSearchController extends AbstractDvsaRestfulController
{
    /** @var SiteSearchService */
    private $service;

    /**
     * @param SiteSearchService $service
     */
    public function __construct(SiteSearchService $service)
    {
        $this->service = $service;
    }

    /**
     * @param array $data
     * @return JsonModel
     */
    public function create($data)
    {
        return ApiResponse::jsonOk($this->service->findSites(DtoHydrator::jsonToDto($data)));
    }

    /**
     * @param mixed $number
     * @return JsonModel
     */
    public function get($number)
    {
        return ApiResponse::jsonOk($this->service->findSiteByNumber($number));
    }
}
