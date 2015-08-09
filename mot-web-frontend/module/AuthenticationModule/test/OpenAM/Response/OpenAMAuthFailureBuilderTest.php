<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\AuthenticationModuleTest\OpenAM\Response;

use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response\OpenAMAuthFailure;
use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response\OpenAMAuthFailureBuilder;
use Dvsa\OpenAM\Exception\OpenAMClientException;
use Dvsa\OpenAM\OpenAMAuthProperties;

class OpenAMAuthFailureBuilderTest extends \PHPUnit_Framework_TestCase
{
    const TEMPLATE_DEFAULT = 'authentication/failed/default';
    const TEMPLATE_LOCKED = 'authentication/failed/locked';

    /**
     * @var OpenAMAuthFailureBuilder
     */
    private $builder;

    public function setUp()
    {
        $this->builder = new OpenAMAuthFailureBuilder(['name' => 'DVSA Helpdesk']);
    }

    public function testCreateFromCodeReturnsAuthFailureInstance()
    {
        $authFailure = $this->builder->createFromCode(1);
        $this->assertInstanceOf(OpenAMAuthFailure::class, $authFailure);
    }

    public function testCreateFromCodeUsesCorrectCode()
    {
        $code = 1;
        $authFailure = $this->builder->createFromCode($code);
        $this->assertEquals($code, $authFailure->getCode());
    }

    public function testCreateFromCodeUsesDefaultTemplate()
    {
        $authFailure = $this->builder->createFromCode(1);
        $viewModel = $authFailure->getViewModel();
        $this->assertEquals(self::TEMPLATE_DEFAULT, $viewModel->getTemplate());
    }

    public function testCreateFromCodeHasSameViewModelAsCreateFromException()
    {
        $code = OpenAMAuthProperties::CODE_INVALID_PASSWORD;
        $authFailureFromCode = $this->builder->createFromCode($code);
        $exception = new OpenAMClientException(OpenAMAuthProperties::ERROR_INVALID_CREDENTIALS);
        $authFailureFromException = $this->builder->createAuthFailureFromException($exception);

        $this->assertEquals(
            $authFailureFromCode->getViewModel()->getVariables(),
            $authFailureFromException->getViewModel()->getVariables()
        );
        $this->assertEquals(
            $authFailureFromCode->getViewModel()->getTemplate(),
            $authFailureFromException->getViewModel()->getTemplate()
        );
    }

    public function testCreateFromExceptionReturnsAuthFailureInstance()
    {
        $authFailure = $this->builder->createAuthFailureFromException(new OpenAMClientException('message'));
        $this->assertInstanceOf(OpenAMAuthFailure::class, $authFailure);
    }

    public function testCreateFromInactiveOrLockedExceptionUsesSameTemplate()
    {
        $userInactiveException = new OpenAMClientException(OpenAMAuthProperties::ERROR_USER_INACTIVE);
        $userInactiveAuthFailure = $this->builder->createAuthFailureFromException($userInactiveException);

        $userLockedException = new OpenAMClientException(OpenAMAuthProperties::ERROR_USER_INACTIVE);
        $userLockedAuthFailure = $this->builder->createAuthFailureFromException($userLockedException);

        $this->assertEquals($userInactiveAuthFailure->getViewModel(), $userLockedAuthFailure->getViewModel());
    }

    public function testCreateFromInvalidPasswordOrCredentialsInvalidExceptionUsesSameTemplate()
    {
        $invalidPasswordException = new OpenAMClientException(OpenAMAuthProperties::ERROR_INVALID_CREDENTIALS);
        $invalidPasswordAuthFailure = $this->builder->createAuthFailureFromException($invalidPasswordException);

        $invalidCredentialsException = new OpenAMClientException(OpenAMAuthProperties::ERROR_INVALID_PASSWORD);
        $invalidCredentialsAuthFailure = $this->builder->createAuthFailureFromException($invalidCredentialsException);

        $this->assertEquals($invalidPasswordAuthFailure->getViewModel(), $invalidCredentialsAuthFailure->getViewModel());
    }

    public function testCreateFromExceptionWithUndefinedMessageReturnsDefaultTemplate()
    {
        $unknownAuthFailure = $this->builder->createAuthFailureFromException(new OpenAMClientException(''));

        $viewModel = $unknownAuthFailure->getViewModel();
        $this->assertEquals(self::TEMPLATE_DEFAULT, $viewModel->getTemplate());
    }
}
