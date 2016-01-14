<?php

namespace Dashboard\Controller;

use Core\Controller\AbstractAuthActionController;
use Core\Service\MotFrontendIdentityProviderInterface;
use Dashboard\Form\ChangePasswordForm;
use Dashboard\Service\PasswordService;
use DvsaCommon\Configuration\MotConfig;
use DvsaCommon\Constants\FeatureToggle;
use DvsaCommon\InputFilter\Account\ChangePasswordInputFilter;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\StringUtils;
use Zend\Http\Response;

class PasswordController extends AbstractAuthActionController
{
    private $passwordService;

    protected $form;

    private $identityProvider;

    private $config;

    public function __construct(
        PasswordService $passwordService,
        ChangePasswordForm $form,
        MotFrontendIdentityProviderInterface $identityProvider,
        MotConfig $config
    )
    {
        $this->passwordService = $passwordService;
        $this->form = $form;
        $this->identityProvider = $identityProvider;
        $this->config = $config;
    }

    public function changePasswordAction()
    {
        $this->layout('layout/layout-govuk.phtml');
        $this->layout()->setVariable('pageSubTitle', "Your account");
        $this->layout()->setVariable('pageTitle', "Change your password");
        $breadcrumbs = [
            'Your account'         => '/profile',
            'Change your password' => '',
        ];

        $this->layout()->setVariable('breadcrumbs', ['breadcrumbs' => $breadcrumbs]);

        $hasPasswordExpired = $this->hasPasswordExpired();
        if ($hasPasswordExpired) {
            $this->layout()->setVariable('pageLede', "You need to change your password because it has expired");
        }

        $form = $this->form;
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost()->toArray());
            if ($form->isValid()) {
                if ($this->passwordService->changePassword($form->getData())) {
                    if ($hasPasswordExpired) {
                        return $this->redirect()->toRoute('user-home/profile/change-password/confirmation');
                    } else {
                        $this->addSuccessMessage("Your password has been changed.");
                        return $this->redirect()->toRoute('user-home/profile/byId');
                    }
                }

                $errors = $this->passwordService->getErrors();
                $form->setMessages($errors);
            }

            $form->clearValues();
        }

        $form->obfuscateOldPasswordElementName();

        $newProfileEnabled = $this->isFeatureEnabled(FeatureToggle::NEW_PERSON_PROFILE);

        return [
            'form'        => $form,
            'username'    => $this->getIdentity()->getUsername(),
            'cancelRoute' => $hasPasswordExpired ? "logout" : "user-home/profile/byId",
            'cancelText'  => $hasPasswordExpired ? "Cancel and return to sign in" : "Cancel and return to your profile",
            'newProfileEnabled' => $newProfileEnabled,
        ];
    }

    private function hasPasswordExpired()
    {
        return $this->identityProvider->getIdentity()->hasPasswordExpired()
        && $this->config->get('feature_toggle', 'openam.password.expiry.enabled');
    }

    public function confirmationAction()
    {
        $this->layout('layout/layout-govuk.phtml');
        $this->layout()->setVariable('pageSubTitle', "MOT Testing Service");
        $this->layout()->setVariable('pageTitle', "Password change");
        $this->layout()->setVariable('pageLede', "Your password has been changed successfully");

        return [];
    }
}
