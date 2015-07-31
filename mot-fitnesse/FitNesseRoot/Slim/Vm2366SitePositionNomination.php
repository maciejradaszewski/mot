<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;
use DvsaCommon\Enum\BusinessRoleStatusCode;
use MotFitnesse\Util\CredentialsProvider;

class Vm2366SitePositionNomination
{
    const SITE_NOMINATION_ACCEPTED = 'SITE-NOMINATION-ACCEPTED';
    const SITE_NOMINATION_REJECTED = 'SITE-NOMINATION-REJECTED';

    private $recipient;
    private $position;
    private $vts;
    private $response;

    private $isSuccess;
    private $result;

    /**
     * @var integer Id
     */
    private $notification;

    /**
     * @var string
     */
    private $recipientUsername;

    /**
     * @var string
     */
    private $aedmUsername;

    /**
     * @param string $aedmUsername
     */
    public function __construct($aedmUsername)
    {
        $this->aedmUsername = $aedmUsername;
    }

    public function success()
    {
        $nomination = $this->inputVtsNomination();

        $urlBuilder = UrlBuilder::sitePosition($this->getVts());

        $this->result = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            $this->createCredentialProvider($this->aedmUsername), $urlBuilder, $nomination
        );
        $this->isSuccess = TestShared::resultIsSuccess($this->result);

        return $this->isSuccess;
    }

    public function responseSent()
    {
        if ($this->isSuccess) {
            $urlBuilder = UrlBuilder::notificationForPerson($this->recipient);

            $this->notification = TestShared::execCurlForJsonFromUrlBuilder(
                $this->createCredentialProvider($this->recipientUsername),
                $urlBuilder
            )['data'][0]['id'];

            $nomination = $this->inputResponse();
            $urlBuilder = UrlBuilder::notification($this->notification)->action();

            $this->result = TestShared::execCurlFormPutForJsonFromUrlBuilder(
                $this->createCredentialProvider($this->recipientUsername), $urlBuilder, $nomination
            );

            $this->isSuccess = TestShared::resultIsSuccess($this->result);

            return $this->isSuccess;
        }
        return false;
    }

    /**
     * @param string $username
     *
     * @return CredentialsProvider
     */
    private function createCredentialProvider($username)
    {
        return new CredentialsProvider($username, TestShared::PASSWORD);
    }

    public function recipientHasPositionAtSite()
    {
        $urlBuilder = (new UrlBuilder)->vehicleTestingStation()->routeParam('id', $this->getVts());

        $result = TestShared::get(
            $urlBuilder->toString(), $this->aedmUsername, TestShared::PASSWORD
        );

        return $this->isPositionAccepted($result);
    }

    private function isPositionAccepted($result)
    {
        /** @var \DvsaCommon\Dto\Site\VehicleTestingStationDto $dto */
        $dto = \DvsaCommon\Utility\DtoHydrator::jsonToDto($result);

        $positions = $dto->getPositions();

        if (count($positions) > 0) {
            $position = $positions[count($positions) - 1];

            return $position->getRole()->getCode() == $this->getPosition()
            && $position->getPerson()->getId() == $this->getRecipient()
            && $position->getRoleStatus()->getCode() === BusinessRoleStatusCode::ACTIVE;
        } else {
            return false;
        }
    }

    public function errorMessages()
    {
        return TestShared::errorMessages($this->result);
    }

    /**
     * @param integer $vts
     *
     * @return $this
     */
    public function setVts($vts)
    {
        $this->vts = $vts;
        return $this;
    }

    /**
     * @return integer
     */
    public function getVts()
    {
        return $this->vts;
    }

    /**
     * @param integer $recipient
     *
     * @return $this
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @return integer
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param string $response
     *
     * @return $this
     */
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        if ('accepted' === $this->response) {
            return self::SITE_NOMINATION_ACCEPTED;
        }
        if ('rejected' === $this->response) {
            return self::SITE_NOMINATION_REJECTED;
        }
        return 'N/A';
    }

    /**
     * @param array $result
     *
     * @return $this
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param string $position
     *
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return array
     */
    private function inputVtsNomination()
    {
        return [
            'nomineeId' => $this->getRecipient(),
            'roleCode'  => $this->getPosition(),
        ];
    }

    /**
     * @return array
     */
    protected function inputResponse()
    {
        return [
            'action' => $this->getResponse()
        ];
    }

    /**
     * @param string $recipientUsername
     */
    public function setRecipientUsername($recipientUsername)
    {
        $this->recipientUsername = $recipientUsername;
    }
}
