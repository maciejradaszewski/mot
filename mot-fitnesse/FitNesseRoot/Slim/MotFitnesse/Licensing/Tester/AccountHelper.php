<?php

namespace MotFitnesse\Licensing\Tester;

use MotFitnesse\Util\RetrieveCheckingHelper;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\Tester1CredentialsProvider;

class AccountHelper extends RetrieveCheckingHelper
{

    public function __construct($uuid)
    {
        parent::__construct($uuid);
    }

    protected function retrieve($id)
    {
        return TestShared::executeAndReturnResponseAsArrayFromUrlBuilder(
            $this->credentialsProvider, (new UrlBuilder())->testerAccount()->registrationComplete()->routeParam("id", $id)
        );
    }

} 