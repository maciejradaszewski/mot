<?php

namespace OrganisationApi\Service\Mapper;

use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Dto\Organisation\OrganisationSiteLinkDto;
use DvsaCommonApi\Service\Mapper\AbstractApiMapper;
use DvsaEntities\Entity\OrganisationSiteMap;

class OrganisationSiteLinkMapper extends AbstractApiMapper
{
    private $contactMapper;

    public function __construct()
    {
        $this->contactMapper = new ContactMapper();
    }

    /**
     * @param OrganisationSiteMap $orgSiteMap
     */
    public function toDto($orgSiteMap)
    {
        if (!$orgSiteMap instanceof OrganisationSiteMap) {
            return null;
        }

        $orgMapper = new OrganisationMapper();
        $siteMapper = new SiteMapper();

        $dto = new OrganisationSiteLinkDto();
        $dto
            ->setId($orgSiteMap->getId())
            ->setStatus($orgSiteMap->getStatus()->getCode())
            ->setStatusChangedOn(DateTimeApiFormat::date($orgSiteMap->getStatusChangedOn()))
            ->setSite($siteMapper->toDto($orgSiteMap->getSite()))
            ->setOrganisation($orgMapper->toDto($orgSiteMap->getOrganisation()));

        return $dto;
    }
}
