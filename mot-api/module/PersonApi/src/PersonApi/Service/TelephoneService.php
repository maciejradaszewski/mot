<?php

namespace PersonApi\Service;

use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonApi\Service\AbstractService;
use Doctrine\ORM\EntityManager;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommon\Validator\TelephoneNumberValidator;
use DvsaEntities\Entity\ContactDetail;
use DvsaEntities\Entity\PersonContact;
use DvsaCommonApi\Service\ContactDetailsService;
use DvsaCommon\Dto\Contact\ContactDto;
use DvsaCommon\Dto\Contact\PhoneDto;
use DvsaCommon\Enum\PhoneContactTypeCode;
use DvsaCommonApi\Service\Exception\BadRequestException;

class TelephoneService extends AbstractService
{
    /** @var ContactDetailsService */
    private $contactDetailsService;

    /** @var TelephoneNumberValidator */
    private $validator;

    /** @var XssFilter  */
    private $xssFilter;

    /**
     * @param EntityManager            $entityManager
     * @param ContactDetailsService    $contactDetailsService
     * @param TelephoneNumberValidator $validator
     * @param XssFilter                $xssFilter
     */
    public function __construct(
        EntityManager $entityManager,
        ContactDetailsService $contactDetailsService,
        TelephoneNumberValidator $validator
    ) {
        parent::__construct($entityManager);

        $this->contactDetailsService = $contactDetailsService;
        $this->validator = $validator;
    }

    /**
     * @param int    $personId
     * @param string $newPhoneNumber
     */
    public function updatePhoneNumber($personId, $newPhoneNumber)
    {
        $person = $this->findPerson($personId);

        /** @var PersonContact $personContactDetails */
        $personContact = ArrayUtils::firstOrNull($person->getContacts());
        if (null !== $personContact) {

            if (null === $newPhoneNumber) {
                $newPhoneNumber = '';
            }

            if (!$this->validator->isValid($newPhoneNumber)) {
                throw new BadRequestException('validation failed', 400);
            }

            /** @var ContactDetail $personContactDetails */
            $personContactDetails = $personContact->getDetails();

            $contactDto = new ContactDto();
            $newPhone = (new PhoneDto())
                ->setIsPrimary(true)
                ->setNumber($newPhoneNumber)
                ->setContactType(PhoneContactTypeCode::PERSONAL);

            $contactDto->setPhones([$newPhone]);

            return $this->contactDetailsService->patchContactDetailsFromDto(
                $contactDto,
                $personContactDetails
            );
        }
    }
}
