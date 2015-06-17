<?php

namespace UserAdmin\Presenter;

use DvsaCommon\Date\DateTimeDisplayFormat;
use DvsaCommon\Dto\Person\SearchPersonResultDto;
use DvsaCommon\Utility\TypeCheck;

/**
 * Decorator for SearchPersonResultDto.
 */
class PersonPresenter
{
    /* @var SearchPersonResultDto $person */
    private $person;

    public function __construct(SearchPersonResultDto $person)
    {
        $this->person = $person;
    }

    /**
     * @return string
     */
    public function displayFullName()
    {
        return implode(' ', [$this->person->getFirstName(), $this->person->getMiddleName(), $this->person->getLastName()]);
    }

    /**
     * @return null|string
     */
    public function displayUserDateOfBirth()
    {
        return DateTimeDisplayFormat::textDate($this->person->getDateOfBirth());
    }

    /**
     * @return string
     */
    public function displayUserAddress()
    {
        return implode(', ', array_filter([$this->person->getAddress()->getAddressLine1(),
            $this->person->getAddress()->getTown(), ]));
    }

    /**
     * @return string
     */
    public function displayUsername()
    {
        return $this->person->getUsername();
    }

    /**
     * @return string
     */
    public function displayPostcode()
    {
        return $this->person->getAddress()->getPostcode();
    }

    /**
     * @param SearchPersonResultDto[] $users
     *
     * @return PersonPresenter[]
     */
    public static function decorateList($users)
    {
        TypeCheck::assertArray($users);

        $decorated = [];

        foreach ($users as $user) {
            $decorated[] = new self($user);
        }

        return $decorated;
    }

    public function getPersonId()
    {
        return $this->person->getPersonId();
    }
}
