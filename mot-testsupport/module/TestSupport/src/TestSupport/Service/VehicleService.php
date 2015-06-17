<?php

namespace TestSupport\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Connection;
use DvsaCommon\Utility\ArrayUtils;

class VehicleService
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $data
     * @return int
     */
    public function save(array $data)
    {
        $vehicleData = $this->prepare($data);
        $this->entityManager->getConnection()->insert('vehicle', $vehicleData);

        return (int)$this->entityManager->getConnection()->lastInsertId();
    }

    private function prepare(array $data)
    {
        $vehicleData = [];
        $makeCode = ArrayUtils::tryGet($data, 'make');
        $makeId = $this->fetchId("make", $makeCode);
        if ($makeId) {
            $vehicleData["make_id"] = $makeId;
        }

        $model = ArrayUtils::tryGet($data, 'model');
        $sql = $this->getSql("model") . " AND `make_code` = :make_code";
        $stmt = $this->entityManager->getConnection()->prepare($sql);
        $stmt->bindValue("code", $model);
        $stmt->bindValue("make_code", $makeCode);
        $stmt->execute();
        $modelId = $stmt->fetchColumn();
        if ($modelId) {
            $vehicleData["model_id"] = $modelId;
        }

        $testClass = ArrayUtils::tryGet($data, 'testClass');
        $vehicleData["vehicle_class_id"] = $this->fetchId("vehicle_class", $testClass);

        $bodyType = ArrayUtils::tryGet($data, 'bodyType');
        $vehicleData["body_type_id"] = $this->fetchId("body_type", $bodyType);

        $primaryColour = ArrayUtils::tryGet($data, 'colour');
        $vehicleData["primary_colour_id"] = $this->fetchId("colour_lookup", $primaryColour);

        $secondaryColour = ArrayUtils::tryGet($data, 'secondaryColour');
        $vehicleData["secondary_colour_id"] = $this->fetchId("colour_lookup", $secondaryColour);

        $fuelType = ArrayUtils::tryGet($data, 'fuelType');
        $vehicleData["fuel_type_id"] = $this->fetchId("fuel_type", $fuelType);

        $countryOfRegistration = ArrayUtils::tryGet($data, 'countryOfRegistration');
        $vehicleData["country_of_registration_id"] = $this->fetchId("country_of_registration_lookup",
            $countryOfRegistration);

        $transmissionType = ArrayUtils::tryGet($data, 'transmissionType');
        $vehicleData["transmission_type_id"] = $this->fetchId("transmission_type", $transmissionType);

        $vehicleData["registration"] = ArrayUtils::tryGet($data, 'registrationNumber');

        $vehicleData["vin"] = ArrayUtils::tryGet($data, 'vin');

        if (is_null($vehicleData['vin'])) {
            $vehicleData['empty_vin_reason_id'] = 1;
        }

        if (is_null($vehicleData['registration'])) {
            $vehicleData['empty_vrm_reason_id'] = 1;
        }
        $makeOther = ArrayUtils::tryGet($data, 'makeOther');

        if (!$makeId) {
            $vehicleData["make_name"] = $makeOther;
        }

        $modelOther = ArrayUtils::tryGet($data, 'modelOther');
        if (!$modelId) {
            $vehicleData["model_name"] = $modelOther;
        }

        $vehicleData["cylinder_capacity"] = ArrayUtils::tryGet($data, 'cylinderCapacity');

        $vehicleData["first_used_date"] = ArrayUtils::tryGet($data, 'dateOfFirstUse');

        $vehicleData["created_by"] = ArrayUtils::tryGet($data, 'createdBy', 2);

        return $vehicleData;
    }

    private function getSql($table)
    {
        return sprintf("SELECT `id` FROM `%s` where `code` = :code", $table);
    }

    private function fetchId($table, $code)
    {
        $connection = $this->entityManager->getConnection();
        $stmt = $connection->prepare($this->getSql($table));
        $stmt->bindValue("code", $code);
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }
}
