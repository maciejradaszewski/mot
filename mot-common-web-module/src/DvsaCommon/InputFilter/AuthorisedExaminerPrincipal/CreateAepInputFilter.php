<?php

namespace DvsaCommon\InputFilter\AuthorisedExaminerPrincipal;

use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\AddressLine1Input;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\AddressLine2Input;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\AddressLine3Input;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\CountryInput;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\DateOfBirthInput;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\FirstNameInput;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\MiddleNameInput;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\FamilyNameInput;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\PostcodeInput;
use DvsaCommon\Input\AuthorisedExaminerPrincipal\TownInput;
use DvsaCommon\Validator\PasswordValidator;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator\Identical;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

class CreateAepInputFilter extends InputFilter
{
    public function init()
    {
        $this->add(new FirstNameInput());
        $this->add(new MiddleNameInput());
        $this->add(new FamilyNameInput());
        $this->add(new AddressLine1Input());
        $this->add(new AddressLine2Input());
        $this->add(new AddressLine3Input());
        $this->add(new PostcodeInput());
        $this->add(new DateOfBirthInput());
        $this->add(new TownInput());
        $this->add(new CountryInput());

        return $this;
    }
}
