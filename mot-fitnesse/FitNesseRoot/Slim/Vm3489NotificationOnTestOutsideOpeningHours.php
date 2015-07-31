<?php

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\FitHelper;
use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 *
 */
class Vm3489NotificationOnTestOutsideOpeningHours
{
    private $siteId;
    private $isSent;
    private $recipient;
    private $username;
    private $areaOffice;


    public function execute()
    {
        $client = FitMotApiClient::create($this->username, TestShared::PASSWORD);

        $recipientId = $this->findRecipientId($client);
        $notificationUrl = UrlBuilder::notificationForPerson($recipientId);
        $data = $client->get($notificationUrl);

        $this->isSent = $this->hasTestingOutsideHoursNotification($data);
    }

    private function hasTestingOutsideHoursNotification($data)
    {
        foreach ($data as $value) {
            if (strpos(strtolower($value['subject']), strtolower('Test outside opening hours')) !== false) {
                return true;
            }
        }
        return false;
    }


    private function findRecipientId(FitMotApiClient $client)
    {
        $foundId = null;
        $vtsUrl = (new UrlBuilder())->vehicleTestingStation()->routeParam("id", $this->siteId);
        $vtsData = $client->get($vtsUrl);
        /** @var \DvsaCommon\Dto\Site\VehicleTestingStationDto $dto */
        $dto = \DvsaCommon\Utility\DtoHydrator::jsonToDto($vtsData);

        $positions = $dto->getPositions();
        foreach ($positions as $pos) {
            if ($pos->getPerson()->getUsername() === $this->recipient) {
                $foundId = $pos->getPerson()->getId();
                break;
            }
        }
        if (is_null($foundId)) {
            $client = FitMotApiClient::create($this->areaOffice, TestShared::PASSWORD);
            $orgId = $dto->getOrganisation()->getId();
            $orgPosUrl = (new UrlBuilder())->organisationPositionNomination($orgId);
            $aeData = $client->get($orgPosUrl);
            foreach ($aeData as $datum) {
                if ($datum['person']['username'] === $this->recipient) {
                    $foundId = $datum['person']['id'];
                    break;
                }
            }
        }

        if ($foundId === null) {
            throw new Exception("No person id found for recipient of type: " . $this->recipient);
        }
        return $foundId;
    }

    public function reset()
    {
        $this->recipient = null;
        $this->isSent = null;
    }

    public function setRecipient($v)
    {
        $this->recipient = $v;
    }

    public function setUsername($v)
    {
        $this->username = $v;
    }

    public function sent()
    {
        return FitHelper::decode(var_export($this->isSent, true), ['true' => 'Y', 'false' => 'N']);
    }

    public function setSiteId($v)
    {
        $this->siteId = $v;
    }

    public function setAreaOffice($v)
    {
        $this->areaOffice = $v;
    }
}
