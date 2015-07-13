<?php

namespace UserApi\HelpDesk\Mapper;

use DvsaCommon\Constants\PersonContactType;
use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Dto\Person\PersonHelpDeskProfileDto;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\PersonContact;
use DvsaEntities\Mapper\AddressMapper;

/**
 * Class PersonHelpDeskProfileMapper.
 */
class PersonHelpDeskProfileMapper
{
    /**
     * @var AddressMapper
     */
    private $addressMapper;

    public function __construct()
    {
        $this->addressMapper = new AddressMapper();
    }

    /**
     * @param Person $person
     *
     * @return PersonHelpDeskProfileDto
     */
    public function fromPersonEntityToDto(Person $person)
    {
        $dto = new PersonHelpDeskProfileDto();

        $dto->setTitle($person->getTitle() && $person->getTitle()->getId() ? $person->getTitle()->getName() : '');
        $dto->setUserName($person->getUsername());
        $dto->setFirstName($person->getFirstName());
        $dto->setMiddleName($person->getMiddleName());
        $dto->setLastName($person->getFamilyName());
        $dto->setDateOfBirth(DateTimeApiFormat::date($person->getDateOfBirth()));
        if ($person->getDrivingLicence() !== null) {
            $dto->setDrivingLicenceNumber($person->getDrivingLicence()->getLicenceNumber());
        } else {
            $dto->setDrivingLicenceNumber('');
        }
        $this->mapPersonalAddress($person, $dto);

        return $dto;
    }

    /**
     * @param Person                   $person
     * @param PersonHelpDeskProfileDto $dto
     */
    private function mapPersonalAddress(Person $person, PersonHelpDeskProfileDto $dto)
    {
        foreach ($person->getContacts() as $contact) {
            if ($contact->getType()->getName() === PersonContactType::PERSONAL) {
                // Address is optional
                $address = $contact->getDetails()->getAddress();
                $dto->setAddress($address ? $this->addressMapper->toDto($address) : null);

                // Phone is optional
                $phone = $contact->getDetails()->getPrimaryPhone();
                $dto->setTelephone($phone ? $phone->getNumber() : null);

                $this->mapEmail($contact, $dto);
                break;
            }
        }
    }

    /**
     * @param PersonContact            $contact
     * @param PersonHelpDeskProfileDto $dto
     */
    private function mapEmail(PersonContact $contact, PersonHelpDeskProfileDto $dto)
    {
        foreach ($contact->getDetails()->getEmails() as $email) {
            $dto->setEmail($email->getEmail());
            break;
        }
    }
}
