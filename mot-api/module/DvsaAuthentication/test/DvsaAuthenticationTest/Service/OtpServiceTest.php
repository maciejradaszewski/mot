<?php
namespace DvsaAuthenticationTest\Service;

use DvsaCommon\Auth\MotIdentity;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaAuthentication\Service\Exception\OtpException;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\Person;
use DvsaEntities\Repository\ConfigurationRepository;
use DvsaEntities\Repository\PersonRepository;
use DvsaAuthentication\Service\OtpService;
use DvsaCommon\Crypt\Hash\BCryptHashFunction;

/**
 * Class OtpServiceTest
 */
class OtpServiceTest extends AbstractServiceTestCase
{
    const VALID_TOKEN = '123456'; //this token is expected to pass (might be used in selenium tests as well)
    const INVALID_TOKEN = '000000'; //this token is stubbed to fail
    /**
     * @var OtpService $sut
     */
    private $sut;

    /** @var  MotIdentityProviderInterface $motIdentityProvider */
    private $motIdentityProvider;

    /** @var  PersonRepository $personRepository */
    private $personRepository;

    /** @var  ConfigurationRepository $configurationRepository */
    private $configurationRepository;
    /**
     * @var Person
     */
    private $mockPerson;

    private $mockOtpService;

    public function setUp()
    {
        $this->motIdentityProvider = XMock::of(\Zend\Authentication\AuthenticationService::class);
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

        $this->sut = new OtpService(
            $this->motIdentityProvider,
            $this->personRepository,
            $this->configurationRepository
        );

        $this->mockOtpService = XMock::of(OtpService::class, [ 'authenticate', 'isTokenValid' ]);
        $this->mockOtpService->expects($this->any())
                             ->method('isTokenValid')
                             ->will($this->returnValue(true));
        $this->mockOtpService->expects($this->any())
                             ->method('authenticate')
                             ->will($this->returnValue(true));
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
        $this->sut->authenticate($invalidToken);

        //then
        //(see @expectedException)
    }

    public function testAuthenticateWhenTokenValidShouldNotThrowException()
    {
        //given
        $validToken = self::VALID_TOKEN;
        $this->mockMaxAttemptsCount(1);

        $this->mockOtpService->authenticate($validToken);
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
            $this->sut->authenticate($invalidToken);
            $this->assertTrue(false, "Should never get here");
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
        $this->sut->authenticate($validToken);

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
