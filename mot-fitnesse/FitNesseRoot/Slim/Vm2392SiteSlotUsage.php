<?php

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class Vm2392SiteSlotUsage
{

    private $siteId;
    private $dateFrom;
    private $dateTo;
    private $aedmUsername;

    public function __construct($siteId, $dateFrom, $dateTo, $aedmUsername)
    {
        $this->siteId = $siteId;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->aedmUsername = $aedmUsername;
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
        $credentials = new CredentialsProvider($this->aedmUsername, TestShared::PASSWORD);
        $urlBuilder = (new UrlBuilder())
            ->siteUsage($this->siteId)
            ->queryParams(
                [
                    'dateFrom' => $this->dateFrom,
                    'dateTo' => $this->dateTo
                ]
            );

        return TestShared::executeAndReturnResponseAsArrayFromUrlBuilder($credentials, $urlBuilder);
    }
}
