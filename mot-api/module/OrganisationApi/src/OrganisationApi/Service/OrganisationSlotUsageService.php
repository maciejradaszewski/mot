<?php

namespace OrganisationApi\Service;

use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaEntities\DqlBuilder\SearchParam\OrgSlotUsageParam;
use DvsaEntities\Repository\SiteRepository;
use OrganisationApi\Model\OutputFormat\OutputFormatOrganisationSlotUsage;

/**
 * class OrganisationSlotUsageService
 *
 * @author Jakub Igla <jakub.igla@valtech.co.uk>
 */
class OrganisationSlotUsageService
{

    protected $repository;

    /** @var $authService MotAuthorisationServiceInterface */
    protected $authService;

    public function __construct(
        SiteRepository $repository,
        MotAuthorisationServiceInterface $authService
    ) {
        $this->authService = $authService;
        $this->repository  = $repository;
    }

    public function getSearchParams()
    {
        return new OrgSlotUsageParam();
    }

    /**
     * Provide output formats for Mot Tests for rendering as data tables and standard objects.
     * @param $searchParams
     *
     * @return OutputFormatOrganisationSlotUsage
     * @throws
     */
    public function getOutputFormat($searchParams)
    {
        return new OutputFormatOrganisationSlotUsage($searchParams);
    }

    /**
     * Performs the actual search using the repository
     *
     * @param OrgSlotUsageParam                 $params
     * @param OutputFormatOrganisationSlotUsage $format
     *
     * @return mixed
     */
    public function getList(OrgSlotUsageParam $params, OutputFormatOrganisationSlotUsage $format)
    {
        $this->authService->assertGrantedAtOrganisation(PermissionAtOrganisation::AE_SLOTS_USAGE_READ, $params->getOrganisationId());

        return $this->repository->searchOrgSlotUsage($params, $format);
    }

    /**
     * @param  int    $orgId
     * @param  string $start
     * @param  string $end
     * @return mixed
     */
    public function getSlotUsage($orgId, $start, $end)
    {
        $this->authService->assertGrantedAtOrganisation(PermissionAtOrganisation::AE_SLOTS_USAGE_READ, $orgId);

        return $this->repository->getSlotUsage($orgId, $start, $end);
    }
}
