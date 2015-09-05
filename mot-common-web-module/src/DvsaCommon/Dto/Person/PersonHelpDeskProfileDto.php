<?php

namespace DvsaCommon\Dto\Person;

use DvsaCommon\Dto\Account\AuthenticationMethodDto;
use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\TypeCheck;

/**
 * Dto for person's profile via help desk.
 */
class PersonHelpDeskProfileDto
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $userName;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $middleName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string yyyy-mm-dd
     */
    private $dateOfBirth;

    /**
     * @var \DvsaCommon\Dto\Contact\AddressDto
     */
    private $address;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $telephone;

    /**
     * An array of roles and permissions assigned to the user at system, site and organisation level. Each
     * organisations/sites details is contained in the siteOrganisationMap array, indexed by ID, which correlates with
     * the indexes in each of the sites and organisations arrays. Array structure:.
     *
     * [
     *     'sites' => [ 1 => [ 'roles' => [ 0 => 'EXAMPLE-1', 1 => 'EXAMPLE-2' ] ] ]
     *     'organisations' => [ 2 => [ 'roles' => [ 0 => 'EXAMPLE-3', 1 => 'EXAMPLE-4' ] ] ]
     *     'siteOrganisationMap' => [
     *         1 => [ 'siteData' => [ 'site_name' => 'Foo Bar' ... ] ]
     *         2 => [ 'siteData' => [ 'site_name' => 'Bar Baz' ... ] ]
     *     ]
     * ]
     *
     * @var array|null
     */
    private $roles;

    /**
     * @var string
     */
    private $drivingLicence;

    /**
     * @var string
     */
    private $authenticationMethod;

    /**
     * @param $data
     *
     * @return PersonHelpDeskProfileDto
     */
    public static function fromArray($data)
    {
        TypeCheck::assertArray($data);

        $dto = new static();
        $dto
            ->setTitle(ArrayUtils::get($data, 'title'))
            ->setUserName(ArrayUtils::get($data, 'userName'))
            ->setFirstName(ArrayUtils::get($data, 'firstName'))
            ->setMiddleName(ArrayUtils::get($data, 'middleName'))
            ->setLastName(ArrayUtils::get($data, 'lastName'))
            ->setDateOfBirth(ArrayUtils::get($data, 'dateOfBirth'))
            ->setEmail(ArrayUtils::get($data, 'email'))
            ->setTelephone(ArrayUtils::get($data, 'telephone'))
            ->setAddress(AddressDto::fromArray($data))
            ->setRoles(ArrayUtils::get($data, 'roles'))
            ->setDrivingLicenceNumber(ArrayUtils::get($data, 'drivingLicence'))
            ->setAuthenticationMethod(AuthenticationMethodDto::fromArray(ArrayUtils::get($data, 'authenticationMethod')));

        return $dto;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $address = $this->getAddress();
        $authenticationMethod = $this->getAuthenticationMethod() ? $this->getAuthenticationMethod()->toArray() : null;

        return [
            'title'                 => $this->getTitle(),
            'userName'              => $this->getUserName(),
            'firstName'             => $this->getFirstName(),
            'middleName'            => $this->getMiddleName(),
            'lastName'              => $this->getLastName(),
            'dateOfBirth'           => $this->getDateOfBirth(),
            'postcode'              => $address ? $address->getPostcode() : null,
            'addressLine1'          => $address ? $address->getAddressLine1() : null,
            'addressLine2'          => $address ? $address->getAddressLine2() : null,
            'addressLine3'          => $address ? $address->getAddressLine3() : null,
            'addressLine4'          => $address ? $address->getAddressLine4() : null,
            'town'                  => $address ? $address->getTown() : null,
            'email'                 => $this->getEmail(),
            'telephone'             => $this->getTelephone(),
            'roles'                 => $this->getRoles(),
            'drivingLicence'        => $this->getDrivingLicenceNumber(),
            'authenticationMethod'  => $authenticationMethod,
        ];
    }

    /**
     * @param AddressDto|null $address
     *
     * @return PersonHelpDeskProfileDto
     */
    public function setAddress(AddressDto $address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return AddressDto
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $dateOfBirth
     *
     * @return PersonHelpDeskProfileDto
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

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
     * @param string $firstName
     *
     * @return PersonHelpDeskProfileDto
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
     * @param string $middleName
     *
     * @return PersonHelpDeskProfileDto
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
     * @param string $lastName
     *
     * @return PersonHelpDeskProfileDto
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

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
     * @param string $title
     *
     * @return PersonHelpDeskProfileDto
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $userName
     *
     * @return PersonHelpDeskProfileDto
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param string $email
     *
     * @return PersonHelpDeskProfileDto
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string|null $telephone
     *
     * @return PersonHelpDeskProfileDto
     */
    public function setTelephone($telephone = null)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param array $roles
     *
     * @return $this
     */
    public function setRoles(array $roles = null)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param $number
     *
     * @return $this
     */
    public function setDrivingLicenceNumber($number)
    {
        $this->drivingLicence = $number;

        return $this;
    }

    /**
     * @return string
     */
    public function getDrivingLicenceNumber()
    {
        return $this->drivingLicence;
    }

    /**
     * @return AuthenticationMethodDto $authenticationMethod
     */
    public function getAuthenticationMethod()
    {
        return $this->authenticationMethod;
    }

    /**
     * @param AuthenticationMethodDto $authenticationMethod
     * @return $this
     */
    public function setAuthenticationMethod(AuthenticationMethodDto $authenticationMethod)
    {
        $this->authenticationMethod = $authenticationMethod;

        return $this;
    }


}
