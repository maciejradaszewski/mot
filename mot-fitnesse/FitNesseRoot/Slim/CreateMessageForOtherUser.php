<?php

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class CreateMessageForOtherUser
{
    private $someOtherPersonId;

    private $messageCreator;
    private $messageTypeCode;

    private $result;

    public function beginTable()
    {
        $testSupportHelper = new TestSupportHelper();
        $schememgt = $testSupportHelper->createSchemeManager();
        $this->someOtherPersonId = $testSupportHelper->createTester($schememgt['username'], [1])['personId'];
    }

    public function errorMessage()
    {
        return TestShared::errorMessages($this->result);
    }

    public function result()
    {
        $resultValue = '';

        if (TestShared::resultIsSuccess($this->result)) {
            $resultValue = $this->result;
        }

        return $resultValue;
    }

    public function execute()
    {
        $this->result = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            CredentialsProvider::fromArray(['username' => $this->messageCreator, 'password' => TestShared::PASSWORD]),
            (new UrlBuilder())->message(),
            ['personId' => $this->someOtherPersonId, 'messageTypeCode' => $this->messageTypeCode]
        );
    }

    public function reset()
    {
        $this->messageCreator = null;
        $this->messageTypeCode = null;

        $this->result = null;
    }

    public function setMessageCreator($messageCreator)
    {
        $this->messageCreator = $messageCreator;
    }

    public function setMessageTypeCode($messageTypeCode)
    {
        $this->messageTypeCode = $messageTypeCode;
    }

    public function setInfo()
    {
    }
}
