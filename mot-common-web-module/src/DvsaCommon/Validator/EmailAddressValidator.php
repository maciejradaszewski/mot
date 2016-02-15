<?php

namespace DvsaCommon\Validator;

use Zend\Validator\EmailAddress;

class EmailAddressValidator extends EmailAddress
{
    const TEST_DOMAIN = 'dvsa.test';
    public function __construct($options = [])
    {
        parent::__construct($options);
        $this->setHostnameValidator(new HostnameValidator());
    }
}