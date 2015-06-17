<?php

use MotFitnesse\Util\AuthorisedExaminerUrlBuilder;
use MotFitnesse\Util\Tester1CredentialsProvider;
use MotFitnesse\Util\TestShared;

/**
 * I think this is about slot balance, but I might be wrong.
 */
class SlotBalance
{
    private $organisationId;
    private $result;

    public function execute()
    {
        $urlBuilder = AuthorisedExaminerUrlBuilder::authorisedExaminer($this->organisationId)->slot();

        $this->result = TestShared::execCurlForJsonFromUrlBuilder(new Tester1CredentialsProvider(), $urlBuilder);
    }

    public function slotBalance()
    {
        return $this->result['data'];
    }

    public function setOrganisationId($organisationId)
    {
        $this->organisationId = $organisationId;
    }
}
