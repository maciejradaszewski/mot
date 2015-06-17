<?php
use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class Vm2392SiteSlotUsageSummary
{

    protected $dateFrom;

    protected $dateTo;

    protected $siteId;

    /**
     * @param mixed $dateFrom
     * @return $this;
     */
    public function setDateFrom($dateFrom)
    {
        $this->dateFrom = $dateFrom;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * @param mixed $dateTo
     * @return $this;
     */
    public function setDateTo($dateTo)
    {
        $this->dateTo = $dateTo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }

    /**
     * @param mixed $organisationId
     * @return $this
     */
    public function setSiteId($organisationId)
    {
        $this->siteId = $organisationId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    public function usage()
    {
        $credentials = new CredentialsProvider('inactivetester', TestShared::PASSWORD);

        $queryString = [
            'period' => [
                [
                    'from' => $this->getDateFrom(),
                    'to'    => $this->getDateTo(),
                ],
            ],
        ];

        $url = (new UrlBuilder)->siteUsage($this->getSiteId())->periodData()
            ->queryParams($queryString)->toString();
        $curlHandle = curl_init($url);

        TestShared::SetupCurlOptions($curlHandle);

        TestShared::setAuthorizationInHeaderForUser(
            $credentials->username,
            $credentials->password,
            $curlHandle
        );

        $return = TestShared::execCurlForJson($curlHandle);

        if (TestShared::resultIsSuccess($return)) {
            return $return['data'][0];
        }

        return '-';
    }

}
