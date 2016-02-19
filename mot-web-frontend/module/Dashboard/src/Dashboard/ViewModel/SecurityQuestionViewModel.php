<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dashboard\ViewModel;

use Account\AbstractClass\AbstractSecurityQuestionViewModel;
use DvsaCommon\UrlBuilder\AccountUrlBuilderWeb;
use DvsaCommon\UrlBuilder\PersonUrlBuilderWeb;
use DvsaCommon\UrlBuilder\UserAdminUrlBuilderWeb;
use UserAdmin\Service\UserAdminSessionManager;
use Zend\Mvc\Controller\Plugin\FlashMessenger;

/**
 * Class SecurityQuestionViewModel.
 */
class SecurityQuestionViewModel extends AbstractSecurityQuestionViewModel
{
    /**
     * This function return the skip question link.
     *
     * @param FlashMessenger $flashMessenger
     *
     * @return UserAdminUrlBuilderWeb|string
     */
    public function getNextPageLink(FlashMessenger $flashMessenger)
    {
        if ($this->service->getQuestionNumber() == UserAdminSessionManager::FIRST_QUESTION) {
            if ($this->service->getQuestionSuccess() === true) {
                $questionNumber = UserAdminSessionManager::SECOND_QUESTION;

                return true === $this->isNewPersonProfileEnabled()
                    ? $this->generateSecurityQuestionsUrlForNewProfile($questionNumber)
                    : PersonUrlBuilderWeb::securityQuestions($questionNumber);
            }

            $flashMessenger->clearCurrentMessagesFromNamespace(FlashMessenger::NAMESPACE_ERROR);

            return AccountUrlBuilderWeb::forgottenPasswordNotAuthenticated();
        }

        if ($this->service->getQuestionSuccess() === true) {
            return true === $this->isNewPersonProfileEnabled()
                ? $this->generateSecuritySettingsUrlForNewProfile()
                : PersonUrlBuilderWeb::securitySettings();
        }

        return AccountUrlBuilderWeb::forgottenPasswordNotAuthenticated();
    }

    /**
     * This function returns the skip question link.
     *
     * @return UserAdminUrlBuilderWeb|string
     */
    public function getCurrentLink()
    {
        $questionNumber = $this->getQuestionNumber();

        return true === $this->isNewPersonProfileEnabled()
            ? $this->generateSecurityQuestionsUrlForNewProfile($questionNumber)
            : PersonUrlBuilderWeb::securityQuestions($questionNumber);
    }

    /**
     * @param int $questionNumber
     *
     * @return string
     */
    public function getFormActionUrl($questionNumber)
    {
        return true === $this->isNewPersonProfileEnabled()
            ? $this->generateSecurityQuestionsUrlForNewProfile($questionNumber)
            : (string) PersonUrlBuilderWeb::securityQuestions($questionNumber);
    }
}
