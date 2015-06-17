<?php

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\AuthorisedExaminerUrlBuilder;

class VM9525RetrieveListOfAuthorisedExaminerPrincipals
{
    private $username;
    private $aeId;
    private $result;

    public function __construct($aeId)
    {
        $this->aeId = $aeId;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function execute()
    {
        $urlBuilder = AuthorisedExaminerUrlBuilder::authorisedExaminer($this->aeId)->authorisedExaminerPrincipal();

        $this->result = TestShared::execCurlForJsonFromUrlBuilder(
            (new \MotFitnesse\Util\CredentialsProvider($this->username, TestShared::PASSWORD)),
            $urlBuilder
        );
    }

    public function result()
    {
        if (TestShared::resultIsSuccess($this->result)) {
            return 'OK';
        }

        return TestShared::errorMessages($this->result);
    }
}
