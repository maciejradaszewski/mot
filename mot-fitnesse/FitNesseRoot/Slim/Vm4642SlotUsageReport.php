<?php

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\OrganisationUrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm4642SlotUsageReport
{
    private $userName;
    private $organisationId;
    private $result;
    private $exceptionMessage;

    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    public function setOrganisationId($organisationId)
    {
        $this->organisationId = $organisationId;
    }

    public function execute()
    {
        $this->exceptionMessage = '';
        $this->result = $this->requestDataFromApi();
    }

    public function result()
    {
        if (isset($this->result['data'])) {
            return 'TRUE';
        } else {
            return 'FALSE';
        }
    }

    public function exceptionMessage()
    {
        return $this->exceptionMessage;
    }

    private function requestDataFromApi()
    {
        $credentials = new CredentialsProvider($this->userName, TestShared::PASSWORD);
        $urlBuilder = OrganisationUrlBuilder::slotUsage($this->organisationId);

        try {
            return TestShared::executeAndReturnResponseAsArrayFromUrlBuilder($credentials, $urlBuilder);
        } catch (\Exception $e) {
            $this->exceptionMessage = $e->getMessage();
            return false;
        }

    }
}