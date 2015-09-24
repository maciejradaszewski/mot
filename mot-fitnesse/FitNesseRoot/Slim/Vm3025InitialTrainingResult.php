<?php

use DvsaCommon\Enum\VehicleClassCode;
use MotFitnesse\Util\FitHelper;
use MotFitnesse\Util\PersonUrlBuilder;

class Vm3025InitialTrainingResult
{
    private $personId;
    private $newStatusCode;
    private $trainingPassed;
    private $group;

    public function newStatusCode()
    {
        return $this->newStatusCode;
    }

    public function getStatusCodeForGroup($group, $authorisations)
    {
        if ($group == 1) {
            return $authorisations['class' . VehicleClassCode::CLASS_1];
        } else {
            if ($group == 2) {
                return $authorisations['class' . VehicleClassCode::CLASS_3];
            } else {
                return null;
            }
        }
    }


    public function setPersonId($value)
    {
        $this->personId = $value;
    }

    public function setGroup($value)
    {
        $this->group = $value;
    }

    public function statusCode()
    {
        $client = FitMotApiClient::create("areaoffice1user", \MotFitnesse\Util\TestShared::PASSWORD);

        $apiUrl = PersonUrlBuilder::motTesting($this->personId);

        $initialAuthorisations = $client->get($apiUrl);
        $initialStatusCode = $this->getStatusCodeForGroup($this->group, $initialAuthorisations);
        $newAuthorisations = $client->put($apiUrl, ['group' => $this->group, 'result' => $this->trainingPassed ]);
        $this->newStatusCode = $this->getStatusCodeForGroup($this->group, $newAuthorisations);

        return $initialStatusCode;
    }

    public function setTrainingPassed($value)
    {
        $this->trainingPassed = FitHelper::decode($value, ['Y' => 1, 'N' => 0]);
    }
}
