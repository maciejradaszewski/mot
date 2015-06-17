<?php

namespace Account\ViewModel;

use Account\AbstractClass\AbstractSecurityQuestionViewModel;
use Account\Service\SecurityQuestionService;
use DvsaCommon\UrlBuilder\AccountUrlBuilderWeb;
use DvsaCommon\UrlBuilder\UserAdminUrlBuilderWeb;
use UserAdmin\Service\UserAdminSessionManager;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\View\Model\ViewModel;

/**
 * Class SecurityQuestionViewModel
 * @package Account\ViewModel
 */
class SecurityQuestionViewModel extends AbstractSecurityQuestionViewModel
{
    /**
     * @param SecurityQuestionService $service
     */
    public function __construct($service)
    {
        parent::__construct($service);
    }

    /**
     * This function return the skip question link
     *
     * @param FlashMessenger $flashMessenger
     * @return UserAdminUrlBuilderWeb
     */
    public function getNextPageLink(FlashMessenger $flashMessenger)
    {
        if ($this->service->getQuestionNumber() == UserAdminSessionManager::FIRST_QUESTION) {
            if ($this->service->getQuestionSuccess() === true) {
                return AccountUrlBuilderWeb::forgottenPasswordSecurityQuestion(
                    $this->getUserId(),
                    UserAdminSessionManager::SECOND_QUESTION
                );
            }
            $flashMessenger->clearCurrentMessagesFromNamespace(FlashMessenger::NAMESPACE_ERROR);
            return AccountUrlBuilderWeb::forgottenPasswordNotAuthenticated();
        }
        if ($this->service->getQuestionSuccess() === true) {
            return AccountUrlBuilderWeb::forgottenPasswordAuthenticated();
        }
        return AccountUrlBuilderWeb::forgottenPasswordNotAuthenticated();
    }

    /**
     * This function return the skip question link
     *
     * @return UserAdminUrlBuilderWeb
     */
    public function getCurrentLink()
    {
        return AccountUrlBuilderWeb::forgottenPasswordSecurityQuestion(
            $this->getUserId(),
            $this->getQuestionNumber()
        );
    }
}
