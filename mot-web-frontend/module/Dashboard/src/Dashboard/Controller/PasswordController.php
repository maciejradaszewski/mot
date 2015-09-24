<?php

namespace Dashboard\Controller;

use Core\Controller\AbstractAuthActionController;
use Dashboard\Service\PasswordService;
use Dashboard\Form\ChangePasswordForm;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;

class PasswordController extends AbstractAuthActionController
{
    private $passwordService;

    protected $form;

    public function __construct(
        PasswordService $passwordService,
        ChangePasswordForm $form
    )
    {
        $this->passwordService = $passwordService;
        $this->form = $form;
    }

    public function changePasswordAction()
    {
        $this->layout('layout/layout-govuk.phtml');
        $this->layout()->setVariable('pageSubTitle',"MOT Testing Service");
        $this->layout()->setVariable('pageTitle', "Password change");
        $this->layout()->setVariable('pageLede', "You need to change your password because it has expired");

        $form = $this->form;
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost()->toArray());
            if ($form->isValid()) {
                if ($this->passwordService->changePassword($form->getData())) {
                    return $this->redirect()->toRoute('user-home/profile/change-password/confirmation');
                }

                $errors = $this->passwordService->getErrors();
                $form->setMessages($errors);
            }

            $form->clearValues();
        }

        return [
            'form' => $form,
            'username' => $this->getIdentity()->getUsername()
        ];
    }

    public function confirmationAction()
    {
        $this->layout('layout/layout-govuk.phtml');
        $this->layout()->setVariable('pageSubTitle',"MOT Testing Service");
        $this->layout()->setVariable('pageTitle', "Password change");
        $this->layout()->setVariable('pageLede', "Your password has been changed successfully");

        return [];
    }
}
