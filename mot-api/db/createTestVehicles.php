<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use DvsaEntities\Entity\Vehicle;
use DvsaCommon\Date\DateUtils;

chdir(dirname(__DIR__));
mb_internal_encoding("UTF-8");
mb_http_output("UTF-8");

if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

require 'init_autoloader.php';
$app = Zend\Mvc\Application::init(require 'config/application.config.php');
$entityManager = $app->getServiceManager()->get(\Doctrine\ORM\EntityManager::class);
$serviceManager = $app->getServiceManager();
$vehicleService = $serviceManager->get(\VehicleApi\Service\VehicleSearchService::class);
$makes = $entityManager->getRepository(\DvsaEntities\Entity\Make::class)->findBy(
    ['name' =>
        [
            'ASTON MARTIN',
            'AUSTIN HEALEY',
            'AUDI',
            'BMW',
            'BRISTOL',
            'BUICK',
            'CITROEN',
            'CORVETTE',
            'DAEWOO',
            'DAIMLER',
            'DATSUN',
            'DODGE',
            'FIAT',
            'FORD',
            'HONDA',
            'JAGUAR',
            'LANCIA',
            'LADA',
            'LAND ROVER', //B2
            'LEXUS',
            'MAZDA',
            'PORSCHE',
            'RENAULT',
            'TOYOTA',
            'VAUXHALL',
            'VOLVO',
            'VOLKSWAGON',
            'HERALD MOTOR COMPANY', // longest
            'MG', // there are also blank ones so not the shortest
        ]
    ]
);

$modelCodes = [];
foreach ($makes as $make) {
    $code = $make->getId();
    if (strlen($code) > 3) {
        // don't pick up our fake data
        continue;
    }
    $modelCodes[$code] = [];
}
$makes = null;

foreach (array_keys($modelCodes) as $makeCode) {
    $models = $entityManager->getRepository(\DvsaEntities\Entity\Model::class)->findBy(['make' => $makeCode]);
    foreach ($models as $model) {
        $modelCodes[$makeCode][] = $model->getId();
    }
}

$models = null;

// Root
$creator = $entityManager->getRepository(\DvsaEntities\Entity\Person::class)->getReference(1);

// Faked
$modelType = $entityManager->getRepository(\DvsaEntities\Entity\ModelDetail::class)->getReference(1);

// Black
$colour = $entityManager->getRepository(\DvsaEntities\Entity\Colour::class)->getReference('B');

// Petrol
$fuelType = $entityManager->getRepository(\DvsaEntities\Entity\FuelType::class)->findByCode('PE');

// GB
$countryOfRegistration = $entityManager->getRepository(\DvsaEntities\Entity\CountryOfRegistration::class)
    ->getReference(1);

// Manual
$transmissionType = $entityManager->getRepository(\DvsaEntities\Entity\TransmissionType::class)->getReference(2);

// Class 3
$testClass = $entityManager->getRepository(\DvsaEntities\Entity\VehicleClass::class)->getReference(3);

$batchSize = 1000;

$makeCodes = array_keys($modelCodes);
$numMakeCodes = count($makeCodes);


for ($v=0; $v<100000; $v++) {

    $makeCode = $makeCodes[mt_rand(0, $numMakeCodes - 1)];
    $makeModelCodes = $modelCodes[$makeCode];
    $numModelCodes = count($makeModelCodes);
    $modelCode = $makeModelCodes[mt_rand(0, $numModelCodes -1)];

    $make = $entityManager->getRepository(\DvsaEntities\Entity\Make::class)->getReference($makeCode);
    $model = $entityManager->getRepository(\DvsaEntities\Entity\Model::class)->
        getReference(['make' => $makeCode, 'id' => $modelCode]);

    $randomDate = mt_rand(1950, 2013) . '-' . sprintf('%02d', mt_rand(1, 12)) . '-'
        . sprintf('%02d', mt_rand(1, 28));

    $vehicle = new Vehicle;
    $vehicle
        ->setVin(generateRandomVin())
        ->setRegistration(generateRandomString(mt_rand(4, 8)))
        ->setCylinderCapacity(mt_rand(500, 3499))
        ->setFirstUsedDate(DateUtils::toDate($randomDate))
        ->setModel($model)
        ->setModelDetail($modelType)
        ->setColour($colour)
        ->setFuelType($fuelType)
        ->setCountryOfRegistration($countryOfRegistration)
        ->setTransmissionType($transmissionType)
        ->setVehicleClass($testClass);

    $entityManager->persist($vehicle);

    if ($v > 0 && $v % $batchSize === 0) {
        echo "Vehicle count is [$v] - flush\n";
        $entityManager->flush();
        // echo "memory used: " .  floor(memory_get_usage() / 1024 / 1024)  . "MB\n";
        $entityManager->clear(\DvsaEntities\Entity\Model::class);
        $entityManager->clear(\DvsaEntities\Entity\Vehicle::class);
    }
}
echo "Complete..";
$entityManager->flush();
echo ".\n";


/**
 * @return string
 */
function generateRandomVin()
{
    $vin = '';
    $length = mt_rand(3, 20);
    for ($i=0; $i<$length; $i++) {
        $vin .= mt_rand(0, 9);
    }
    return $vin;
}

function generateRandomString($length = 10)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[mt_rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function getRandomModelFromMake($entityManager, $make, &$modelCache)
{
    $makeId = $make->getId();
    if (!isset($modelCache[$makeId])) {
        $models = $entityManager->getRepository(\DvsaEntities\Entity\Model::class)->findByMake($make->getId());
        $modelCache[$makeId] = $models;
    }
    $numModels = count($modelCache[$makeId]);
    if ($numModels > 0) {
        $model = $modelCache[$makeId][mt_rand(0, $numModels - 1)];
        $modelCache = []; // knobble the cache to avoid memory issues.
        return $model;
    }
    return null;
}
