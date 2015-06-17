<?php

namespace DvsaCommon\Dto\Person;

use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\TypeCheck;

/**
 * Dto for found person account
 */
class SearchPersonResultDto
{
    /** @var int */
    private $personId;
    /** @var string */
    private $firstName;
    /** @var string */
    private $lastName;
    /** @var string */
    private $middleName;
    /** @var string */
    private $dateOfBirth;
    /** @var \DvsaCommon\Dto\Contact\AddressDto */
    private $address;
    /** @var string */
    private $username;

    public function __construct($data)
    {
        TypeCheck::assertArray($data);

        $this->setPersonId(ArrayUtils::get($data, 'id'));
        $this->setFirstName(ArrayUtils::get($data, 'firstName'));
        $this->setLastName(ArrayUtils::get($data, 'lastName'));
        $this->setMiddleName(ArrayUtils::get($data, 'middleName'));
        $this->setDateOfBirth(ArrayUtils::get($data, 'dateOfBirth'));
        $this->setUsername(ArrayUtils::get($data, 'username'));

        $this->setAddress(AddressDto::fromArray($data));
    }

    /**
     * @param array $data
     *
     * @return SearchPersonResultDto[]
     */
    public static function getList($data)
    {
        TypeCheck::assertArray($data);

        $result = [];
        foreach ($data as $item) {
            $result[] = new self($item);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id'           => $this->getPersonId(),
            'firstName'    => $this->getFirstName(),
            'lastName'     => $this->getLastName(),
            'middleName'   => $this->getMiddleName(),
            'dateOfBirth'  => $this->getDateOfBirth(),
            'postcode'     => $this->getAddress()->getPostcode(),
            'addressLine1' => $this->getAddress()->getAddressLine1(),
            'addressLine2' => $this->getAddress()->getAddressLine2(),
            'addressLine3' => $this->getAddress()->getAddressLine3(),
            'addressLine4' => $this->getAddress()->getAddressLine4(),
            'town'         => $this->getAddress()->getTown(),
            'username'     => $this->getUsername()
        ];
    }

    /**
     * @return AddressDto
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param AddressDto $address
     *
     * @return SearchPersonResultDto
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return SearchPersonResultDto
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * @param string $dateOfBirth
     *
     * @return SearchPersonResultDto
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return SearchPersonResultDto
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * @param string $middleName
     *
     * @return SearchPersonResultDto
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return SearchPersonResultDto
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return int
     */
    public function getPersonId()
    {
        return $this->personId;
    }

    /**
     * @param int $personId
     *
     * @return SearchPersonResultDto
     */
    public function setPersonId($personId)
    {
        $this->personId = $personId;

        return $this;
    }
}
