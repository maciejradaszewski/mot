<?php

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\OrganisationUrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm2392OrganisationSlotUsage
{

    private $organisationId;
    private $dateFrom;
    private $dateTo;

    public function __construct($organisationId, $dateFrom, $dateTo)
    {
        $this->organisationId = $organisationId;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function query()
    {
        $data = [];

        $apiResult = $this->requestDataFromApi();
        foreach ($apiResult['data'] as $record) {
            $dataEntry = [];
            foreach ($record as $key => $value) {
                $dataEntry[] = [$key, $value];
            }
            $data[] = $dataEntry;
        }
        return $data;
    }

    private function requestDataFromApi()
    {
        $credentials = new CredentialsProvider('ft-aedm', TestShared::PASSWORD);
        $urlBuilder = OrganisationUrlBuilder::slotUsage($this->organisationId)
            ->queryParams(
                [
                    'dateFrom' => $this->dateFrom,
                    'dateTo' => $this->dateTo
                ]
            );
        return TestShared::executeAndReturnResponseAsArrayFromUrlBuilder($credentials, $urlBuilder);
    }
}
