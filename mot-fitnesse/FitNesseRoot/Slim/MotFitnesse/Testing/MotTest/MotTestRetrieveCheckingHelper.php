<?php

namespace MotFitnesse\Testing\MotTest;

use MotFitnesse\Util\RetrieveCheckingHelper;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class MotTestRetrieveCheckingHelper extends RetrieveCheckingHelper
{
    public function __construct($motTestNumber)
    {
        parent::__construct($motTestNumber);
    }

    protected function retrieve($motTestNumber)
    {
        return TestShared::executeAndReturnResponseAsArrayFromUrlBuilder(
            $this->credentialsProvider,
            (new UrlBuilder())->motTest()->routeParam('motTestNumber', $motTestNumber)
        );
    }
}
