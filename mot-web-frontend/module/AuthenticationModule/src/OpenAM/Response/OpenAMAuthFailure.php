<?php

/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response;

use Zend\View\Model\ViewModel;

/**
 * OpenAMAuthFailure represents a failed OpenAM authentication attempt.
 */
class OpenAMAuthFailure implements OpenAMAuthenticationResponse
{
    /**
     * @var int
     */
    private $code;

    /**
     * @var ViewModel
     */
    private $viewModel;

    /**
     * @param int       $code
     * @param ViewModel $viewModel
     */
    public function __construct($code, ViewModel $viewModel)
    {
        $this->code = $code;
        $this->viewModel = $viewModel;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return ViewModel
     */
    public function getViewModel()
    {
        return $this->viewModel;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccess()
    {
        return false;
    }
}
