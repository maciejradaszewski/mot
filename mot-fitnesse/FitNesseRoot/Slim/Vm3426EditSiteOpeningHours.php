<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\UrlBuilder;

class Vm3426EditSiteOpeningHours
{
    private $siteId;
    private $day;
    private $newOpenTime;
    private $newCloseTime;
    private $isClosed;

    private $putResult;
    private $putSuccess = false;
    private $putErrors;
    private $resultOpenTime;
    private $resultCloseTime;

    private $days
        = [
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
            7 => 'sunday'
        ];

    public function reset()
    {
        $this->putSuccess = false;
        $this->resultOpenTime = null;
        $this->resultCloseTime = null;
        $this->putErrors = null;
    }

    public function execute()
    {
        $apiClient = FitMotApiClient::create("areaOffice1user", \MotFitnesse\Util\TestShared::PASSWORD);
        if ($this->putOpeningTimesToApi($apiClient)) {
            $this->getOpeningTimesFromApi($apiClient);
        }
    }

    public function setSiteId($value)
    {
        $this->siteId = $value;
    }

    public function setDay($value)
    {
        $this->day = array_search(strtolower($value), $this->days);
    }

    public function setNewOpenTime($value)
    {
        $this->newOpenTime = $value;
    }

    public function setNewCloseTime($value)
    {
        $this->newCloseTime = $value;
    }

    public function setIsClosed($value)
    {
        $this->isClosed = ($value === 'true');
    }

    public function putSuccess()
    {
        return $this->putSuccess;
    }

    public function putErrorMessage()
    {
        return $this->putErrors ? : '';
    }

    public function openTimeAfterSuccess()
    {
        return $this->resultOpenTime ? : '';
    }

    public function closeTimeAfterSuccess()
    {
        return $this->resultCloseTime ? : '';
    }

    private function buildWeek()
    {
        $data = [];

        for ($i = 1; $i < 8; $i++) {

            if ($this->day === $i) {
                $openTime = $this->newOpenTime;
                $closeTime = $this->newCloseTime;
                $isClosed = $this->isClosed;
            } else {
                $openTime = '09:00:00';
                $closeTime = '
                17:00:00';
                $isClosed = false;
            }
            $data['weeklySchedule'][] = [
                'weekday'   => $i,
                'openTime'  => $openTime,
                'closeTime' => $closeTime,
                'isClosed'  => $isClosed
            ];
        }

        return $data;
    }

    private function putOpeningTimesToApi(FitMotApiClient $apiClient)
    {
        $data = $this->buildWeek();
        $urlBuilder = (new UrlBuilder())->vehicleTestingStation()->routeParam('id', $this->siteId)->siteOpeningHours();

        try {
            $this->putResult = $apiClient->put($urlBuilder, $data);
            $this->putSuccess = true;
        } catch (ApiErrorException $e) {
            foreach ($e->getErrorsArray() as $errorMessage) {
                $this->putErrors[] = $errorMessage['displayMessage'];
            }

            return false;
        }

        return true;
    }

    private function getOpeningTimesFromApi(FitMotApiClient $apiClient)
    {
        $urlBuilder = (new UrlBuilder())->vehicleTestingStation()->routeParam('id', $this->siteId)
            ->siteOpeningHours();

        $data = $apiClient->get($urlBuilder);

        $openTime = $data[$this->day - 1]['openTime'];
        $this->resultOpenTime = $openTime ? $openTime : 'closed';

        $closeTime = $data[$this->day - 1]['closeTime'];
        $this->resultCloseTime = $closeTime ? $closeTime : 'closed';

    }
}
