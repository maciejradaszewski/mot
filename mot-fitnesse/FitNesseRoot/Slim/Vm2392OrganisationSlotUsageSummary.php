<?php
/**
 * Vm2392OrganisationSlotUsageSummary
 * 
 * @author Jakub Igla <jakub.igla@gmail.com>
 */

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\OrganisationUrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm2392OrganisationSlotUsageSummary
{

    protected $dateFrom;

    protected $dateTo;

    protected $organisationId;

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
    public function setOrganisationId($organisationId)
    {
        $this->organisationId = $organisationId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrganisationId()
    {
        return $this->organisationId;
    }

    public function usage()
    {
        $credentials = new CredentialsProvider('ft-aedm', TestShared::PASSWORD);

        $queryString = [
            'period' => [
                [
                    'from' => $this->getDateFrom(),
                    'to'    => $this->getDateTo(),
                ],
            ],
        ];

        $url = OrganisationUrlBuilder::slotUsagePeriodData($this->getOrganisationId())
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
