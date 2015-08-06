<?php

namespace SiteApi\Service\Mapper;

use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Dto\Site\SiteAssessmentDto;
use DvsaCommon\Dto\Site\SiteCommentDto;
use DvsaCommon\Dto\Site\SiteContactDto;
use DvsaCommon\Dto\Site\SiteDto;
use DvsaCommon\Dto\Site\SiteVisitOutcomeDto;
use DvsaCommonApi\Service\Mapper\AbstractApiMapper;
use DvsaEntities\Entity\EnforcementSiteAssessment;
use DvsaEntities\Entity\Organisation;
use DvsaEntities\Entity\Site;
use DvsaEntities\Entity\SiteComment;
use DvsaEntities\Entity\SiteContact;
use OrganisationApi\Service\Mapper\ContactMapper;
use OrganisationApi\Service\Mapper\PersonMapper;

/**
 * Map data from Site Entity to SiteDto.
 *
 * @package SiteApi\Service\Mapper
 */
class SiteMapper extends AbstractApiMapper
{
    /** @var  ContactMapper */
    private $contactMapper;
    /** @var  PersonMapper */
    protected $personMapper;

    public function __construct()
    {
        $this->contactMapper = new ContactMapper();
        $this->personMapper = new PersonMapper();
    }

    /**
     * @param Site $site
     * @param SiteDto $dto
     *
     * @return SiteDto
     */
    public function toDto($site, $dto = null)
    {
        /** @var SiteDto $dto */
        $dto = ($dto !== null ? $dto : new SiteDto());

        $dto
            ->setId($site->getId())
            ->setName($site->getName())
            ->setSiteNumber($site->getSiteNumber())

            ->setIsDualLanguage($site->getDualLanguage())
            ->setIsScottishBankHoliday($site->getScottishBankHoliday())

            ->setLatitude($site->getLatitude())
            ->setLongitude($site->getLongitude())

            ->setType($site->getType()->getCode())

            ->setComments($this->mapComments($site->getSiteComments()))
            ->setContacts($this->mapContacts($site->getContacts()))

            ->setOrganisation($this->mapOrganisation($site->getOrganisation()))
            ->setAssessment($this->mapAssessment($site->getLastSiteAssessment()));

        return $dto;
    }

    /**
     * @param $sites
     *
     * @return SiteDto[]
     */
    public function manyToDto($sites)
    {
        return parent::manyToDto($sites);
    }

    /**
     * @param SiteContact[] $contacts
     *
     * @return SiteContactDto[]
     */
    private function mapContacts($contacts)
    {
        $contactsDtos = [];

        /** @var SiteContact $contact */
        foreach ($contacts as $contact) {
            /** @var  SiteContactDto $contactDto */
            $contactDto = new SiteContactDto();
            $contactDto = $this->contactMapper->toDto($contact->getDetails(), $contactDto);
            $contactDto
                ->setId($contact->getId())
                ->setType($contact->getType()->getCode());

            $contactsDtos[] = $contactDto;
        }

        return $contactsDtos;
    }

    /**
     * @param SiteComment[] $comments
     *
     * @return SiteCommentDto[]
     */
    private function mapComments($comments)
    {
        $dtos = [];

        /** @var SiteComment $comment */
        foreach ($comments as $comment) {
            $dto = (new SiteCommentDto())
                ->setId($comment->getId())
                ->setComment($comment->getComment());

            $dtos[] = $dto;
        }

        return $dtos;
    }


    /**
     * @param Organisation $organisation
     *
     * @return OrganisationDto
     */
    private function mapOrganisation($organisation)
    {
        if (!($organisation instanceof Organisation)) {
            return null;
        }

        $orgDto = new OrganisationDto();

        $organisationType = null;
        if ($organisation->getOrganisationType()) {
            $organisationType = $organisation->getOrganisationType()->getName();
        }

        $orgDto
            ->setId($organisation->getId())
            ->setRegisteredCompanyNumber($organisation->getRegisteredCompanyNumber())
            ->setName($organisation->getName())
            ->setSlotBalance($organisation->getSlotBalance())
            ->setSlotWarning($organisation->getSlotsWarning())
            ->setTradingAs($organisation->getTradingAs())
            ->setOrganisationType($organisationType);

        return $orgDto;
    }

    /**
     * @param EnforcementSiteAssessment $assessment
     *
     * @return SiteAssessmentDto
     */
    private function mapAssessment($assessment)
    {
        if (!($assessment instanceof EnforcementSiteAssessment)) {
            return null;
        }

        $visitOutcomeEntity = $assessment->getVisitOutcome();

        $visitOutcomeDto = new SiteVisitOutcomeDto();
        $visitOutcomeDto
            ->setId($visitOutcomeEntity->getId())
            ->setDescription($visitOutcomeEntity->getDescription())
            ->setPosition($visitOutcomeEntity->getPosition());

        $dto = new SiteAssessmentDto();
        $dto
            ->setId($assessment->getId())
            ->setIsAdvisoryIssued((boolean)$assessment->getAdvisoryIssued())
            ->setRepresentativeName($assessment->getAeRepresentativeName())
            ->setRepresentativePosition($assessment->getAeRepresentativePosition())
            ->setScore($assessment->getSiteAssessmentScore())
            ->setVisitDate(DateTimeApiFormat::dateTime($assessment->getVisitDate()))
            ->setVisitOutcome($visitOutcomeDto)
            ->setTester($this->personMapper->toDto($assessment->getTester()));

        return $dto;
    }
}
