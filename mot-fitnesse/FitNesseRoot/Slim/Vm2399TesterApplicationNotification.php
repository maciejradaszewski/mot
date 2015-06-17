<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class Vm2399TesterApplicationNotification
{
    private $recipientId;
    private $applicationReference;
    private $notificationId;
    private $senderUsername;

    private $result;

    const TEMPLATE_APPROVED = 1;

    /**
     * @param mixed $applicationReference
     *
     * @return $this
     */
    public function setApplicationReference($applicationReference)
    {
        $this->applicationReference = $applicationReference;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApplicationReference()
    {
        return $this->applicationReference;
    }

    /**
     * @param mixed $recipientId
     *
     * @return $this
     */
    public function setRecipientId($recipientId)
    {
        $this->recipientId = $recipientId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecipientId()
    {
        return $this->recipientId;
    }

    public function setInfo()
    {
    }

    public function success()
    {
        $notification = $this->input();

        $urlBuilder = UrlBuilder::notificationForPerson($this->getRecipientId());

        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            $urlBuilder->toString(), TestShared::METHOD_POST, $notification, $this->senderUsername, TestShared::PASSWORD
        );

        $this->result = TestShared::execCurlForJson($curlHandle);

        $this->notificationId = $this->result['data']['id'];

        return TestShared::resultIsSuccess($this->result);
    }

    public function errorMessages()
    {
        return TestShared::errorMessages($this->result);
    }

    /**
     * @return array
     */
    protected function input()
    {
        return $experience
            = [
            'template'  => self::TEMPLATE_APPROVED,
            'recipient' => $this->getRecipientId(),
            'fields'    => [
                'applicationReference' => "Fake Application UUID",
            ],
        ];
    }

    public function notificationId()
    {
        return $this->notificationId;
    }

    public function setSenderUsername($senderUsername)
    {
        $this->senderUsername = $senderUsername;
    }
}
