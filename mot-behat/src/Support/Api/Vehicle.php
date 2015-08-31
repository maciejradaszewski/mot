<?php

namespace Dvsa\Mot\Behat\Support\Api;

use Dvsa\Mot\Behat\Datasource\Authentication;
use Dvsa\Mot\Behat\Support\HttpClient;
use Dvsa\Mot\Behat\Support\Request;

class Vehicle extends MotApi
{
    const PATH = 'vehicle';
    const PATH_ID = 'vehicle/{vehicle_id}';
    const PATH_SEARCH = 'vehicle?reg={vehicle_reg}&vinType=noVin&excludeDvla=true';
    const PATH_SEARCH_WITH_VIN = 'vehicle?reg={vehicle_reg}&vinType=fullVin&vin={vin}&excludeDvla=true';

    private $makeMap = [
        'BMW' => '18807',
        'Suzuk' => '18807',
        'Piagg' => '18807',
        'Ford' => '18807',
        'Merce' => '18807',
    ];

    private $modelMap = [
        'Mini' => '013AA',
        'Band' => '013AA',
        'Haya' => '013AA',
        'MP3' => '013AA',
        'Supe' => '013AA',
        'Anto' => '013AA',
    ];

    public function create($token, $vehicleMerge)
    {
        $vehicleMergeData = array_replace([
            'colour' => 'C',
            'countryOfRegistration' => 1,
            'cylinderCapacity' => 1000,
            'fuelType' => 'PE',
            'make' => 'BMW',
            'model' => 'Mini',
            'makeOther' => null,
            'modelOther' => null,
            'registrationNumber' => self::randomRegNumber(),
            'secondaryColour' => 'C',
            'testClass' => 4,
            'transmissionType' => 2,
            'vin' => self::randomVin(),
            'dateOfFirstUse' => '1990-01-01',
            'dateOfRegistration' => '1980/01/01',
            'dateOfManufacture' => '1980/01/01',
            'newAtFirstReg' => 0,
            'oneTimePassword' => Authentication::ONE_TIME_PASSWORD,
            'vtsId' => 1,
        ], $vehicleMerge);

        $body = $vehicleMergeData;
        $body['make'] = $this->mapMakeToCode($body['make']);
        $body['model'] = $this->mapModelToCode($body['model']);

        return $this->sendRequest($token, MotApi::METHOD_POST, self::PATH, $body);
    }

    public function getVehicleDetails($token, $vehicle_id)
    {
        return $this->client->request(new Request(
            'GET',
            str_replace('{vehicle_id}', $vehicle_id, self::PATH_ID),
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token]
        ));
    }

    public function vehicleSearch($token, $reg, $vin = null)
    {
        if(empty($vin)) {
            $url = str_replace('{vehicle_reg}', $reg, self::PATH_SEARCH);
        } else {
            $url = str_replace(['{vehicle_reg}', '{vin}'], [$reg, $vin], self::PATH_SEARCH_WITH_VIN);
        }

        return $this->client->request(new Request(
            'GET',
            $url,
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token]
        ));
    }

    public function dvlaVehicleUpdated($token, $vehicleId)
    {
        return $this->client->request(new Request(
            'POST',
            'dvla-vehicle-updated',
            ['Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$token],
            json_encode(['vehicleId' => $vehicleId])
        ));
    }

    public function randomRegNumber($length = 7)
    {
        $str = '';
        $characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }

        return strtoupper($str);
    }

    public function randomVin($length = 17)
    {
        $str = '';
        $characters = array_merge(range('0', '9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }

        return $str;
    }

    private function mapMakeToCode($make)
    {
        if (!isset($this->makeMap[$make])) {
            throw new \InvalidArgumentException(sprintf('Could not map "%s" to a make code', $make));
        }

        return $this->makeMap[$make];
    }

    private function mapModelToCode($model)
    {
        if (!isset($this->modelMap[$model])) {
            throw new \InvalidArgumentException(sprintf('Could not map "%s" to a model code', $model));
        }

        return $this->modelMap[$model];
    }
}
