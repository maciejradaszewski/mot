<?php

use DvsaCommon\Enum\SiteBusinessRoleCode;
use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm2231SitePositionRemoval
{
    private $site;
    private $recipient;

    public function success()
    {
        $inputTesterNomination = $this->inputSiteManagerNomination();

        // sending nomination
        $nominatorClient = FitMotApiClient::create('aedm', TestShared::PASSWORD);
        $nominationUrl = UrlBuilder::sitePosition($this->site);
        $positionId = $nominatorClient->post($nominationUrl, $inputTesterNomination)['id'];

        // accepting nomination
        $nomineeClient = FitMotApiClient::create('inactivetester', TestShared::PASSWORD);
        $notificationForPersonUrl = UrlBuilder::notificationForPerson($inputTesterNomination['nomineeId']);
        $notifications = $nominatorClient->get($notificationForPersonUrl);
        $notification = array_shift($notifications);

        $nomineeClient->put(
            UrlBuilder::notification($notification['id'])->action(),
            ['action' => 'SITE-NOMINATION-ACCEPTED']
        );

        // removing position
        $nominatorClient->delete(UrlBuilder::sitePosition($this->site, $positionId));

        $vtsUrl = (new UrlBuilder())->vehicleTestingStation()->routeParam("id", $this->site);
        $result = $nominatorClient->get($vtsUrl);

        /** @var \DvsaCommon\Dto\Site\VehicleTestingStationDto $dto */
        $dto = \DvsaCommon\Utility\DtoHydrator::jsonToDto($result);
        $positions = $dto->getPositions();

        // verifying position is no longer there
        return $this->isPositionRemoved($positions, $positionId);
    }

    public function setSite($value)
    {
        $this->site = $value;
    }

    public function setRecipient($value)
    {
        $this->recipient = $value;
    }

    /**
     * @param array $positions
     * @param $posId
     *
     * @return bool
     */
    private function isPositionRemoved($positions, $posId)
    {
        foreach ($positions as $pos) {
            if ($pos->getId() === $posId) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return array
     */
    private function inputSiteManagerNomination()
    {
        return [
            'nomineeId' => $this->recipient,
            'roleCode' => SiteBusinessRoleCode::SITE_MANAGER,
        ];
    }
}
