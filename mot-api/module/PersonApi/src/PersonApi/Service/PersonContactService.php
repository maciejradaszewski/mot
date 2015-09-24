<?php

namespace PersonApi\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use DvsaAuthorisation\Service\AuthorisationService;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Enum\PersonContactTypeCode;
use DvsaCommonApi\Service\Exception\DataValidationException;
use DvsaEntities\Entity\Email;
use DvsaEntities\Repository\PersonContactRepository;
use OrganisationApi\Service\Mapper\PersonContactMapper;
use PersonApi\Service\Validator\PersonalDetailsValidator;
use Zend\Authentication\AuthenticationService;

class PersonContactService
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

    public function __construct(
        PersonContactRepository $repository,
        PersonContactMapper $mapper,
        EntityRepository $emailRepository,
        PersonalDetailsValidator $validator,
        AuthenticationService $authenticationService,
        AuthorisationService $authorisationService,
        EntityManager $em
    ) {
        $this->personContactRepository = $repository;
        $this->personContactMapper = $mapper;
        $this->emailRepository = $emailRepository;
        $this->personalDetailsValidator = $validator;
        $this->authenticationService = $authenticationService;
        $this->authorisationService = $authorisationService;
        $this->em = $em;
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
        if ($this->authenticationService->getIdentity()->getUserId() !== $personId) {
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
        $dto = $this->personContactMapper->toDto($contact);

        return $dto;
    }
}
