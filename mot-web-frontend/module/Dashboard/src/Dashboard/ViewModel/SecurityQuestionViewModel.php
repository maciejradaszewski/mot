<?php

namespace Dashboard\ViewModel;

use Account\AbstractClass\AbstractSecurityQuestionViewModel;
use Account\Service\SecurityQuestionService;
use DvsaCommon\UrlBuilder\AccountUrlBuilderWeb;
use DvsaCommon\UrlBuilder\PersonUrlBuilderWeb;
use DvsaCommon\UrlBuilder\UserAdminUrlBuilderWeb;
use UserAdmin\Service\UserAdminSessionManager;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\View\Model\ViewModel;

/**
 * Class SecurityQuestionViewModel
 * @package Dashboard\ViewModel
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
                return PersonUrlBuilderWeb::securityQuestions(UserAdminSessionManager::SECOND_QUESTION);
            }
            $flashMessenger->clearCurrentMessagesFromNamespace(FlashMessenger::NAMESPACE_ERROR);
            return AccountUrlBuilderWeb::forgottenPasswordNotAuthenticated();
        }
        if ($this->service->getQuestionSuccess() === true) {
            return PersonUrlBuilderWeb::securitySettings();
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
        return PersonUrlBuilderWeb::securityQuestions($this->getQuestionNumber());
    }
}
