<?php

namespace UserApi\Message\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use UserApi\Message\Service\MessageService;

class MessageController extends AbstractDvsaRestfulController
{
    public function create($data)
    {
        return ApiResponse::jsonOk(
            $this->getMessageService()->createMessage($data)
        );
    }

    /**
     * @return MessageService
     */
    private function getMessageService()
    {
        return $this->getServiceLocator()->get(MessageService::class);
    }
}
