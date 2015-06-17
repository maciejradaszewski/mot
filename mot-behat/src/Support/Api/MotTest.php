<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Datasource\Authentication;
use Dvsa\Mot\Behat\Support\HttpClient;

class MotTest extends AbstractMotTest
{
    const PATH = 'mot-test';
    const PATH_GET_CERT = 'mot-test-certificate?number=651157444199';
    const PATH_SEARCH = 'mot-test-search';

    /**
     * @var Person
     */
    private $person;
    
    public function __construct(HttpClient $client, Person $person)
    {
        parent::__construct($client);

        $this->person = $person;
    }

    public function getPath()
    {
        return self::PATH;
    }

    /**
     * @param string $token
     * @param string $vehicleId
     * @param string $testClass
     *
     * @return \Dvsa\Mot\Behat\Support\Response
     * @throws \Exception
     */
    public function startMOTTest($token, $vehicleId = '3', $testClass = '4')
    {
        $params = [
            'vehicleId' => $vehicleId,
            'vehicleTestingStationId' => '1',
            'primaryColour' => 'L',
            'secondaryColour' => 'L',
            'fuelTypeId' => 'PE',
            'vehicleClassCode' => $testClass,
            'hasRegistration' => '1',
            'oneTimePassword' => Authentication::ONE_TIME_PASSWORD,
        ];

        return parent::createMotWithParams($token, $params);
    }

    public function startMOTTestForTester($token, $userId)
    {
        if (!$this->isMOTTestInProgressForTester($token, $userId)) {
            return $this->startMOTTest($token);
        } else {
            $currentMotTestNumber = $this->getInProgressTestId($token, $userId);
            $this->abort($token, $currentMotTestNumber);

            return $this->startMOTTest($token);
        }
    }

    public function startNewMotTestWithVehicleId($token, $userId, $vehicleId, $vehicleClass = '4')
    {
        if (!$this->isMOTTestInProgressForTester($token, $userId)) {
            return $this->startMOTTest($token, $vehicleId, $vehicleClass);
        } else {
            //Stop Current Test and Start a New one with the new Vehicle Id

            $currentMotTestNumber = $this->getInProgressTestId($token, $userId);
            $this->abort($token, $currentMotTestNumber);

            return $this->startMOTTest($token, $vehicleId, $vehicleClass);
        }
    }

    public function isMOTTestInProgressForTester($token, $user_id)
    {
        $response = $this->person->getPersonDashboard($token, $user_id);

        return $response->getBody()['data']['inProgressTestNumber'] == null ? false : true;
    }

    public function getInProgressTestId($token, $user_id)
    {
        $response = $this->person->getPersonDashboard($token, $user_id);

        return $response->getBody()['data']['inProgressTestNumber'];
    }

    public function getMotData($token, $motTestNumber)
    {
        return $this->sendRequest(
            $token,
            MotApi::METHOD_GET,
            self::PATH.'/'.$motTestNumber
        );
    }

    public function getMOTCertificateDetails($token)
    {
        return $this->sendRequest(
            $token,
            MotApi::METHOD_GET,
            self::PATH_GET_CERT
        );
    }

    public function searchMOTTest($token, $params)
    {
        $defaults = [
            'status' => [],
            'testType' => [],
            'format' => "DATA_TABLES",
            'pageNr' => 0,
            'rowsCount' => 25000,
            'sortColumnId' => 5, //sort by started date, id assigned in MotTestSearchParam class
            'sortDirection' => "DESC",
            '_class' => 'DvsaCommon\Dto\Search\MotTestSearchParamsDto',
        ];

        $params = array_replace($defaults, $params);

        return $this->sendRequest(
            $token,
            MotApi::METHOD_POST,
            self::PATH_SEARCH,
            $params
        );
    }
}
