<?php
namespace OrganisationApi\Service\Mapper;

use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Dto\Common\AuthForAeStatusDto;
use DvsaCommon\Dto\Organisation\AuthorisedExaminerAuthorisationDto;
use DvsaCommon\Dto\Organisation\OrganisationContactDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Dto\Site\SiteDto;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommonApi\Service\Mapper\AbstractApiMapper;
use DvsaEntities\Entity\Organisation;
use DvsaEntities\Entity\OrganisationContact;
use DvsaEntities\Entity\Site;

/**
 * Class OrganisationMapper
 *
 * @package OrganisationApi\Service\Mapper
 */
class OrganisationMapper extends AbstractApiMapper
{
    private $contactMapper;

    public function __construct()
    {
        $this->contactMapper = new ContactMapper();
    }

    /**
     * @param Organisation $organisation
     *
     * @return OrganisationDto
     */
    public function toDto($organisation)
    {
        $organisationDto = new OrganisationDto();

        $organisationType = $organisation->getOrganisationType();
        if ($organisationType) {
            $organisationType = $organisationType->getName();
        }

        $companyType = $organisation->getCompanyType();
        if ($companyType) {
            $companyType = $companyType->getName();
        }

        $organisationDto
            ->setId($organisation->getId())
            ->setRegisteredCompanyNumber($organisation->getRegisteredCompanyNumber())
            ->setName($organisation->getName())
            ->setTradingAs($organisation->getTradingAs())
            ->setOrganisationType($organisationType)
            ->setCompanyType($companyType)
            ->setSlotBalance($organisation->getSlotBalance())
            ->setDataMayBeDisclosed($organisation->getDataMayBeDisclosed());

        if ($organisation->isAuthorisedExaminer()) {
            $ae       = $organisation->getAuthorisedExaminer();
            $aeStatus = $ae->getStatus();

            $statusDto = new AuthForAeStatusDto();
            if ($aeStatus) {
                $statusDto
                    ->setCode($aeStatus->getCode())
                    ->setName($aeStatus->getName());
            }

            //The assigned area *is* a Site entity
            $siteAO = $ae->getAreaOffice();
            $siteDto = new SiteDto();
            if ($siteAO) {
                $siteDto
                    ->setId($siteAO->getId())
                    ->setSiteNumber($siteAO->getSiteNumber())
                    ->setName($siteAO->getName());
            }


            $aeAuthorisation = new AuthorisedExaminerAuthorisationDto();
            $aeAuthorisation
                ->setAssignedAreaOffice($siteDto)
                ->setAuthorisedExaminerRef($ae->getNumber())
                ->setStatus($statusDto)
                ->setValidFrom(DateTimeApiFormat::date($ae->getValidFrom()))
                ->setExpiryDate(DateTimeApiFormat::date($ae->getExpiryDate()));

            $organisationDto->setAuthorisedExaminerAuthorisation($aeAuthorisation);
        }

        $contactsDtos = $this->mapContacts($organisation->getContacts());
        $organisationDto->setContacts($contactsDtos);

        return $organisationDto;
    }

    /**
     * @param $organisations
     *
     * @return OrganisationDto[]
     */
    public function manyToDto($organisations)
    {
        return parent::manyToDto($organisations);
    }

    /**
     * @param $contacts OrganisationContact[]
     *
     * @return OrganisationContactDto[]
     */
    private function mapContacts($contacts)
    {
        $hasContactType = [
            OrganisationContactTypeCode::REGISTERED_COMPANY => false,
            OrganisationContactTypeCode::CORRESPONDENCE     => false,
        ];

        $contactsDtos = [];

        foreach ($contacts as $contact) {
            $type = $contact->getType()->getCode();

            $contactDto = new OrganisationContactDto();
            $contactDto = $this->contactMapper->toDto($contact->getDetails(), $contactDto);
            $contactDto->setType($type);
            $contactsDtos[] = $contactDto;

            $hasContactType[$type] = true;
        }

        //  --  create contact if not presented --
        foreach ($hasContactType as $type => $has) {
            if (!$has) {
                $contactsDtos[] = (new OrganisationContactDto())->setType($type);
            }
        }

        return $contactsDtos;
    }
}
