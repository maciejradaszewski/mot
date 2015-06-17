<?php
namespace NotificationApi\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use NotificationApi\Mapper\NotificationMapper;
use NotificationApi\Service\NotificationService;

/**
 * Class PersonNotificationController
 *
 * @package NotificationApi\Controller
 */
class PersonNotificationController extends AbstractDvsaRestfulController
{

    public function create($data)
    {
        $service = $this->getNotificationService();
        $result = $service->add($data);

        $extractor = new NotificationMapper();

        return ApiResponse::jsonOk($extractor->toArray($service->get($result)));
    }

    public function getList()
    {
        $personId = $this->params()->fromRoute('personId', null);
        $service = $this->getNotificationService();
        $notifications = $service->getAllByPersonId($personId);

        $extractedResult = [];

        if (is_array($notifications)) {
            $extractor = new NotificationMapper();
            foreach ($notifications as $notification) {
                $extractedResult[] = $extractor->toArray($notification);
            }
        }

        return ApiResponse::jsonOk($extractedResult);
    }

    /**
     * @return NotificationService
     */
    private function getNotificationService()
    {
        return $this->getServiceLocator()->get(NotificationService::class);
    }
}
