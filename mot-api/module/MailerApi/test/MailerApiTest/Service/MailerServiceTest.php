<?php

namespace MailerApiTest\Service;

use MailerApi\Validator\MailerValidator;
use PHPUnit_Framework_TestCase;
use MailerApi\Service\MailerService;
use MailerApiTest\Mixin\ServiceManager;
use Zend\Log\Logger;
use Zend\Log\Writer\Mail;
use DvsaCommonTest\TestUtils\XMock;

class MailerServiceTest extends PHPUnit_Framework_TestCase
{
    use ServiceManager;

    /** @var  MailerService */
    protected $mailerService;

    /** @var  MailerValidator */
    protected $mockValidator;

    public function setUp()
    {
        $this->prepServiceManager();
        $this->newEngineWithConfig([]);
    }

    public function newEngineWithConfig($config)
    {
        $this->setConfig($config);
        $this->mockValidator = XMock::of(MailerValidator::class, ['validate']);
        $this->mailerService = new MailerService($config, null, $this->mockValidator);
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\BadRequestException
     * @expectedExceptionMessage recipient
     */
    public function testRejectsNonStringRecipientWithException()
    {
        $this->mailerService->send(42, '', '');
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\BadRequestException
     * @expectedExceptionMessage recipient
     */
    public function testRejectsEmptyStringRecipientWithException()
    {
        $this->mailerService->send('', '', '');
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\BadRequestException
     * @expectedExceptionMessage subject
     */
    public function testRejectsNonStringSubjectWithException()
    {
        $this->mailerService->send('hello', -1, '');
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\BadRequestException
     * @expectedExceptionMessage subject
     */
    public function testRejectsEmptyStringSubjectWithException()
    {
        $this->mailerService->send('hello', '', '');
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\BadRequestException
     * @expectedExceptionMessage subject
     */
    public function testRejectsNonStringMessageWithException()
    {
        $this->mailerService->send('hello', '', []);
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\BadRequestException
     * @expectedExceptionMessage subject
     */
    public function testRejectsEmptyMessageSubjectWithException()
    {
        $this->mailerService->send('hello', '', '');
    }

    public function testSendDoesNothingWhenConfiguredNotToSend()
    {
        $this->setConfig(['sendingAllowed' => false]);
        $this->mailerService = new MailerService($this->sm()->get('Config'), null, $this->mockValidator);

        $this->assertFalse(
            $this->mailerService->send(
                'sean.charles@valtech.co.uk',
                'test subject',
                'Hello, World!'
            )
        );
    }

    public function testSendsMessageWithCorrectlySuppliedDetails()
    {
        $mockMail = XMock::of(
            "\\Zend\\Mail\\Message",
            ['setFrom', 'setBody', 'setSubject', 'addTo']
        );

        $mockMail->expects($this->once())->method('setFrom')->with('a unit test');
        $mockMail->expects($this->once())->method('setBody')->with('Hello, World!');
        $mockMail->expects($this->once())->method('setSubject')->with('test subject');
        $mockMail->expects($this->once())->method('addTo')->with('sean.charles@valtech.co.uk');

        $mockMta = XMock::of("\\Zend\\Mail\\Transport\\Sendmail", ['send']);

        $mockMta->expects($this->once())
            ->method('send')
            ->with($mockMail);

        $this->setConfig(['sendingAllowed' => true,
                'mta-class' => $mockMta,
                'mail-class' => $mockMail,
                'sender' => 'a unit test'
            ]
        );
        $this->mailerService = new MailerService($this->sm()->get('Config'), null, $this->mockValidator);

        $this->assertTrue(
            $this->mailerService->send(
                'sean.charles@valtech.co.uk',
                'test subject',
                'Hello, World!'
            )
        );
    }
}
