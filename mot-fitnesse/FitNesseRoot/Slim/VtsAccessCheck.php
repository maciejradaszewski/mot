<?php
use MotFitnesse\Testing\Authorisation\AbstractAuthorisationTest;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\VehicleTestingStationUrlBuilder;

class VtsAccessCheck extends AbstractAuthorisationTest
{
    private $resource;

    public function beginTable()
    {
        $this->setUpTestData();
    }

    public function setApiCall($resource)
    {
        $this->resource = $resource;
    }

    protected function callApi($username)
    {
        switch ($this->resource) {
            case 'EDIT VTS CONTACT DETAILS':
                $this->callEditSite($username);
                break;

            case 'READ VTS':
                $this->callReadVts($username);
                break;

            case 'UPDATE DEFAULT VTS SETTINGS':
                $this->callUpdateDefaultSettings($username);
                break;

            case 'MOT TEST IN PROGRESS':
                $this->callMotTestInProgress($username);
                break;

            case 'UPDATE VTS SCHEDULE':
                $this->callUpdateVtsSchedule($username);
                break;

            default:
                throw new \InvalidArgumentException("Unknown rest resource: \"" . $this->resource . "\"");
        }
    }

    private function callEditSite($username)
    {
        $postData = [
            "name"                       => "test",
            "addressLine1"               => "los santos",
            "town"                       => "andreas",
            "postcode"                   => "abs-123",
            "email"                      => "www@www.pl",
            "phoneNumber"                => "123456789",
            "correspondenceAddressLine1" => "los santos",
            "correspondenceTown"         => "andreas",
            "correspondencePostcode"     => "abs-123",
            "correspondenceEmail"        => "www@www.pl",
            "correspondencePhoneNumber"  => "123456788",
        ];

        $apiClient = FitMotApiClient::create($username, TestShared::PASSWORD);
        $urlBuilder = VehicleTestingStationUrlBuilder::vtsById($this->getVtsId());

        $apiClient->put($urlBuilder, $postData);
    }

    private function callReadVts($username)
    {
        $apiClient = FitMotApiClient::create($username, TestShared::PASSWORD);
        $urlBuilder = VehicleTestingStationUrlBuilder::vtsById($this->getVtsId());

        $apiClient->get($urlBuilder);
    }

    private function callMotTestInProgress($username)
    {
        $apiClient = FitMotApiClient::create($username, TestShared::PASSWORD);
        $urlBuilder = VehicleTestingStationUrlBuilder::testInProgress($this->getVtsId());

        $apiClient->get($urlBuilder);
    }

    private function callUpdateDefaultSettings($username)
    {
        $apiClient = FitMotApiClient::create($username, TestShared::PASSWORD);
        $urlBuilder = VehicleTestingStationUrlBuilder::defaultBrakeTests($this->getVtsId());

        $apiClient->put($urlBuilder, []);
    }

    private function callUpdateVtsSchedule($username)
    {
        $postData = [
            "weeklySchedule" => [
                [
                    "weekday"   => 1,
                    "openTime"  => "10:00:00",
                    "closeTime" => "15:00:00",
                    "isClosed"  => false
                ],
                [
                    "weekday"   => 2,
                    "openTime"  => "10:00:00",
                    "closeTime" => "15:00:00",
                    "isClosed"  => false
                ],
                [
                    "weekday"   => 3,
                    "openTime"  => "",
                    "closeTime" => "",
                    "isClosed"  => true
                ],
                [
                    "weekday"   => 4,
                    "openTime"  => "",
                    "closeTime" => "",
                    "isClosed"  => true
                ],
                [
                    "weekday"   => 5,
                    "openTime"  => "10:00:00",
                    "closeTime" => "15:00:00",
                    "isClosed"  => false
                ],
                [
                    "weekday"   => 6,
                    "openTime"  => "",
                    "closeTime" => "",
                    "isClosed"  => true
                ],
                [
                    "weekday"   => 7,
                    "openTime"  => "",
                    "closeTime" => "",
                    "isClosed"  => true
                ]
            ]
        ];

        $apiClient = FitMotApiClient::create($username, TestShared::PASSWORD);
        $urlBuilder = (new UrlBuilder())->vehicleTestingStation()->routeParam('id', $this->getVtsId())
            ->siteOpeningHours();;

        $apiClient->put($urlBuilder, $postData);
    }
}
