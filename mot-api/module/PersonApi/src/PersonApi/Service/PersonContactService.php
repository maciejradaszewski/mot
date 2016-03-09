<?php

namespace PersonApi\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use DvsaAuthorisation\Service\AuthorisationService;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Enum\PersonContactTypeCode;
use DvsaCommonApi\Service\AbstractService;
use DvsaCommonApi\Service\Exception\DataValidationException;
use DvsaEntities\Entity\Email;
use DvsaEntities\Repository\PersonContactRepository;
use OrganisationApi\Service\Mapper\PersonContactMapper;
use PersonApi\Helper\PersonDetailsChangeNotificationHelper;
use PersonApi\Service\Validator\PersonalDetailsValidator;
use Zend\Authentication\AuthenticationService;

class PersonContactService extends AbstractService
{
    /**
     * @var PersonContactRepository
     */
    private $personContactRepository;
    /**
     * @var EntityRepository
     */
    private $emailRepository;
    /**
     * @var PersonContactMapper
     */
    private $personContactMapper;
    /**
     * @var PersonalDetailsValidator
     */
    private $personalDetailsValidator;
    /**
     * @var AuthenticationService
     */
    private $authenticationService;
    /**
     * @var AuthorisationService
     */
    private $authorisationService;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var PersonDetailsChangeNotificationHelper
     */
    private $notificationHelper;

    public function __construct(
        PersonContactRepository $repository,
        PersonContactMapper $mapper,
        EntityRepository $emailRepository,
        PersonalDetailsValidator $validator,
        AuthenticationService $authenticationService,
        AuthorisationService $authorisationService,
        EntityManager $em,
        PersonDetailsChangeNotificationHelper $notificationHelper
    ) {
        parent::__construct($em);

        $this->personContactRepository = $repository;
        $this->personContactMapper = $mapper;
        $this->emailRepository = $emailRepository;
        $this->personalDetailsValidator = $validator;
        $this->authenticationService = $authenticationService;
        $this->authorisationService = $authorisationService;
        $this->em = $em;
        $this->notificationHelper = $notificationHelper;
    }

    /**
     * @param $personId
     *
     * @return \DvsaCommon\Dto\Person\PersonContactDto
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     */
    public function getForPersonId($personId)
    {
        $contact = $this->personContactRepository->getHydratedByTypeCode($personId, PersonContactTypeCode::PERSONAL);
        $dto = $this->personContactMapper->toDto($contact);

        return $dto;
    }

    /**
     * @param int   $personId
     * @param array $data
     *
     * @return \DvsaCommon\Dto\Person\PersonContactDto
     * @throws DataValidationException
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     */
    public function updateEmailForPersonId($personId, $data)
    {
        if ($this->authenticationService->getIdentity()->getUserId() != $personId) {
            $this->authorisationService->assertGranted(PermissionInSystem::PROFILE_EDIT_OTHERS_EMAIL_ADDRESS);
        }

        if (!isset($data['emails'][0])
            || !$this->personalDetailsValidator->validateEmail($data['emails'][0])
        ) {
            $exception = new DataValidationException();
            $exception->addError('Email Address not Valid', 1);
            throw $exception;
        }

        $contact = $this->personContactRepository->getHydratedByTypeCode($personId, PersonContactTypeCode::PERSONAL);
        $contactDetails = $contact->getDetails();
        $emails = $contactDetails->getEmails();
        if ($emails->isEmpty()) {
            $email = new Email();
            $email->setEmail($data['emails'][0]);
            $email->setContact($contactDetails);
            $email->setIsPrimary(1);
            $emails->add($email);
            $this->personContactRepository->persist($email);
        } else {
            foreach ($emails as $emailIter => $email) {
                $email->setEmail($data['emails'][$emailIter]);
                $this->personContactRepository->persist($email);
            }
        }
        $this->em->flush();

        if ($this->authenticationService->getIdentity()->getUserId() != $personId) {
            $person = $this->findPerson($personId);
            $this->notificationHelper->sendChangedPersonalDetailsNotification($person);
        }

        $dto = $this->personContactMapper->toDto($contact);
        return $dto;
    }
}
