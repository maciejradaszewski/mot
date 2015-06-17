<?php

namespace SiteApi\Service;

use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\Assertion\UpdateVtsAssertion;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Enum\AuthorisationForTestingMotStatusCode;
use DvsaCommon\Enum\BrakeTestTypeCode;
use DvsaCommon\Enum\PhoneContactTypeCode;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaCommon\Enum\SiteTypeCode;
use DvsaCommon\Enum\VehicleClassCode;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\TypeCheck;
use DvsaCommonApi\Error\Message as ErrorMessage;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommonApi\Service\AbstractService;
use DvsaCommonApi\Service\ContactDetailsService;
use DvsaCommonApi\Service\Exception\BadRequestException;
use DvsaCommonApi\Service\Exception\BadRequestExceptionWithMultipleErrors;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaEntities\Entity\BrakeTestType;
use DvsaEntities\Entity\Site;
use DvsaEntities\Entity\SiteContactType;
use DvsaEntities\Entity\SiteType;
use DvsaEntities\Entity\NonWorkingDayCountry;
use DvsaEntities\Repository;
use DvsaEntities\Repository\SiteContactTypeRepository;
use DvsaEntities\Repository\SiteRepository;
use DvsaEntities\Repository\SiteTypeRepository;
use DvsaEntities\Repository\NonWorkingDayCountryRepository;
use SiteApi\Model\SiteNumberGenerator;
use SiteApi\Service\Mapper\SiteBusinessRoleMapMapper;
use SiteApi\Service\Mapper\SiteMapper;
use SiteApi\Service\Mapper\VtsMapper;
use SiteApi\Service\Validator\SiteValidatorBuilder;
use Zend\Http\Request;

/**
 * Service which creates/edits new VTS.
 */
class SiteService extends AbstractService
{
    use ExtractSiteTrait;

    const SITE_NUMBER_QUERY_PARAMETER = 'siteNumber';
    const SITE_NUMBER_REQUIRED_DISPLAY_MESSAGE = 'You need to enter a Site Number to perform the search';
    const SITE_NUMBER_INVALID_DATA_DISPLAY_MESSAGE = 'Site number should contain alphanumeric characters only';

    /** @var AuthorisationServiceInterface $authService */
    protected $authService;
    /** @var SiteRepository */
    private $repository;
    /** @var SiteTypeRepository */
    private $siteTypeRepository;
    /** @var NonWorkingDayCountryRepository */
    private $nonWorkingDayCountryRepository;
    private $brakeTestTypeRepository;
    private $siteValidatorBuilder;
    /** @var ContactDetailsService */
    private $contactService;
    /** @var SiteContactTypeRepository */
    private $siteContactTypeRepository;

    /** @var VtsMapper */
    private $vtsMapper;
    /** @var SiteMapper */
    private $siteMapper;

    /** @var XssFilter */
    private $xssFilter;

    /**
     * @var UpdateVtsAssertion
     */
    private $updateVtsAssertion;

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param \DvsaEntities\Repository\SiteRepository $repository
     * @param \DvsaEntities\Repository\SiteContactTypeRepository $siteContactTypeRepository
     * @param \DvsaEntities\Repository\BrakeTestTypeRepository $brakeTestTypeRepository
     * @param $objectHydrator
     * @param \DvsaAuthorisation\Service\AuthorisationServiceInterface $authService
     * @param \SiteApi\Service\Mapper\SiteBusinessRoleMapMapper $positionMapper
     * @param \DvsaCommonApi\Service\ContactDetailsService $contactService
     * @param \DvsaCommonApi\Filter\XssFilter $xssFilter
     * @param UpdateVtsAssertion $updateVtsAssertion
     */
    public function __construct(
        EntityManager $entityManager,
        SiteTypeRepository $siteTypeRepository,
        SiteRepository $repository,
        SiteContactTypeRepository $siteContactTypeRepository,
        Repository\BrakeTestTypeRepository $brakeTestTypeRepository,
        NonWorkingDayCountryRepository $nonWorkingDayCountryRepository,
        $objectHydrator,
        AuthorisationServiceInterface $authService,
        SiteBusinessRoleMapMapper $positionMapper,
        ContactDetailsService $contactService,
        XssFilter $xssFilter,
        UpdateVtsAssertion $updateVtsAssertion
    ) {
        parent::__construct($entityManager);
        $this->siteTypeRepository = $siteTypeRepository;
        $this->repository = $repository;
        $this->brakeTestTypeRepository = $brakeTestTypeRepository;
        $this->siteContactTypeRepository = $siteContactTypeRepository;
        $this->nonWorkingDayCountryRepository = $nonWorkingDayCountryRepository;

        $this->objectHydrator = $objectHydrator;
        $this->authService = $authService;
        $this->positionMapper = $positionMapper;
        $this->contactService = $contactService;
        $this->xssFilter      = $xssFilter;
        $this->updateVtsAssertion = $updateVtsAssertion;

        $this->vtsMapper = new VtsMapper();
        $this->siteMapper = new SiteMapper();

        $this->siteValidatorBuilder = new SiteValidatorBuilder($updateVtsAssertion);
    }

    /**
     * @param array $data
     * @return array
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     */
    public function create($data)
    {
        $this->authService->assertGranted(PermissionInSystem::VEHICLE_TESTING_STATION_CREATE);

        $validator = $this->siteValidatorBuilder->buildCreateValidator();
        $data      = $this->xssFilter->filterMultiple($data);
        $validator->validate($data);

        /** @var SiteType $siteType */
        $siteType = $this->siteTypeRepository->getByCode(SiteTypeCode::VEHICLE_TESTING_STATION);
        /** @var BrakeTestType $brakeTestType */
        $brakeTestType = $this->brakeTestTypeRepository->getByCode(BrakeTestTypeCode::ROLLER);

        $nonWorkingDayCountry = ArrayUtils::tryGet($data, 'nonWorkingDayCountry');

        $site = new Site();
        $site = $this->hydrateSite($site, $data);

        $site->setType($siteType);
        $site->setDefaultBrakeTestClass1And2($brakeTestType);
        $site->setDefaultServiceBrakeTestClass3AndAbove($brakeTestType);
        $site->setDefaultParkingBrakeTestClass3AndAbove($brakeTestType);

        if (empty($nonWorkingDayCountry) === false) {
            $nonWorkingDayCountry = $this->getNonWorkingDayCountry($nonWorkingDayCountry);
            $site->setNonWorkingDayCountry($nonWorkingDayCountry);
        }

        $this->setBusinessContact($site, $data);
        $this->setCorrespondenceContact($site, $data);

        $this->repository->save($site);

        $siteNumberGenerator = new SiteNumberGenerator();
        $site->setSiteNumber($siteNumberGenerator->generate($site->getId()));
        $this->repository->save($site);

        return ['id' => $site->getId(), 'siteNumber' => $site->getSiteNumber()];
    }

    /**
     * @param $id
     * @param array $data
     * @return array
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     * @deprecated VM-7285
     */
    public function update($id, array $data)
    {
        $this->updateVtsAssertion->assertGranted($id);

        $validator = $this->siteValidatorBuilder->buildEditValidator($id);
        $data      = $this->xssFilter->filterMultiple($data);
        $validator->validate($data);

        $site = $this->repository->get($id);

        if ($this->updateVtsAssertion->canUpdateName($id)) {
            $site = $this->hydrateSite($site, $data);
        }

        if ($this->updateVtsAssertion->canUpdateBusinessDetails($id)) {
            $this->setBusinessContact($site, $data);
        }

        if ($this->updateVtsAssertion->canUpdateCorrespondenceDetails($id)) {
            $this->setCorrespondenceContact($site, $data);
        }

        $this->repository->save($site);

        return ['id' => $site->getId()];
    }

    public function getVehicleTestingStationData($id, $isNeedDto = false)
    {
        $this->authService->assertGrantedAtSite(PermissionAtSite::VEHICLE_TESTING_STATION_READ, $id);

        /** @var Site $site */
        $site = $this->repository->find((int)$id);
        if (!$site) {
            throw new NotFoundException('Site', $id);
        }

        if ($isNeedDto === true) {
            return $this->vtsMapper->toDto($site);
        }

        return $this->extractVehicleTestingStation($site);
    }

    /**
     * @param $id int the site id we want to load back
     *
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     *
     * @return Array
     */
    public function getSiteData($id, $isNeedDto = false)
    {
        $this->authService->assertGrantedAtSite(PermissionAtSite::VEHICLE_TESTING_STATION_READ, $id);

        /** @var \DvsaEntities\Entity\Site $site */
        $site = $this->repository->find((int)$id);
        if (!$site) {
            throw new NotFoundException('Site', $id);
        }

        if ($isNeedDto === true) {
            return $this->siteMapper->toDto($site);
        }

        return $this->extractSite($site);
    }

    public function getSiteAuthorisedClasses($siteId)
    {
        $approvedVehicleClasses = $this->extractApprovedVehicleClasses($this->getSiteData($siteId));
        return $approvedVehicleClasses;
    }

    /**
     * Fetch a single Vehicle Testing Station by site number
     * - an alternative to getting it by ID.
     *
     * @param $siteNumber
     *
     * @return array
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     */
    public function getVehicleTestingStationDataBySiteNumber($siteNumber, $isNeedDto = false)
    {
        /** @var Site $site */
        $site = $this->repository->findOneBy(['siteNumber' => strtoupper(trim($siteNumber))]);
        if (!$site) {
            throw new NotFoundException('Site', $siteNumber);
        }

        $this->authService->assertGrantedAtSite(
            PermissionAtSite::VEHICLE_TESTING_STATION_READ, $site->getId()
        );

        if ($isNeedDto === true) {
            return $this->vtsMapper->toDto($site);
        }

        return $this->extractVehicleTestingStation($site);
    }

    public function findVehicleTestingStationsByPartialSiteNumber($partialSiteNumber, $maxResults)
    {
        //$this->authService->assertGranted(PermissionAtOrganisation::VEHICLE_TESTING_STATION_READ);

        $fieldErrors = [];
        $mainErrors = [];

        if (!TypeCheck::isAlphaNumeric($partialSiteNumber)) {
            $fieldErrors[] = new ErrorMessage(
                self::SITE_NUMBER_INVALID_DATA_DISPLAY_MESSAGE,
                BadRequestException::ERROR_CODE_INVALID_DATA,
                ['siteNumber' => null]
            );
        }

        // Send back any errors we have found
        if ($fieldErrors) {
            throw new BadRequestExceptionWithMultipleErrors($mainErrors, $fieldErrors);
        }

        $preparedPartialSiteNumber = $this->toUpperCaseStripOutNonAlphaNumeric($partialSiteNumber);
        $results = $this->repository
            ->findVehicleTestingStationsByPartialSiteNumber($preparedPartialSiteNumber, $maxResults);

        return $this->extractVehicleTestingStations($results);
    }

    /**
     * @param \DvsaEntities\Entity\Site $site
     * @param $data
     * @return \DvsaEntities\Entity\Site
     */
    private function hydrateSite(Site $site, $data)
    {
        if (isset($data['name'])) {
            $site->setName($data['name']);
        }

        return $site;
    }

    private function toUpperCaseStripOutNonAlphaNumeric($string)
    {
        return strtoupper(preg_replace("/[^a-zA-Z0-9]+/", "", $string));
    }

    private function setBusinessContact(Site $site, $data)
    {
        $businessContact = $this->contactService->create($data, PhoneContactTypeCode::BUSINESS, true);

        /** @var SiteContactType $siteContactType */
        $siteContactType = $this->siteContactTypeRepository->getByCode(SiteContactTypeCode::BUSINESS);

        $site->setContact($businessContact, $siteContactType);
    }

    private function setCorrespondenceContact(Site $site, $data)
    {
        $data = ArrayUtils::removePrefixFromKeys($data, 'correspondence');

        $correspondenceContact = $this->contactService->create(
            $data,
            PhoneContactTypeCode::BUSINESS,
            true
        );

        /** @var SiteContactType $siteContactType */
        $siteContactType = $this->siteContactTypeRepository->getByCode(SiteContactTypeCode::CORRESPONDENCE);

        $site->setContact($correspondenceContact, $siteContactType);
    }

    /**
     * @param $countryCode
     * @return NonWorkingDayCountry
     */
    private function getNonWorkingDayCountry($countryCode)
    {
        return $this->nonWorkingDayCountryRepository->getOneByCode($countryCode);
    }

    private function extractApprovedVehicleClasses($approvedVehicleClasses)
    {
        $vtsApprovedClasses = [];
        foreach ($approvedVehicleClasses['approvedVehicleClasses'] as $approvedClass) {
            $vtsApprovedClasses[] = $approvedClass->getName();
        }

        $result = [];
        $allClasses = VehicleClassCode::getAll();
        foreach ($allClasses as $class)
        {
            $result['class'. $class] =  in_array($class, $vtsApprovedClasses) ? AuthorisationForTestingMotStatusCode::QUALIFIED : null ;
        }

        return $result;
    }
}
