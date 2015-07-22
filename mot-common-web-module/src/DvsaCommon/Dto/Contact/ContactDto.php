<?php

namespace DvsaCommon\Dto\Contact;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Enum\PhoneContactTypeCode;
use DvsaCommon\Utility\ArrayUtils;

/**
 * Class ContactDto
 *
 * @package DvsaCommon\Dto\Contact
 */
class ContactDto extends AbstractDataTransferObject
{
    /** @var AddressDto */
    private $address;
    /** @var PhoneDto[] */
    private $phones = [];
    /** @var EmailDto[] */
    private $emails = [];
    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param AddressDto $address
     *
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setEmails($emails)
    {
        $this->emails = $emails;

        return $this;
    }

    public function getEmails()
    {
        return $this->emails;
    }

    /**
     * @param $phones PhoneDto[]
     *
     * @return $this
     */
    public function setPhones($phones)
    {
        $this->phones = $phones;

        return $this;
    }

    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * @return PhoneDto
     */
    public function getPrimaryPhone()
    {
        return ArrayUtils::firstOrNull(
            $this->phones,
            function (PhoneDto $phone) {
                return $phone->isPrimary()
                    && $phone->getContactType() !== PhoneContactTypeCode::FAX;
            }
        );
    }

    public function getPrimaryPhoneNumber()
    {
        $primaryPhone = $this->getPrimaryPhone();

        return $primaryPhone ? $primaryPhone->getNumber() : null;
    }

    /**
     * @return EmailDto
     */
    public function getPrimaryEmail()
    {
        return ArrayUtils::firstOrNull(
            $this->emails,
            function (EmailDto $email) {
                return $email->isPrimary();
            }
        );
    }

    public function getPrimaryEmailAddress()
    {
        $primaryEmail = $this->getPrimaryEmail();

        return $primaryEmail ? $primaryEmail->getEmail() : null;
    }

    /**
     * @return PhoneDto
     */
    public function getPrimaryFax()
    {
        return ArrayUtils::firstOrNull(
            $this->phones,
            function (PhoneDto $fax) {
                return $fax->isPrimary() && $fax->getContactType() === PhoneContactTypeCode::FAX;
            }
        );
    }

    public function getPrimaryFaxNumber()
    {
        $primaryFax = $this->getPrimaryFax();

        return $primaryFax ? $primaryFax->getNumber() : null;
    }

    /**
     * @param ContactDto $dtoA
     * @param ContactDto $dtoB
     *
     * @return bool
     */
    public static function isEquals($dtoA, $dtoB)
    {
        return (
            ($dtoA === null && $dtoB === null)
            || (
                $dtoA instanceof ContactDto
                && $dtoB instanceof ContactDto
                && AddressDto::isEquals($dtoA->getAddress(), $dtoB->getAddress())
                && PhoneDto::isEquals($dtoA->getPrimaryPhone(), $dtoB->getPrimaryPhone())
                && PhoneDto::isEquals($dtoA->getPrimaryFax(), $dtoB->getPrimaryFax())
                && EmailDto::isEquals($dtoA->getPrimaryEmail(), $dtoB->getPrimaryEmail())
            )
        );
    }
}
