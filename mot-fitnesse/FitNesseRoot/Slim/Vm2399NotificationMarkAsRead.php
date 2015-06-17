<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class Vm2399NotificationMarkAsRead
{
    private $notificationId;
    private $readerUsername;

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
        $urlBuilder = UrlBuilder::notification($this->getNotificationId())->read();

        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            $urlBuilder->toString(), TestShared::METHOD_PUT, null, $this->readerUsername, TestShared::PASSWORD
        );

        $this->result = TestShared::execCurlForJson($curlHandle);

        return TestShared::resultIsSuccess($this->result);
    }

    public function errorMessages()
    {
        return TestShared::errorMessages($this->result);
    }

    public function setReaderUsername($readerUsername)
    {
        $this->readerUsername = $readerUsername;
    }
}
