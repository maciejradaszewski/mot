<?php

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\OrganisationUrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm2392OrganisationSearchSlotUsage
{
    private $organisationId;
    private $searchText;
    private $result;


    public function setOrganisationId($organisationId)
    {
        $this->organisationId = $organisationId;
    }

    public function setSearchText($searchText)
    {
        $this->searchText = $searchText;
    }

    public function execute()
    {
        $this->result = $this->requestDataFromApi();
    }

    public function foundCount()
    {
        return count($this->result['data']);
    }

    private function requestDataFromApi()
    {
        $credentials = new CredentialsProvider('ft-aedm', TestShared::PASSWORD);
        $urlBuilder = OrganisationUrlBuilder::slotUsage($this->organisationId)
            ->queryParams(['searchText' => $this->searchText]);
        return TestShared::executeAndReturnResponseAsArrayFromUrlBuilder($credentials, $urlBuilder);
    }
}