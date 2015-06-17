<?php

namespace TestSupport\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Utility\ArrayUtils;
use TestSupport\Helper\TestDataResponseHelper;
use DvsaCommon\UrlBuilder\UrlBuilder;
use TestSupport\Helper\DataGeneratorHelper;
use TestSupport\Helper\TestSupportRestClientHelper;

class VtsService
{

    const STATUS_APPROVED = 2;
    const NON_WORKING_DAY_COUNTRY_CODE = "GBENG";

    /**
     * @var TestSupportRestClientHelper
     */
    private $restClientHelper;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var UrlBuilder
     */
    private $urlBuilder;

    public function __construct(
        TestSupportRestClientHelper $restClientHelper,
        EntityManager $em,
        UrlBuilder $urlBuilder
    ) {
        $this->restClientHelper = $restClientHelper;
        $this->em = $em;
        $this->urlBuilder = $urlBuilder;
    }

    public function create(array $data)
    {
        $dataGenerator = DataGeneratorHelper::buildForDifferentiator($data);
        $email = $dataGenerator->emailAddress();

        $default = [
            'name' => ArrayUtils::tryGet($data, 'siteName', $dataGenerator->siteName()),
            'addressLine1' => ArrayUtils::tryGet($data, 'addressLine1', $dataGenerator->addressLine1()),
            'town' => ArrayUtils::tryGet($data, 'town', 'Boston'),
            'country' => ArrayUtils::tryGet($data, 'country', 'England'),
            'postcode' => ArrayUtils::tryGet($data, 'postcode', 'BT2 4RR'),
            'email' => ArrayUtils::tryGet($data, 'email', $email),
            'phoneNumber' => ArrayUtils::tryGet($data, 'phoneNumber', $dataGenerator->phoneNumber()),
            'correspondenceName' => ArrayUtils::tryGet($data, 'siteName', $dataGenerator->siteName()),
            'correspondenceAddressLine1' => ArrayUtils::tryGet($data, 'addressLine1', $dataGenerator->addressLine1()),
            'correspondenceTown' => ArrayUtils::tryGet($data, 'town', 'Bristol'),
            'correspondencePostcode' => ArrayUtils::tryGet($data, 'country', 'BS7 8RR'),
            'correspondenceEmail' => ArrayUtils::tryGet($data, 'email', $email),
            'correspondencePhoneNumber' => ArrayUtils::tryGet($data, 'phoneNumber', $dataGenerator->phoneNumber()),
            'nonWorkingDayCountry' => ArrayUtils::tryGet(
                $data,
                'nonWorkingDayCountry',
                self::NON_WORKING_DAY_COUNTRY_CODE
            ),
        ];
        $data = array_merge($data, $default);
        $result = $this->restClientHelper->getJsonClient($data)->post(
            UrlBuilder::of()->vehicleTestingStation()->toString(),
            $data
        );

        $siteId = $result['data']['id'];
        $siteNumber = $result['data']['siteNumber'];
        $this->addOtherDataToSite($siteId, $data);

        return TestDataResponseHelper::jsonOk(
            [
                "message" => "VTS created",
                "id" => $siteId,
                "siteNumber" => $siteNumber,
                "name" => $data["name"],
                "town" => $data["town"],
                "postcode" => $data["postcode"]
            ]
        );
    }


    /**
     * Direct DB access, needs to be removed
     */
    public function finishCreatingVtsWithHacking($siteId, $vehicleClasses)
    {
        foreach ($vehicleClasses as $vehicleClass) {
            $this->em->getConnection()->executeUpdate(
                "INSERT INTO auth_for_testing_mot_at_site (site_Id, vehicle_class_id, status_id, created_by)
                 VALUES (?,?,?,?)",
                [$siteId, $vehicleClass, self::STATUS_APPROVED, 2]
            );
        }
    }

    /**
     * @param int   $siteId
     * @param array $data
     *
     * @throws \Exception
     */
    private function addOtherDataToSite($siteId, $data)
    {
        if (isset($data['aeId'])) {
            $this->em->getConnection()->executeUpdate(
                "UPDATE site SET organisation_id = ? WHERE id = ?",
                [$data['aeId'], $siteId]
            );
        }

        $testGroups = ['1', '2'];
        if (isset($data['testGroup'])) {
            $testGroups = [$data['testGroup']];
        }
        $vehicleClasses = $this->vehicleClassesForTestGroups($testGroups);

        if (isset($data['classes'])) {
            if (isset($data['testGroup'])) {
                throw new \Exception("Specify either classes or testGroup, not both");
            }
            $vehicleClasses = $data['classes'];
        }

        $openingTimes = [];
        for ($i = 1; $i < 8; $i++) {

            /*
             * This isn't ideal. There is a half-hour closed window always. Overnight tests could well choke unless
             * started after midnight.
             */
            $openTime = '00:00:00';
            $closeTime = '23:30:00';
            $isClosed = false;

            $openingTimes['weeklySchedule'][] = [
                'weekday'   => $i,
                'openTime'  => $openTime,
                'closeTime' => $closeTime,
                'isClosed'  => $isClosed
            ];
        }

        $urlBuilder = $this->urlBuilder->vehicleTestingStation()->routeParam('id', $siteId)->siteOpeningHours();

        $this->restClientHelper->getJsonClient($data)->put($urlBuilder, $openingTimes);

        $this->finishCreatingVtsWithHacking($siteId, $vehicleClasses);
    }

    /**
     * @param $testGroups
     *
     * @return array
     */
    private function vehicleClassesForTestGroups($testGroups)
    {
        $vehicleClasses = [];

        if (in_array('1', $testGroups)) {
            array_push($vehicleClasses, '1', '2');
        }
        if (in_array('2', $testGroups)) {
            array_push($vehicleClasses, '3', '4', '5', '7');
        }

        return $vehicleClasses;
    }

}
