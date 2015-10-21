<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\AuthenticationModuleTest\OpenAM;

use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\OpenAMAuthenticator;
use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response\OpenAMAuthFailure;
use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response\OpenAMAuthFailureBuilder;
use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response\OpenAMAuthSuccess;
use Dvsa\OpenAM\Exception\InvalidCredentialsException;
use Dvsa\OpenAM\Exception\InvalidPasswordException;
use Dvsa\OpenAM\Exception\OpenAMClientException;
use Dvsa\OpenAM\Exception\TooManyAuthenticationAttemptsException;
use Dvsa\OpenAM\Exception\UserInactiveException;
use Dvsa\OpenAM\Exception\UserLockedException;
use Dvsa\OpenAM\Model\OpenAMLoginDetails;
use Dvsa\OpenAM\OpenAMAuthProperties;
use Dvsa\OpenAM\OpenAMClientInterface;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use DvsaCommonTest\TestUtils\XMock;
use Zend\Log\Logger;
use Zend\View\Model\ViewModel;

class OpenAMAuthenticatorTest extends \PHPUnit_Framework_TestCase
{
    const REALM = 'realm';

    private $openAMClient;
    private $openAMOptions;
    private $authFailureBuilder;
    private $logger;

    public function setUp()
    {
        $this->openAMClient = XMock::of(OpenAMClientInterface::class);
        $this->logger = XMock::of(Logger::class);
        $this->openAMOptions = new OpenAMClientOptions();
        $this->openAMOptions->setRealm(self::REALM);
        $this->authFailureBuilder = new OpenAMAuthFailureBuilder(new OpenAMClientOptions(), []);
    }

    public function testAuthenticate_whenUsernameOrPasswordAreNull_expectFailure()
    {
        $result = $this->createAuthenticator()->authenticate('', 'password');

        $this->assertInstanceOf(OpenAMAuthFailure::class, $result);
        $this->assertEquals(OpenAMAuthProperties::CODE_AUTHENTICATION_FAILED, $result->getCode());

        $result = $this->createAuthenticator()->authenticate('username', '');
        $this->assertInstanceOf(OpenAMAuthFailure::class, $result);
        $this->assertEquals(OpenAMAuthProperties::CODE_AUTHENTICATION_FAILED, $result->getCode());

        $result = $this->createAuthenticator()->authenticate('', '');
        $this->assertInstanceOf(OpenAMAuthFailure::class, $result);
        $this->assertEquals(OpenAMAuthProperties::CODE_AUTHENTICATION_FAILED, $result->getCode());
    }

    public function testAuthenticate_whenClientReturnsToken_expectSuccess()
    {
        $username = 'username1';
        $password = 'Password1';
        $token = 'myToken';
        $this->openAMClient
            ->expects($this->atLeastOnce())
            ->method('authenticate')
            ->with(new OpenAMLoginDetails($username, $password, self::REALM))
            ->willReturn($token);

        $result = $this->createAuthenticator()->authenticate($username, $password);

        $this->assertEquals(new OpenAMAuthSuccess($token), $result);
    }

    /**
     * @return array
     */
    public static function data_clientThrowsException()
    {
        return [
            [
                new UserInactiveException(OpenAMAuthProperties::ERROR_USER_INACTIVE, 401),
                new OpenAMAuthFailure(OpenAMAuthProperties::CODE_USER_ACCOUNT_LOCKED, new ViewModel()),
            ],
            [
                new UserLockedException(OpenAMAuthProperties::ERROR_USER_LOCKED, 401),
                new OpenAMAuthFailure(OpenAMAuthProperties::CODE_USER_ACCOUNT_LOCKED, new ViewModel()),
            ],
            [
                new InvalidCredentialsException(OpenAMAuthProperties::ERROR_INVALID_CREDENTIALS, 401),
                new OpenAMAuthFailure(OpenAMAuthProperties::CODE_AUTHENTICATION_FAILED, new ViewModel()),
            ],
            [
                new InvalidPasswordException(OpenAMAuthProperties::ERROR_INVALID_PASSWORD, 401),
                new OpenAMAuthFailure(OpenAMAuthProperties::CODE_AUTHENTICATION_FAILED, new ViewModel()),
            ],
            [
                new OpenAMClientException(''),
                new OpenAMAuthFailure(OpenAMAuthProperties::CODE_AUTHENTICATION_FAILED, new ViewModel()),
            ],
            [
                new TooManyAuthenticationAttemptsException('', 429),
                new OpenAMAuthFailure(OpenAMAuthProperties::CODE_TOO_MANY_AUTHENTICATION_ATTEMPTS, new ViewModel()),
            ],
        ];
    }

    /**
     * @dataProvider data_clientThrowsException
     */
    public function testAuthenticate_whenClientThrowsException_expectProperResultTranslation($exception, $expectedResult)
    {
        $username = 'username1';
        $password = 'Password1';
        $this
            ->openAMClient
            ->expects($this->atLeastOnce())
            ->method('authenticate')
            ->will($this->throwException($exception));

        $result = $this->createAuthenticator()->authenticate($username, $password);

        $this->assertEquals($expectedResult->getCode(), $result->getCode());
    }

    /**
     * @return OpenAMAuthenticator
     */
    private function createAuthenticator()
    {
        return new OpenAMAuthenticator(
            $this->openAMClient,
            $this->openAMOptions,
            $this->authFailureBuilder,
            $this->logger
        );
    }
}
