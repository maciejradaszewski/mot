<?php

namespace OrganisationApi\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\AuthorisationForAuthorisedExaminerStatusCode;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\Enum\PhoneContactTypeCode;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommonApi\Service\AbstractService;
use DvsaCommonApi\Service\ContactDetailsService;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaEntities\Entity\AuthForAeStatus;
use DvsaEntities\Entity\AuthorisationForAuthorisedExaminer;
use DvsaEntities\Entity\ContactDetail;
use DvsaEntities\Entity\Organisation;
use DvsaEntities\Entity\OrganisationContact;
use DvsaEntities\Entity\OrganisationContactType;
use DvsaEntities\Entity\Person;
use DvsaEntities\Repository\AuthForAeStatusRepository;
use DvsaEntities\Repository\CompanyTypeRepository;
use DvsaEntities\Repository\OrganisationContactTypeRepository;
use DvsaEntities\Repository\OrganisationRepository;
use DvsaEntities\Repository\OrganisationTypeRepository;
use DvsaEntities\Repository\PersonRepository;
use OrganisationApi\Service\Mapper\OrganisationMapper;
use OrganisationApi\Service\Validator\AuthorisedExaminerValidator;

/**
 * Service to deal with creating, editing and viewing AuthorisedExaminers
 */
class AuthorisedExaminerService extends AbstractService
{
    const FIELD_CORRESPONDENCE_CONTACT_DETAILS_SAME = 'correspondenceContactDetailsSame';

    /** @var AuthorisationServiceInterface  */
    private $authService;

    /** @var OrganisationService  */
    private $organisationService;

    /** @var ContactDetailsService  */
    private $contactDetailService;

    /** @var AuthorisedExaminerValidator */
    private $validator;

    /** @var OrganisationRepository  */
    private $organisationRepository;

    /** @var PersonRepository $personRepository */
    private $personRepository;

    /** @var OrganisationTypeRepository $organisationTypeRepository */
    private $organisationTypeRepository;

    /** @var CompanyTypeRepository $companyTypeRepository */
    private $companyTypeRepository;

    /** @var OrganisationContactTypeRepository */
    private $organisationContactTypeRepository;

    /** @var OrganisationMapper $mapper */
    private $mapper;

    /**  @var \DvsaCommonApi\Filter\XssFilter */
    protected $xssFilter;

    /**  @var AuthForAeStatusRepository */
    private $authForAeStatusRepository;

    /**
     * @param \Doctrine\ORM\EntityManager                                    $entityManager
     * @param \DvsaAuthorisation\Service\AuthorisationServiceInterface       $authService
     * @param \OrganisationApi\Service\OrganisationService                   $organisationService
     * @param \DvsaCommonApi\Service\ContactDetailsService                   $contactDetailService
     * @param \DvsaEntities\Repository\OrganisationRepository                $organisationRepository
     * @param \DvsaEntities\Repository\PersonRepository                      $personRepository
     * @param \DvsaEntities\Repository\OrganisationTypeRepository            $organisationTypeRepository
     * @param \DvsaEntities\Repository\CompanyTypeRepository                 $companyTypeRepository
     * @param OrganisationContactTypeRepository                              $organisationContactTypeRepository
     * @param \OrganisationApi\Service\Validator\AuthorisedExaminerValidator $validator
     * @param \OrganisationApi\Service\Mapper\OrganisationMapper             $mapper
     * @param \DvsaEntities\Repository\AuthForAeStatusRepository             $authForAeStatusRepository
     * @param \DvsaCommonApi\Filter\XssFilter                                $xssFilter
     */
    public function __construct(
        EntityManager $entityManager,
        AuthorisationServiceInterface $authService,
        OrganisationService $organisationService,
        ContactDetailsService $contactDetailService,
        OrganisationRepository $organisationRepository,
        PersonRepository $personRepository,
        OrganisationTypeRepository $organisationTypeRepository,
        CompanyTypeRepository $companyTypeRepository,
        OrganisationContactTypeRepository $organisationContactTypeRepository,
        AuthorisedExaminerValidator $validator,
        OrganisationMapper $mapper,
        AuthForAeStatusRepository $authForAeStatusRepository,
        XssFilter $xssFilter
    ) {
        parent::__construct($entityManager);

        $this->authService                       = $authService;
        $this->organisationService               = $organisationService;
        $this->contactDetailService              = $contactDetailService;
        $this->organisationRepository            = $organisationRepository;
        $this->personRepository                  = $personRepository;
        $this->organisationTypeRepository        = $organisationTypeRepository;
        $this->companyTypeRepository             = $companyTypeRepository;
        $this->organisationContactTypeRepository = $organisationContactTypeRepository;
        $this->validator                         = $validator;
        $this->mapper                            = $mapper;
        $this->authForAeStatusRepository         = $authForAeStatusRepository;
        $this->xssFilter                         = $xssFilter;
    }

    /**
     * @param $data
     * @return array
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     */
    public function create($data)
    {
        $this->authService->assertGranted(PermissionInSystem::AUTHORISED_EXAMINER_CREATE);

        $data = $this->xssFilter->filterMultiple($data);
        $this->validator->validate($data);

        $organisation = new Organisation();

        $contactDetails     = $this->createContactDetailsEntity($data);
        $businessOrgContact = new OrganisationContact($contactDetails, $this->getRegisteredCompanyContactType());

        $organisation = $this->organisationService->persist($organisation, $data, $businessOrgContact);

        if (!$this->areBothContactDetailsTheSame($data)) {
            $correspondenceData = ArrayUtils::removePrefixFromKeys($data, 'correspondence');
            $contactDetails     = $this->createContactDetailsEntity($correspondenceData);

            $type                              = $this->getCorrespondenceContactType();
            $correspondenceOrganisationContact = new OrganisationContact($contactDetails, $type);
            $organisation->addContact($correspondenceOrganisationContact);
        }

        /** @var AuthForAeStatus $status */
        $status = $this->authForAeStatusRepository->getByCode(AuthorisationForAuthorisedExaminerStatusCode::APPROVED);

        $authorisedExaminer = new AuthorisationForAuthorisedExaminer();
        $authorisedExaminer
            ->setValidFrom(new \DateTime())
            ->setStatus($status)
            ->setOrganisation($organisation);

        if (isset($data['authorisedExaminerReference'])) {
            $authorisedExaminer->setNumber($data['authorisedExaminerReference']);
        }

        $this->entityManager->persist($authorisedExaminer);
        $this->entityManager->flush();

        return ['id' => $organisation->getId()];
    }

    /**
     * @param int             $orgId
     * @param OrganisationDto $orgDto
     *
     * @return array
     */
    public function update($orgId, OrganisationDto $orgDto)
    {
        $this->authService->assertGrantedAtOrganisation(PermissionAtOrganisation::AUTHORISED_EXAMINER_UPDATE, $orgId);

        $orgDto = $this->xssFilter->filter($orgDto);

        $orgEntity = $this->organisationRepository->getAuthorisedExaminer($orgId);

        $this->updateOrganisationFromDto($orgDto, $orgEntity);

        //  --  create/update contact   --
        /** @var \DvsaCommon\Dto\Organisation\OrganisationContactDto $contactDto */
        foreach ($orgDto->getContacts() as $contactDto) {
            $contactType = $this->organisationContactTypeRepository->getByCode($contactDto->getType());
            $contact     = $orgEntity->getContactByType($contactType->getCode());

            $contactDetails = null;
            if ($contact instanceof OrganisationContact) {
                $contactDetails = $contact->getDetails();
            }

            $contactDetails = $this->contactDetailService->setContactDetailsFromDto(
                $contactDto,
                ($contactDetails instanceof ContactDetail ? $contactDetails : new ContactDetail())
            );

            //  --  set to org --
            $orgEntity->setContact($contactDetails, $contactType);
        }

        $this->organisationRepository->save($orgEntity);

        return ['id' => $orgEntity->getId()];
    }

    private function updateOrganisationFromDto(OrganisationDto $dto, Organisation $orgEntity)
    {
        $val = $dto->getName();
        if ($val !== null) {
            $orgEntity->setName($val);
        }

        $val = $dto->getRegisteredCompanyNumber();
        if ($val !== null) {
            $orgEntity->setRegisteredCompanyNumber($val);
        }

        $val = $dto->getTradingAs();
        if ($val !== null) {
            $orgEntity->setTradingAs($val);
        }

        $orgType = $dto->getOrganisationType();
        if ($orgType !== null) {
            $type = $this->organisationTypeRepository->findOneByName($orgType);

            $orgEntity->setOrganisationType($type);
        }

        return $orgEntity;
    }

    /**
     * @param array $data
     *
     * @return \DvsaEntities\Entity\ContactDetail
     */
    private function createContactDetailsEntity($data)
    {
        return $this->contactDetailService->create($data, PhoneContactTypeCode::BUSINESS, true);
    }

    /**
     * @param Organisation $org
     * @param array        $data
     *
     * @return Organisation
     */
    public function hydrateOrganisation(Organisation $org, $data)
    {
        $companyType = ArrayUtils::tryGet($data, 'companyType');
        if ($companyType) {
            $type = $this->companyTypeRepository->findOneByName($companyType);
            $org->setCompanyType($type);
        }

        $organisationType = ArrayUtils::tryGet($data, 'organisationType');
        if ($organisationType) {
            $type = $this->organisationTypeRepository->findOneByName($organisationType);
            $org->setOrganisationType($type);
        }

        $org->setName(ArrayUtils::tryGet($data, 'organisationName'));
        $org->setTradingAs(ArrayUtils::tryGet($data, 'tradingAs'));
        $org->setRegisteredCompanyNumber(ArrayUtils::tryGet($data, 'registeredCompanyNumber'));

        return $org;
    }

    /**
     * @param $id
     *
     * @return OrganisationDto
     * @throws NotFoundException
     */
    public function get($id)
    {
        if (!$this->authService->isGranted(PermissionInSystem::AUTHORISED_EXAMINER_READ_FULL)) {
            $this->authService->assertGrantedAtOrganisation(PermissionAtOrganisation::AUTHORISED_EXAMINER_READ, $id);
        }

        $organisation = $this->organisationRepository->getAuthorisedExaminer($id);

        $data = $this->mapper->toDto($organisation);

        return $data;
    }

    /**
     * @param string $aeNumber Organisation the identifying number code
     *
     * @return OrganisationDto
     * @throws NotFoundException
     */
    public function getByNumber($aeNumber)
    {
        $this->authService->assertGranted(PermissionInSystem::AUTHORISED_EXAMINER_LIST);

        $data = null;

        $data = $this->entityManager
            ->getRepository(AuthorisationForAuthorisedExaminer::class)
            ->findOneBy(['number' => $aeNumber]);

        if (is_null($data)) {
            throw new NotFoundException('Organisation ID: ' . $aeNumber);
        }

        return $this->mapper->toDto($data->getOrganisation());
    }

    /**
     * @param $personId
     *
     * @return OrganisationDto[]
     */
    public function getAuthorisedExaminersForPerson($personId)
    {
        $this->authService->assertGranted(PermissionInSystem::AUTHORISED_EXAMINER_LIST);

        /** @var Person $manager */
        $manager       = $this->personRepository->get($personId);
        $organisations = $manager->findAuthorisedExaminers();

        foreach ($organisations as $org) {
            $this->authService->assertGrantedAtOrganisation(
                PermissionAtOrganisation::AUTHORISED_EXAMINER_READ,
                $org->getId()
            );
        }

        $data = $this->mapper->manyToDto($organisations);

        return $data;
    }

    /**
     * @param $username
     *
     * @return mixed
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     * @deprecated Should be removed at all cost.
     */
    public function getAuthorisedExaminerData($username)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('username', 'username');
        $rsm->addScalarResult('slots', 'slots');
        $rsm->addScalarResult('slots_in_use', 'slotsInUse');

        /*
         * Should really use ORM here but it would require
         * adding inverse relationships to both VTS, AE, and MOT
         */
        $authorisedExaminer = $this->entityManager
            ->createNativeQuery(
                "select
                    ae.id,
                    p.username,
                    count(mt.id) as slots_in_use
                from
                    mot.authorisation_for_authorised_examiner ae
                    join mot.organisation o on ae.organisation_id = o.id
                    join mot.organisation_business_role_map obrm on o.id = obrm.organisation_id
                    join mot.person p on obrm.person_id = p.id
                    left outer join mot.site vts on o.id = vts.organisation_id
                    left outer join mot.mot_test mt on vts.id = mt.site_id and mt.status = '" . MotTestStatusName::ACTIVE . "'
                    left outer join mot_test_type mtt on mt.mot_test_type_id = mtt.id
                        and mtt.code not in (
                        '" . MotTestTypeCode::ROUTINE_DEMONSTRATION_TEST . "',
                        '" . MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING . "'
                        )
                WHERE
                    p.username = ?
                GROUP BY
                    ae.id,
                    p.username",
                $rsm
            )
            ->setParameter(1, $username)
            ->getResult();

        if (!$authorisedExaminer) {
            throw new NotFoundException('AuthorisationForAuthorisedExaminer', $username);
        }

        // Don't bother hydrating, just return array for direct serialization.
        return current($authorisedExaminer);
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    private function areBothContactDetailsTheSame($data)
    {
        return true === (bool) $data[self::FIELD_CORRESPONDENCE_CONTACT_DETAILS_SAME];
    }

    /**
     * @return OrganisationContactType
     */
    private function getRegisteredCompanyContactType()
    {
        return $this->organisationContactTypeRepository->getByCode(OrganisationContactTypeCode::REGISTERED_COMPANY);
    }

    /**
     * @return OrganisationContactType
     */
    private function getCorrespondenceContactType()
    {
        return $this->organisationContactTypeRepository->getByCode(OrganisationContactTypeCode::CORRESPONDENCE);
    }
}
