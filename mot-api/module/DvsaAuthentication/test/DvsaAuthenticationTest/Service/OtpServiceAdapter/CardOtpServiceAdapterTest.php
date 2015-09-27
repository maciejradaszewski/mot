<?php

namespace DvsaAuthenticationTest\Service;

use Dvsa\OpenAM\Exception\OpenAMClientException;
use Dvsa\OpenAM\Model\OpenAMLoginDetails;
use Dvsa\OpenAM\OpenAMClientInterface;
use DvsaAuthentication\Service\OtpServiceAdapter;
use DvsaAuthentication\Service\OtpServiceAdapter\CardOtpServiceAdapter;
use DvsaEntities\Entity\Person;

class CardOtpServiceAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CardOtpServiceAdapter
     */
    private $otpServiceAdapter;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $openAMClient;

    protected function setUp()
    {
        $this->openAMClient = $this->getMock(OpenAMClientInterface::class);
        $this->otpServiceAdapter = new CardOtpServiceAdapter($this->openAMClient);
    }

    public function testItIsAnOtpServiceAdapter()
    {
        $this->assertInstanceOf(OtpServiceAdapter::class, $this->otpServiceAdapter);
    }

    public function testItReturnsTrueIfCredentialsAreValidated()
    {
        $person = $this->getPerson('bob');
        $token = '123123';

        $this->openAMClientWillValidateCredentials('bob', $token);

        $result = $this->otpServiceAdapter->authenticate($person, $token);

        $this->assertTrue($result);
    }

    public function testItReturnsFalseIfCredentialsAreNotValidated()
    {
        $this->openAMClientWillNotValidateCredentials();

        $result = $this->otpServiceAdapter->authenticate($this->getPerson('bob'), '123123');

        $this->assertFalse($result);
    }

    public function testItReturnsFalseIfOpenAMClientThrowsAnException()
    {
        $this->openAMClientWillThrowAnExceptionDuringValidateCredentials();

        $result = $this->otpServiceAdapter->authenticate($this->getPerson('bob'), '123123');

        $this->assertFalse($result);
    }

    /**
     * @param string $username
     *
     * @return Person
     */
    private function getPerson($username)
    {
        return (new Person())->setUsername($username);
    }

    private function openAMClientWillValidateCredentials($username, $password)
    {
        $this->openAMClient->expects($this->once())
            ->method('validateCredentials')
            ->with(new OpenAMLoginDetails(
                $username, $password, CardOtpServiceAdapter::REALM, CardOtpServiceAdapter::AUTHENTICATION_MODULE
            ))
            ->willReturn(true);
    }

    private function openAMClientWillNotValidateCredentials()
    {
        $this->openAMClient->expects($this->any())
            ->method('validateCredentials')
            ->willReturn(false);
    }

    private function openAMClientWillThrowAnExceptionDuringValidateCredentials()
    {
        $this->openAMClient->expects($this->any())
            ->method('validateCredentials')
            ->willThrowException(new OpenAMClientException('Client error.'));
    }
}