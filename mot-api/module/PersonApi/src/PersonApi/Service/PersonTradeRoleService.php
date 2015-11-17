<?php

namespace PersonApi\Service;

use DvsaCommon\ApiClient\Person\PersonTradeRoles\Dto\PersonTradeRoleDto;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonApi\Formatting\AddressFormatter;
use DvsaEntities\Entity\OrganisationBusinessRoleMap;
use DvsaEntities\Entity\SiteBusinessRoleMap;
use DvsaEntities\Repository\OrganisationBusinessRoleMapRepository;
use DvsaEntities\Repository\SiteBusinessRoleMapRepository;

class PersonTradeRoleService
{
    private $organisationPositionRepository;
    private $sitePositionRepository;

    public function __construct(
        OrganisationBusinessRoleMapRepository $organisationPositionRepository,
        SiteBusinessRoleMapRepository $sitePositionRepository
    )
    {
        $this->organisationPositionRepository = $organisationPositionRepository;
        $this->sitePositionRepository = $sitePositionRepository;
    }

    public function getForPerson($personId)
    {
        $organisationPositions = $this->organisationPositionRepository->getActiveUserRoles($personId);
        $sitePositions = $this->sitePositionRepository->getActiveUserRoles($personId);

        $organisationDtos = ArrayUtils::map($organisationPositions, function (OrganisationBusinessRoleMap $position) {
            $contact = $position->getOrganisation()->getContactByType(OrganisationContactTypeCode::REGISTERED_COMPANY);
            $address = $contact ? $contact->getDetails()->getAddress() : null;
            $addressAsString = $address ? (new AddressFormatter())->format($address) : '';

            $positionDto = new PersonTradeRoleDto();
            $positionDto->setPositionId($position->getId())
                ->setWorkplaceId($position->getOrganisation()->getId())
                ->setWorkplaceName($position->getOrganisation()->getName())
                ->setRoleCode($position->getOrganisationBusinessRole()->getCode())
                ->setAddress($addressAsString);

            return $positionDto;
        });

        $siteDtos  = ArrayUtils::map($sitePositions, function (SiteBusinessRoleMap $position) {
            $contact = $position->getSite()->getContactByType(SiteContactTypeCode::BUSINESS);
            $address = $contact ? $contact->getDetails()->getAddress() : null;
            $addressAsString = $address ? (new AddressFormatter())->format($address) : '';

            $positionDto = new PersonTradeRoleDto();
            $positionDto->setPositionId($position->getId())
                ->setWorkplaceId($position->getSite()->getId())
                ->setWorkplaceName($position->getSite()->getName())
                ->setRoleCode($position->getSiteBusinessRole()->getCode())
                ->setAddress($addressAsString);

            return $positionDto;
        });

        $dtos = array_merge($organisationDtos, $siteDtos);

        return $dtos;
    }
}
