<?php

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\CredentialsProvider;

/**
 * Class Organisation_BasicSearchAeByNumber
 */
class Organisation_BasicSearchAeByNumber
{
    private $aeNumber;
    private $username;
    private $result;

    public function setAeNumber($aeNumber)
    {
        $this->aeNumber = $aeNumber;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function result()
    {
        if (TestShared::resultIsSuccess($this->result)) {
            return 'OK';
        }

        return TestShared::errorMessages($this->result);
    }

    public function execute()
    {
         $this->result = TestShared::execCurlForJsonFromUrlBuilder(
                new CredentialsProvider(
                    $this->username,
                    TestShared::PASSWORD
                ),
                (new UrlBuilder())->authorisedExaminerDesignatedManagerByAeRef()
                    ->queryParam('number', $this->aeNumber)
            );
    }
}
