<?php

namespace DvsaCommon\Dto\AuthorisedExaminerPrincipal;

use DvsaCommon\Date\DateTimeDisplayFormat;
use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\Contact\ContactDto;

class AuthorisedExaminerPrincipalDto extends AbstractDataTransferObject
{
    private $firstName;
    private $middleName;
    private $familyName;
    private $contactDetails;
    private $id;
    private $dateOfBirth;
    private $displayName;

    /**
     * @param ContactDto $contactDetails
     *
     * @return AuthorisedExaminerPrincipalDto
     */
    public function setContactDetails($contactDetails)
    {
        $this->contactDetails = $contactDetails;

        return $this;
    }

    /**
     * @return ContactDto
     */
    public function getContactDetails()
    {
        return $this->contactDetails;
    }

    /**
     * @param string $dataOfBirth
     * @return AuthorisedExaminerPrincipalDto
     */
    public function setDateOfBirth($dataOfBirth)
    {
        $this->dateOfBirth = $dataOfBirth;

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
     * @return string format: j F Y
     */
    public function displayDateOfBirth()
    {
        return DateTimeDisplayFormat::textDate($this->dateOfBirth);
    }

    /**
     * @param string $familyName
     *
     * @return AuthorisedExaminerPrincipalDto
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
     * @return AuthorisedExaminerPrincipalDto
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
     * @return AuthorisedExaminerPrincipalDto
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
     * @return AuthorisedExaminerPrincipalDto
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
     * @param string $displayName
     * @return AuthorisedExaminerPrincipalDto
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }
}
