<?php
namespace NotificationApi\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;

/**
 * Class PersonReadNotificationController
 *
 * @package NotificationApi\Controller
 */
class PersonReadNotificationController extends AbstractDvsaRestfulController
{

    public function getList()
    {
        return ApiResponse::jsonOk('personal notification -> get list of read notifications');
    }

    public function replaceList($data)
    {
        return ApiResponse::jsonOk('personal notification -> mark as read');
    }
}
