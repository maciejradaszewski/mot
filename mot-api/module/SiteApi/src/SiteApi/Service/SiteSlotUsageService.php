<?php

namespace SiteApi\Service;

use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaEntities\DqlBuilder\SearchParam\SiteSlotUsageParam;
use DvsaEntities\Repository\SiteRepository;
use SiteApi\Model\OutputFormat\OutputFormatSiteSlotUsage;

/**
 * Class SiteSlotUsageService
 *
 * @author Jakub Igla <jakub.igla@valtech.co.uk>
 */
class SiteSlotUsageService
{
    private $authorisationService;
    protected $repository;

    public function __construct(SiteRepository $repository, MotAuthorisationServiceInterface $authorisationService)
    {
        $this->repository = $repository;
        $this->authorisationService = $authorisationService;
    }

    public function getSearchParams()
    {
        return new SiteSlotUsageParam();
    }

    /**
     * Provide output formats for Mot Tests for rendering as data tables and standard objects.
     * @param $searchParams
     *
     * @return OutputFormatSiteSlotUsage
     */
    public function getOutputFormat($searchParams)
    {
        return new OutputFormatSiteSlotUsage($searchParams);
    }

    /**
     * Performs the actual search using the repository
     *
     * @param SiteSlotUsageParam  $params
     * @param OutputFormatSiteSlotUsage $format
     *
     * @return mixed
     */
    public function getList(SiteSlotUsageParam $params, OutputFormatSiteSlotUsage $format)
    {
        $this->authorisationService->assertGrantedAtSite(PermissionAtSite::SITE_SLOTS_USAGE_READ, $params->getVtsId());

        return $this->repository->searchSiteSlotUsage($params, $format);
    }

    /**
     * @param int $siteId
     * @param string $start
     * @param string $end
     * @return mixed
     */
    public function getSlotUsage($siteId, $start, $end)
    {
        $this->authorisationService->assertGrantedAtSite(PermissionAtSite::SITE_SLOTS_USAGE_READ, $siteId);

        return $this->repository->getVtsSlotUsage($siteId, $start, $end);
    }
}
