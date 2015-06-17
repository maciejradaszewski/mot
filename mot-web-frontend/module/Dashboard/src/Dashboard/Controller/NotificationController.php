<?php
namespace Dashboard\Controller;

use Core\Controller\AbstractAuthActionController;
use Dashboard\Data\ApiNotificationResource;
use Dashboard\Model\Notification;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;

/**
 * Class NotificationController
 */
class NotificationController extends AbstractAuthActionController
{

    const ROUTE_NOTIFICATION = 'user-home/notification/item';
    const ROUTE_NOTIFICATION_LIST = 'user-home/notification/list';
    const ROUTE_NOTIFICATION_ACTION = 'user-home/notification/action';

    const SHORT_LIST_LIMIT = 5;

    public function notificationAction()
    {
        $notificationId = $this->params()->fromRoute('notificationId', null);

        if ($notificationId) {
            $api = $this->getServiceLocator()->get(ApiNotificationResource::class);
            $notificationApiResponse = $api->markAsRead($notificationId);
            $notification = new Notification($notificationApiResponse);

            $this->layout('layout/layout-govuk.phtml');
            $this->layout()->setVariable('pageTitle', $notification->getSubject());
            $this->layout()->setVariable('pageSubTitle', 'Notifications');

            $viewModel = new ViewModel();
            $viewModel->setVariable('notification', $notification);

            if ($notification->isActionRequired()) {
                $viewModel->setTemplate('dashboard/notification/notification_action.phtml');
            } else {
                $viewModel->setTemplate('dashboard/notification/notification_general.phtml');
            }

            return $viewModel;
        }

        throw new \Exception('Notification not found');
    }

    public function listAction()
    {
        $userId = $this->getIdentity()->getUserId();
        $api = $this->getServiceLocator()->get(ApiNotificationResource::class);

        return [
            'notifications' => Notification::createList($api->getList($userId))
        ];
    }

    public function confirmNominationAction()
    {
        /** @var $request Request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $postData = $request->getPost()->toArray();

            if (isset($postData['notificationId']) && isset($postData['action']) && isset($postData['url'])) {
                /** @var $api ApiNotificationResource */
                $api = $this->getServiceLocator()->get(ApiNotificationResource::class);
                $personId = $this->getIdentity()->getUserId();

                $api->notificationAction($personId, $postData['notificationId'], $postData['action']);

                return $this->redirect()->toUrl($postData['url']);
            }
            throw new \RuntimeException('Wrong data');
        }
        throw new \RuntimeException('Method not allowed');
    }
}
