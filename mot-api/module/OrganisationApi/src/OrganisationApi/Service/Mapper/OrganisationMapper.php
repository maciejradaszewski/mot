<?php
namespace OrganisationApi\Service\Mapper;

use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Dto\Common\AuthForAeStatusDto;
use DvsaCommon\Dto\Organisation\AuthorisedExaminerAuthorisationDto;
use DvsaCommon\Dto\Organisation\OrganisationContactDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonApi\Service\Mapper\AbstractApiMapper;
use DvsaEntities\Entity\Organisation;
use DvsaEntities\Entity\OrganisationContact;
use DvsaEntities\Repository\CompanyTypeRepository;
use DvsaEntities\Repository\OrganisationTypeRepository;

/**
 * Class OrganisationMapper
 *
 * @package OrganisationApi\Service\Mapper
 */
class OrganisationMapper extends AbstractApiMapper
{
    private $contactMapper;

    /** @var OrganisationTypeRepository */
    private $organisationTypeRepository;
    /** @var CompanyTypeRepository */
    private $companyTypeRepository;

    public function __construct(
        OrganisationTypeRepository $organisationTypeRepository,
        CompanyTypeRepository $companyTypeRepository
    ) {
        $this->organisationTypeRepository = $organisationTypeRepository;
        $this->companyTypeRepository      = $companyTypeRepository;
        $this->contactMapper              = new ContactMapper();
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

            $aeAuthorisation = new AuthorisedExaminerAuthorisationDto();
            $aeAuthorisation
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

    public function mapToObject(Organisation $organisation, array $data)
    {
        $companyType = ArrayUtils::tryGet($data, 'companyType');
        if ($companyType) {
            $type = $this->companyTypeRepository->findOneByName($companyType);
            $organisation->setCompanyType($type);
        }

        $organisationType = ArrayUtils::tryGet($data, 'organisationType');
        if ($organisationType) {
            $type = $this->organisationTypeRepository->findOneByName($organisationType);
            $organisation->setOrganisationType($type);
        }

        $organisation
            ->setName(ArrayUtils::tryGet($data, 'organisationName', ''))
            ->setTradingAs(ArrayUtils::tryGet($data, 'tradingAs', ''))
            ->setRegisteredCompanyNumber(ArrayUtils::tryGet($data, 'registeredCompanyNumber', ''))
            ->setSlotsWarning(ArrayUtils::tryGet($data, 'slotsWarning', 0));
    }
}
