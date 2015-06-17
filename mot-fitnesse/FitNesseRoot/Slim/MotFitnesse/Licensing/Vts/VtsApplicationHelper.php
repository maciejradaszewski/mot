<?php

namespace MotFitnesse\Licensing\Vts;

use MotFitnesse\Util\RetrieveCheckingHelper;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\Tester1CredentialsProvider;

class VtsApplicationHelper extends RetrieveCheckingHelper {


    public function __construct($uuid)
    {
        parent::__construct($uuid);
    }

    protected function retrieve($uuid)
    {
        return TestShared::executeAndReturnResponseAsArrayFromUrlBuilder(
            $this->credentialsProvider, (new UrlBuilder())->vtsApplicant()->routeParam("uuid", $uuid)
        );
    }

} 