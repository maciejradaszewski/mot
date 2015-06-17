<?php

/**
 * Backing class for generating opening hours for VTS
 *
!| GenerateVtsOpeningHours   |
|siteId  |aedmUsername|isOpen|
|$siteId1|$aedm1      |false |

 */
class GenerateVtsOpeningHours
{

    private $isOpen;
    private $aedmUsername;
    private $siteId;

    public function execute()
    {
        $client = FitMotApiClient::create($this->aedmUsername, \MotFitnesse\Util\TestShared::PASSWORD);

        $openingHoursUrl = (new \MotFitnesse\Util\UrlBuilder())->vehicleTestingStation()
            ->routeParam('id', $this->siteId)
            ->siteOpeningHours();
        $client->put($openingHoursUrl, $this->schedule());
    }


    private function schedule()
    {
        $data = [];
        $dateTime_minus_mins = date("Y-m-d H:00:00");
        $dateTime = new \DateTime($dateTime_minus_mins);
        $oneHoursInterval = new \DateInterval("PT2H");
        $openingTime = clone $dateTime;
        $closingTime = clone $dateTime;
        if ($this->isOpen) {
            $openingTime->sub($oneHoursInterval);
            $closingTime->add($oneHoursInterval);
        } else {
            $openingTime->add($oneHoursInterval);
            $closingTime->add($oneHoursInterval)->add($oneHoursInterval);
        }

        $startTime = $openingTime->format("H:i");
        $closeTime = $closingTime->format("H:i");

        // closeTime has crossed over midnight in this special case we keep VTS open all times
        if ($startTime > $closeTime) {
            if ($this->isOpen) {
                $openingTime->setTime(0, 0, 0);
                $closingTime->setTime(0, 0, 0);
            } else {
                /* if closeTime crossed over midnight and we expect vts to be closed we put the
                    opening times further ahead to ascertain closed state */
                $openingTime->setTime(3, 0, 0);
                $closingTime->setTime(4, 0, 0);
            }
        }

        for ($i = 1; $i <= 7; $i++) {
            $data['weeklySchedule'][] = [
                'weekday' => $i,
                'openTime' => $openingTime->format("H:i:s"),
                'closeTime' => $closingTime->format("H:i:s"),
                'isClosed' => false
            ];
        }
        return $data;
    }

    public function setIsOpen($isOpen)
    {
        $this->isOpen = $isOpen === 'true';
    }

    public function setAedmUsername($aedmUsername)
    {
        $this->aedmUsername = $aedmUsername;
    }

    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }

}