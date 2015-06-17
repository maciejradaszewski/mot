<?php

namespace MotFitnesse\Licensing\AuthorisedExaminerDesignatedManager;

use MotFitnesse\Util\RetrieveCheckingHelper;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\Tester1CredentialsProvider;

class ApplicationCommentHelper extends RetrieveCheckingHelper
{


    function __construct($uuid)
    {
        parent::__construct($uuid);
    }

    protected function retrieve($id)
    {
        return TestShared::executeAndReturnResponseAsArrayFromUrlBuilder(
            $this->credentialsProvider, (new UrlBuilder())->assessmentApplicationComment()->routeParam('uuid', $id)
        );
    }

} 