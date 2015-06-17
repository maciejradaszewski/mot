<?php

namespace MotFitnesse\Licensing\Notification;

use MotFitnesse\Util\RetrieveCheckingHelper;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class NotificationHelper extends RetrieveCheckingHelper
{

    public function __construct($personId)
    {
        parent::__construct($personId);
    }

    protected function retrieve($id)
    {
        return TestShared::executeAndReturnResponseAsArrayFromUrlBuilder(
            $this->credentialsProvider, UrlBuilder::notificationForPerson($id)
        );
    }
}
