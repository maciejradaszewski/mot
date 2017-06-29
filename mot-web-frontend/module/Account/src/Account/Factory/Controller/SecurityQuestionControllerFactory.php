<?php

namespace Account\Factory\Controller;

use Account\Action\PasswordReset\AnswerSecurityQuestionsAction;
use Account\Controller\SecurityQuestionController;
use Account\Service\SecurityQuestionService;
use UserAdmin\Service\UserAdminSessionManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SecurityQuestionControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        $appServiceLocator = $controllerManager->getServiceLocator();

        /* @var SecurityQuestionService */
        $service = $appServiceLocator->get(SecurityQuestionService::class);

        /** @var AnswerSecurityQuestionsAction $action */
        $action = $appServiceLocator->get(AnswerSecurityQuestionsAction::class);

        /* @var UserAdminSessionManager */
        $userAdminSessionManager = $appServiceLocator->get(UserAdminSessionManager::class);

        $controller = new SecurityQuestionController(
            $service,
            $userAdminSessionManager,
            $action
        );

        return $controller;
    }
}
