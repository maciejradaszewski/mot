<?php

namespace SiteApi\Controller;

use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use SiteApi\Service\SiteDetailsService;


/**
 * Controller for getting / updating site's testing facilities
 */
class SiteDetailsController extends AbstractDvsaRestfulController
{
    /**
     * @var SiteDetailsService
     */
    private $siteDetailsService;

    public function __construct(SiteDetailsService $siteDetailsService)
    {
        $this->siteDetailsService = $siteDetailsService;
    }

    public function update($siteId, $data)
    {
        /** @var VehicleTestingStationDto $dto */
        $dto = DtoHydrator::jsonToDto($data);
        $result = $this->siteDetailsService->update($siteId, $dto);

        return ApiResponse::jsonOk($result);
    }
}