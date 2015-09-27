<?php

namespace DvsaAuthenticationTest\Service;

use DvsaAuthentication\Service\OtpServiceAdapter;
use DvsaAuthentication\Service\OtpServiceAdapter\DelegatingOtpServiceAdapter;
use DvsaEntities\Entity\AuthenticationMethod;
use DvsaEntities\Entity\Person;

class DelegatingOtpServiceAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DelegatingOtpServiceAdapter
     */
    private $otpServiceAdapter;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $pinOtpServiceAdapter;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $cardOtpServiceAdapter;

    protected function setUp()
    {
        $this->pinOtpServiceAdapter = $this->getMock(OtpServiceAdapter::class);
        $this->cardOtpServiceAdapter = $this->getMock(OtpServiceAdapter::class);
        $this->otpServiceAdapter = new DelegatingOtpServiceAdapter(
            $this->pinOtpServiceAdapter,
            $this->cardOtpServiceAdapter
        );
    }

    public function testItIsAnOtpServiceAdapter()
    {
        $this->assertInstanceOf(OtpServiceAdapter::class, $this->otpServiceAdapter);
    }

    /**
     * @dataProvider provideAuthenticationResults
     */
    public function testItAuthenticatesWithThePinOtpServiceAdapterByDefault($result)
    {
        $person = $this->getPerson(AuthenticationMethod::PIN_CODE);

        $this->pinOtpServiceAdapter->expects($this->any())
            ->method('authenticate')
            ->with($person, '123123')
            ->willReturn($result);

        $this->cardOtpServiceAdapter->expects($this->never())
            ->method('authenticate');

        $this->assertSame($result, $this->otpServiceAdapter->authenticate($person, '123123'));
    }

    /**
     * @dataProvider provideAuthenticationResults
     */
    public function testItAuthenticatesWithTheCardOtpServiceAdapter($result)
    {
        $person = $this->getPerson(AuthenticationMethod::CARD_CODE);

        $this->pinOtpServiceAdapter->expects($this->never())
            ->method('authenticate');

        $this->cardOtpServiceAdapter->expects($this->any())
            ->method('authenticate')
            ->with($person, '123123')
            ->willReturn($result);

        $this->assertSame($result, $this->otpServiceAdapter->authenticate($person, '123123'));
    }

    public function provideAuthenticationResults()
    {
        return [[true], [false]];
    }

    /**
     * @param string $authenticationMethodCode
     *
     * @return Person
     */
    protected function getPerson($authenticationMethodCode)
    {
        $authenticationMethod = (new AuthenticationMethod())
            ->setCode($authenticationMethodCode);

        $person = new Person();
        $person->setAuthenticationMethod($authenticationMethod);

        return $person;
    }
}