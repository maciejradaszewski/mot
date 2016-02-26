<?php

namespace Dvsa\Mot\Frontend\AuthenticationModule\Service;

use Dvsa\Mot\Frontend\AuthenticationModule\Controller\SecurityController;
use Dvsa\OpenAM\OpenAMAuthProperties;
use DvsaCommon\Authn\AuthenticationResultCode;
use DvsaCommon\Dto\Authn\AuthenticationResponseDto;
use DvsaCommon\Dto\Common\KeyValue;
use Zend\View\Model\ViewModel;


class AuthenticationFailureViewModelBuilder
{
    /**
     * @var array
     */
    private $helpdeskConfig;


    /**
     * @param array $helpdeskConfig
     */
    public function __construct(array $helpdeskConfig)
    {
        $this->helpdeskConfig = $helpdeskConfig;
    }


    /**
     * @param AuthenticationResponseDto $response
     * @return ViewModel
     */
    public function createFromAuthenticationResponse(AuthenticationResponseDto $response)
    {
        $code = $response->getAuthnCode();
        if ($code === AuthenticationResultCode::ACCOUNT_LOCKED) {
            $template = 'locked';
            $vars = [
                'helpdesk'     => $this->helpdeskConfig,
                'pageSubTitle' => SecurityController::PAGE_TITLE,
                'pageTitle'    => 'Your account has been locked',
            ];
        } elseif ($code === AuthenticationResultCode::LOCKOUT_WARNING) {
            $attemptsBeforeLockout = KeyValue::find($response->getExtra(), 'attemptsLeft', 1);

            $yourAccountWillBeLockedMessage = sprintf('Your account will be locked for 30 minutes if you enter an incorrect password %d more time%s.',
                $attemptsBeforeLockout, $attemptsBeforeLockout == 1 ? '' : 's');

            $template = OpenAMAuthProperties::TEMPLATE_LOCKOUT_WARNING;
            $vars = [
                'helpdesk'                       => $this->helpdeskConfig,
                'pageSubTitle'                   => SecurityController::PAGE_TITLE,
                'pageTitle'                      => 'Authentication failed',
                'yourAccountWillBeLockedMessage' => $yourAccountWillBeLockedMessage,
            ];
        } else {
            $template = 'default';
            $vars = [
                'pageTitle' => SecurityController::PAGE_TITLE,
            ];
        }

        $viewModel = new ViewModel($vars);
        $viewModel->setTemplate('authentication/failed/' . $template);

        return $viewModel;
    }
}
