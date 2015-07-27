<?php

namespace PersonApi\Service;

use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaAuthorisation\Service\UserRoleService;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Constants\PersonContactType;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Enum\LicenceTypeCode;
use DvsaCommon\Enum\PhoneContactTypeCode;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommonApi\Service\AbstractService;
use DvsaEntities\Entity\Address;
use DvsaEntities\Entity\ContactDetail;
use DvsaEntities\Entity\CountryOfRegistration;
use DvsaEntities\Entity\Email;
use DvsaEntities\Entity\Gender;
use DvsaEntities\Entity\Licence;
use DvsaEntities\Entity\LicenceType;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\PersonContact;
use DvsaEntities\Entity\Phone;
use DvsaEntities\Entity\PhoneContactType;
use DvsaEntities\Entity\Title;
use PersonApi\Dto\PersonDetails;
use PersonApi\Service\Validator\PersonalDetailsValidator;

/**
 * Service to handle updating and getting user details.
 */
class PersonalDetailsService extends AbstractService
{
    /**
     * @var PersonalDetailsValidator
     */
    private $validator;

    /**
     * @var \DvsaCommon\Auth\MotIdentityProviderInterface
     */
    private $identityProvider;

    /**
     * @var \DvsaAuthorisation\Service\AuthorisationServiceInterface
     */
    protected $authorisationService;

    /**
     * @var \DvsaCommonApi\Filter\XssFilter
     */
    protected $xssFilter;

    /**
     * @var UserRoleService
     */
    private $roleService;

    /**
     * @param \Doctrine\ORM\EntityManager                                $entityManager
     * @param \PersonApi\Service\Validator\PersonalDetailsValidator $validator
     * @param \DvsaAuthorisation\Service\AuthorisationServiceInterface   $authorisationService
     * @param \DvsaCommon\Auth\MotIdentityProviderInterface              $identityProvider
     * @param \DvsaCommonApi\Filter\XssFilter                            $xssFilter
     * @param \DvsaAuthorisation\Service\UserRoleService                 $roleService
     */
    public function __construct(
        EntityManager $entityManager,
        PersonalDetailsValidator $validator,
        AuthorisationServiceInterface $authorisationService,
        MotIdentityProviderInterface $identityProvider,
        XssFilter $xssFilter,
        UserRoleService $roleService
    ) {
        parent::__construct($entityManager);

        $this->validator            = $validator;
        $this->authorisationService = $authorisationService;
        $this->identityProvider     = $identityProvider;
        $this->xssFilter            = $xssFilter;
        $this->roleService          = $roleService;
    }

    /**
     * @param int   $personId
     * @param array $data
     *
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     * @throws \DvsaCommon\Date\Exception\IncorrectDateFormatException
     * @throws \DvsaCommon\Exception\UnauthorisedException
     *
     * @return \PersonApi\Dto\PersonDetails
     */
    public function update($personId, $data)
    {
        $this->assertUpdateGranted($personId);

        // Strip script tags from form data (avoid XSS vulns)
        $data = $this->xssFilter->filterMultiple($data);
        $this->validator->validateContactDetails($data, true);
        $person = $this->findPerson($personId);

        $personContact = $this->updatePersonalContactDetails($person, $data);

        $this->entityManager->flush();

        return new PersonDetails(
            $person,
            $personContact->getDetails(),
            $this->getEntityHelper(),
            $this->getUserRoles($person)
        );
    }

    /**
     * @param int $personId
     *
     * @throws UnauthorisedException
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     *
     * @return PersonDetails
     */
    public function get($personId)
    {
        $person = $this->findPerson($personId);

        $this->assertViewGranted($person);

        $personContactTypeRepository = $this->entityManager->getRepository(\DvsaEntities\Entity\PersonContactType::class);
        $personContactType = $personContactTypeRepository->findOneBy(['name' => PersonContactType::PERSONAL]);

        /** @var PersonContact $contact */
        $contact = $this
            ->entityManager
            ->getRepository(PersonContact::class)
            ->findOneBy(
                [
                    'person' => $person,
                    'type' => $personContactType,
                ]
            );
        if (null === $contact) {
            $contact = $this->createContactDetailPlaceholder($person);
        }

        return new PersonDetails(
            $person, $contact->getDetails(), $this->getEntityHelper(), $this->getUserRoles($person)
        );
    }

    /**
     * Check access for current user to view profile of specified person.
     *
     * @param Person $person
     *
     * @throws UnauthorisedException
     */
    private function assertViewGranted(Person $person)
    {
        $userId = $this->identityProvider->getIdentity()->getUserId();

        if ($userId === $person->getId()) {
            return;
        }

        //  ----    check access by site    ----
        foreach ($person->findSites() as $site) {
            if ($this->authorisationService->isGrantedAtSite(
                PermissionAtSite::VTS_EMPLOYEE_PROFILE_READ, $site->getId()
            )) {
                return;
            }
        }

        //  ----    check access in organisation    --
        foreach ($person->findOrganisations() as $organisation) {
            if ($this->authorisationService->isGrantedAtOrganisation(
                PermissionAtOrganisation::AE_EMPLOYEE_PROFILE_READ, $organisation->getId()
            )) {
                return;
            }
        }

        throw new UnauthorisedException("Cannot access profiles of other users");
    }

    /**
     * @param int $personId
     *
     * @throws UnauthorisedException
     */
    private function assertUpdateGranted($personId)
    {
        if ($this->identityProvider->getIdentity()->getUserId() != $personId) {
            throw new UnauthorisedException("Cannot access profiles of other users");
        }
    }

    /**
     * @param Person $person
     *
     * @return array
     */
    private function getUserRoles(Person $person)
    {
        return $this->roleService->getDetailedRolesForPerson($person);
    }

    /**
     * Returns $person->drivingLicence or create a new one (and associate it with person) if there was no object yet.
     *
     * @param Person $person
     *
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     *
     * @return Licence
     */
    private function getDrivingLicenceOrCreateEntity(Person $person)
    {
        $drivingLicence = $person->getDrivingLicence();

        if (null === $drivingLicence) {
            $drivingLicence = new Licence();
            /** @var LicenceType $licenceType */
            $licenceType = $this->findOneByOrThrowException(
                LicenceType::class,
                ['code' => LicenceTypeCode::DRIVING_LICENCE]
            );
            $drivingLicence->setLicenceType($licenceType);
            $person->setDrivingLicence($drivingLicence);
        }

        return $drivingLicence;
    }

    /**
     * Updates Person entity with given data. Returns updated (persisted, NOT flushed) Person entity.
     *
     * @param Person $person
     * @param array  $data
     *
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     * @throws \DvsaCommon\Date\Exception\IncorrectDateFormatException
     *
     * @return Person
     */
    private function updatePersonalDetails(Person $person, $data)
    {
        /** @var Title $title */
        $title = $this->findOneByOrThrowException(Title::class, ['name' => $data['title']]);
        /** @var Gender $gender */
        $gender = $this->findOneByOrThrowException(Gender::class, ['name' => $data['gender']]);
        /** @var CountryOfRegistration $country */
        $country = $this->findOneByOrThrowException(
            CountryOfRegistration::class,
            ['code' => $data['drivingLicenceRegion']]
        );

        $drivingLicence = $this->getDrivingLicenceOrCreateEntity($person);
        $drivingLicence
            ->setCountry($country)
            ->setLicenceNumber($data['drivingLicenceNumber']);

        $person
            ->setFirstName($data['firstName'])
            ->setMiddleName($data['middleName'])
            ->setFamilyName($data['surname'])
            ->setDateOfBirth(DateUtils::toDate($data['dateOfBirth']))
            ->setTitle($title)
            ->setGender($gender);

        $this->entityManager->persist($drivingLicence);
        $this->entityManager->persist($person);

        return $person;
    }

    /**
     * @param Person $person
     * @param array  $data
     *
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     *
     * @return PersonContact
     */
    private function updatePersonalContactDetails(Person $person, $data)
    {
        $personContactTypeRepository = $this->entityManager->getRepository(\DvsaEntities\Entity\PersonContactType::class);
        $personContactType = $personContactTypeRepository->findOneBy(['name' => PersonContactType::PERSONAL]);
        /** @var $personContact PersonContact */
        $personContact = $this
            ->entityManager
            ->getRepository(PersonContact::class)
            ->findOneBy([
                'person' => $person,
                'type' => $personContactType,
            ]);
        if (!$personContact) {
            $contactDetails = $this->createContactDetail();
            $personContact  = $this->createPersonContact($person, $contactDetails);
        } else {
            $contactDetails = $personContact->getDetails();
        }

        // Update, create or remove an Address entity as this field is optional.
        $address = $contactDetails->getAddress();
        if (true === $this->validator->hasAddressData($data)) {
            if (!$address) {
                $address = new Address();
                $contactDetails->setAddress($address);
                $this->entityManager->persist($address);
            }

            $address
                ->setAddressLine1($data['addressLine1'])
                ->setAddressLine2($data['addressLine2'])
                ->setAddressLine3($data['addressLine3'])
                ->setTown($data['town'])
                ->setPostcode($data['postcode']);
        } else {
            // The user didn't provided address details, unlink it from the ContactDetails table and remove it from the
            // Address table if this entry already exists.
            $contactDetails->setAddress(null);
            if ($address) {
                $this->entityManager->remove($address);
            }
        }

        /** @var $email Email */
        $email = $this
            ->entityManager
            ->getRepository(Email::class)
            ->findOneBy([
                'contact' => $personContact->getDetails()
            ]);
        if (!$email) {
            $email = $this->createEmail($contactDetails);
        }
        $email->setEmail($data['email']);

        // Update, create or remove a Phone entity as this field is optional.
        $phone = $this
            ->entityManager
            ->getRepository(Phone::class)
            ->findOneBy(['contact' => $contactDetails]);
        if (true === $this->validator->hasPhoneData($data)) {
            if (!$phone) {
                $phone = new Phone();
                /** @var PhoneContactType $phoneContactType */
                $phoneContactType = $this
                    ->entityManager
                    ->getRepository(PhoneContactType::class)
                    ->findOneBy(['code' => PhoneContactTypeCode::PERSONAL]);
                $phone
                    ->setContact($contactDetails)
                    ->setIsPrimary(true)
                    ->setContactType($phoneContactType);
                $this->entityManager->persist($phone);
            }

            $phone->setNumber($data['phoneNumber']);
        } else {
            if ($phone) {
                // The user didn't provided a phone number remove it from the Phone table if this entry already exists.
                $this->entityManager->remove($phone);
            }
        }

        $this->entityManager->persist($email);

        return $personContact;
    }

    /**
     * Returns an empty PersonContact instance used as a placeholder for classes that require a PersonContact object.
     * It's meant to be used when a Person instance doesn't have a PersonContact associated.
     *
     * @param Person $person
     *
     * @return PersonContact
     */
    private function createContactDetailPlaceholder(Person $person)
    {
        $contactDetail     = new ContactDetail();
        $personContactTypeRepository = $this->entityManager->getRepository(\DvsaEntities\Entity\PersonContactType::class);
        $personContactType = $personContactTypeRepository->findOneBy(['name' => PersonContactType::PERSONAL]);
        $personContact     = new PersonContact($contactDetail, $personContactType, $person);

        return $personContact;
    }

    /**
     * Creates an empty ContactDetail to be populated after.
     *
     * @return ContactDetail
     */
    private function createContactDetail()
    {
        $address       = new Address();
        $contactDetail = new ContactDetail();
        $contactDetail->setAddress();

        $this->entityManager->persist($address);
        $this->entityManager->persist($contactDetail);

        return $contactDetail;
    }

    /**
     * Creates a personal PersonContactType linking Person and ContactDetail entities.
     *
     * @param Person $person
     * @param ContactDetail $contactDetail
     *
     * @return PersonContactType
     */
    private function createPersonContact(Person $person, ContactDetail $contactDetail)
    {
        $personContactTypeRepository = $this->entityManager->getRepository(\DvsaEntities\Entity\PersonContactType::class);
        $personContactType = $personContactTypeRepository->findOneBy(['name' => PersonContactType::PERSONAL]);
        $personContact     = new PersonContact($contactDetail, $personContactType, $person);

        $this->entityManager->persist($personContact);

        return $personContact;
    }

    /**
     * Creates an empty Email entity associated to a ContactDetail entity.
     *
     * @param ContactDetail $contactDetail
     *
     * @return Email
     */
    private function createEmail(ContactDetail $contactDetail)
    {
        $email = new Email();
        $email->setContact($contactDetail);

        $this->entityManager->persist($email);

        return $email;

    }
}
