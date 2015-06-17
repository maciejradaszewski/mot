<?php

namespace DvsaCommon\Dto\Person;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\Contact\ContactDto;

/**
 * Class PersonDto
 *
 * @package DvsaCommon\Dto\Person
 */
class PersonDto extends AbstractDataTransferObject
{
    private $firstName;
    private $middleName;
    private $familyName;
    private $contactDetails;
    private $id;
    private $gender;
    private $title;
    private $dateOfBirth;
    private $username;
    private $displayName;

    /**
     * @param ContactDto[] $contactDetails
     *
     * @return PersonDto
     */
    public function setContactDetails($contactDetails)
    {
        $this->contactDetails = $contactDetails;

        return $this;
    }

    /**
     * @return ContactDto[]
     */
    public function getContactDetails()
    {
        return $this->contactDetails;
    }

    public function setDateOfBirth($dataOfBirth)
    {
        $this->dateOfBirth = $dataOfBirth;

        return $this;
    }

    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * @param ContactDto[] $contactDetails
     *
     * @return PersonDto
     */
    public function setContacts($contactDetails)
    {
        $this->contactDetails = $contactDetails;

        return $this;
    }

    /**
     * @return ContactDto[]
     */
    public function getContacts()
    {
        return $this->contactDetails;
    }

    /**
     * @param $title
     *
     * @return PersonDto
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $gender
     *
     * @return PersonDto
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $familyName
     *
     * @return PersonDto
     */
    public function setFamilyName($familyName)
    {
        $this->familyName = $familyName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }

    /**
     * @param string $firstName
     *
     * @return PersonDto
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
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
     * @param string $id
     *
     * @return PersonDto
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $middleName
     *
     * @return PersonDto
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

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
     * @return string
     */
    public function getFullName()
    {
        return join(' ', array_filter([$this->getFirstName(), $this->getMiddleName(), $this->getFamilyName()]));
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     *
     * @return PersonDto
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

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
     * @return PersonDto
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }
}
