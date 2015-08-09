<?php

/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response;

use Dvsa\Mot\Frontend\AuthenticationModule\Controller\SecurityController;
use Dvsa\OpenAM\Exception\OpenAMClientException;
use Dvsa\OpenAM\Exception\UserInactiveException;
use Dvsa\OpenAM\Exception\UserLockedException;
use Dvsa\OpenAM\OpenAMAuthProperties;
use Zend\View\Model\ViewModel;

/**
 * OpenAMAuthFailureBuilder produces OpenAMAuthFailure instances.
 */
class OpenAMAuthFailureBuilder
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
     * @param int $code
     *
     * @return OpenAMAuthFailure
     */
    public function createFromCode($code)
    {
        $vars = ['pageTitle' => SecurityController::PAGE_TITLE];
        $viewModel = new ViewModel($vars);
        $viewModel->setTemplate('authentication/failed/default');

        return new OpenAMAuthFailure($code, $viewModel);
    }

    /**
     * @param OpenAMClientException $exception
     *
     * @return OpenAMAuthFailure
     */
    public function createAuthFailureFromException(OpenAMClientException $exception)
    {
        if ($exception instanceof UserInactiveException || $exception instanceof UserLockedException) {
            $template = 'locked';
            $vars = [
                'helpdesk'     => $this->helpdeskConfig,
                'pageSubTitle' => SecurityController::PAGE_TITLE,
                'pageTitle'    => 'Your account has been locked',
            ];
        } else {
            $template = 'default';
            $vars = [
                'pageTitle' => SecurityController::PAGE_TITLE,
            ];
        }

        $viewModel = new ViewModel($vars);
        $viewModel->setTemplate('authentication/failed/' . $template);

        return new OpenAMAuthFailure($exception->getCode() ?: OpenAMAuthProperties::CODE_AUTHENTICATION_FAILED, $viewModel);
    }
}
