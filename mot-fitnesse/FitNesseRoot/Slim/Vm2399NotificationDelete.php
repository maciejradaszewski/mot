<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class Vm2399NotificationDelete
{
    private $notificationId;
    private $recipientUsername;

    private $result;

    /**
     * @param mixed $notificationId
     *
     * @return $this
     */
    public function setNotificationId($notificationId)
    {
        $this->notificationId = $notificationId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNotificationId()
    {
        return $this->notificationId;
    }

    public function setInfo()
    {
    }

    public function success()
    {
        $urlBuilder = UrlBuilder::notification($this->getNotificationId());

        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            $urlBuilder->toString(), TestShared::METHOD_DELETE, null, $this->recipientUsername, TestShared::PASSWORD
        );

        $this->result = TestShared::execCurlForJson($curlHandle);

        return TestShared::resultIsSuccess($this->result);
    }

    public function errorMessages()
    {
        return TestShared::errorMessages($this->result);
    }

    public function setRecipientUsername($recipientUsername)
    {
        $this->recipientUsername = $recipientUsername;
    }
}
