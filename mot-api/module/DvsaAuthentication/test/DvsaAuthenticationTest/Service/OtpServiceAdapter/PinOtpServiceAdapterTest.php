<?php

namespace DvsaAuthenticationTest\Service;

use DvsaAuthentication\Service\Exception\OtpException;
use DvsaAuthentication\Service\OtpServiceAdapter;
use DvsaAuthentication\Service\OtpServiceAdapter\PinOtpServiceAdapter;
use DvsaCommon\Auth\MotIdentity;
use DvsaCommon\Crypt\Hash\BCryptHashFunction;
use DvsaCommonApiTest\Service\AbstractServiceTest;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\Person;
use DvsaEntities\Repository\ConfigurationRepository;
use DvsaEntities\Repository\PersonRepository;
use Zend\Authentication\AuthenticationService;

class PinOtpServiceAdapterTest extends AbstractServiceTest
{
    const VALID_TOKEN = '123456';

    const INVALID_TOKEN = '000000';

    /**
     * @var PinOtpServiceAdapter
     */
    private $otpServiceAdapter;

    /**
     * @var  MotIdentityProviderInterface $motIdentityProvider
     */
    private $motIdentityProvider;

    /**
     * @var  PersonRepository $personRepository
     */
    private $personRepository;

    /**
     * @var  ConfigurationRepository $configurationRepository
     */
    private $configurationRepository;

    /**
     * @var Person
     */
    private $mockPerson;

    protected function setUp()
    {
        $this->motIdentityProvider = XMock::of(AuthenticationService::class);
        $this->motIdentityProvider->expects($this->any())
            ->method('getIdentity')
            ->will($this->returnValue(new MotIdentity(5, 'test')));

        $hash = new BCryptHashFunction();
        $pin = $hash->hash(self::VALID_TOKEN);

        $this->mockPerson = (new Person())->setFirstName('test')
            ->setPin($pin);

        $this->personRepository = $this->getMockRepository(PersonRepository::class);
        $this->personRepository->expects($this->any())
            ->method('get')
            ->will($this->returnValue($this->mockPerson));

        $this->configurationRepository = $this->getMockRepository(ConfigurationRepository::class);

        $this->otpServiceAdapter = new PinOtpServiceAdapter(
            $this->motIdentityProvider,
            $this->personRepository,
            $this->configurationRepository
        );
    }

    public function testItIsAnOtpServiceAdapter()
    {
        $this->assertInstanceOf(OtpServiceAdapter::class, $this->otpServiceAdapter);
    }

    /**
     * @expectedException \DvsaAuthentication\Service\Exception\OtpException
     */
    public function testAuthenticateWhenTokenInvalidShouldThrowException()
    {
        //given
        $invalidToken = self::INVALID_TOKEN;
        $this->mockMaxAttemptsCount(1);

        //when
        $this->otpServiceAdapter->authenticate($invalidToken);

        //then
        //(see @expectedException)
    }

    public function testAuthenticateWhenTokenValidShouldNotThrowException()
    {
        //given
        $validToken = self::VALID_TOKEN;
        $this->mockMaxAttemptsCount(1);

        $this->otpServiceAdapter->authenticate($validToken);
    }

    public function testAuthenticateWhenTokenInvalidProvidesCorrectAttemptCounts()
    {
        //given
        $invalidToken = self::INVALID_TOKEN;
        $expectedMaxNumberOfAttempts = 6543;
        $this->mockMaxAttemptsCount($expectedMaxNumberOfAttempts);
        $failedAttempts = 234;
        $this->mockPerson->setOtpFailedAttempts($failedAttempts);
        $expectedAttemptsLeft = $expectedMaxNumberOfAttempts - $failedAttempts - 1;

        //when
        try {
            $this->otpServiceAdapter->authenticate($invalidToken);
            $this->fail('Should never get here');
        } catch (OtpException $ex) {
            //then
            $errorData = $ex->getErrorData();
            $this->assertArrayHasKey('attempts', $errorData);
            $attemptsData = $errorData['attempts'];
            $this->assertEquals($expectedMaxNumberOfAttempts, $attemptsData['total']);
            $this->assertEquals($expectedAttemptsLeft, $attemptsData['left']);
        }
    }

    public function testAuthenticateWhenTokenValidShouldResetFailedAttemptsCount()
    {
        //given
        $validToken = self::VALID_TOKEN;
        $expectedMaxNumberOfAttempts = 6543;
        $this->mockMaxAttemptsCount($expectedMaxNumberOfAttempts);
        $failedAttempts = 234;
        $this->mockPerson->setOtpFailedAttempts($failedAttempts);

        //when
        $this->otpServiceAdapter->authenticate($validToken);

        //then
        $this->assertEquals(0, $this->mockPerson->getOtpFailedAttempts());
    }

    private function mockMaxAttemptsCount($maxAttemptsCount)
    {
        $this->configurationRepository->expects($this->any())
            ->method('getValue')
            ->with('otpMaxNumberOfAttempts')
            ->will($this->returnValue($maxAttemptsCount));
    }
}