<?php

namespace SiteApi\Service\Mapper;

use DvsaCommon\Dto\Site\SiteSearchDto;
use DvsaCommonApi\Service\Mapper\AbstractApiMapper;
use DvsaEntities\Entity\Site;
use OrganisationApi\Service\Mapper\ContactMapper;

class SiteSearchMapper extends AbstractApiMapper
{
    /** @var  ContactMapper */
    private $contactMapper;

    public function __construct()
    {
        $this->contactMapper = new ContactMapper();
    }

    /**
     * @param array $site
     *
     * @return SiteSearchDto
     */
    public function toDto($site)
    {
        /** @var SiteSearchDto $dto */
        $dto = new SiteSearchDto();

        $dto
            ->setId($site['id'])
            ->setSiteNumber($site['site_number'])
            ->setSiteName($site['name'])
            ->setSiteType($site['name'])
            ->setSiteVehicleClass($site['roles'])
            ->setSiteTown($site['town'])
            ->setSitePostcode($site['postcode']);

        return $dto;
    }

    /**
     * @param Site[] $sites
     *
     * @return SiteSearchDto[]
     */
    public function manyToDto($sites)
    {
        return parent::manyToDto($sites);
    }
}
