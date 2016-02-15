<?php
namespace MailerApiTest\Logic;

use DvsaCommon\Dto\Mailer\MailerDto;
use DvsaCommon\Validator\EmailAddressValidator;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\Person;
use DvsaMotApi\Service\UserService;
use MailerApi\Logic\ClaimAccountReminder;
use MailerApi\Service\MailerService;
use MailerApi\Validator\MailerValidator;
use MailerApiTest\Mixin\ServiceManager;
use PHPUnit_Framework_TestCase;
use Zend\Log\Logger;

class ClaimAccountReminderTest extends PHPUnit_Framework_TestCase
{
    use ServiceManager;

    protected $validator;
    protected $mockUserService;
    protected $mockMailerService;
    protected $mailDto;
    protected $config;

    public function setUp()
    {
        $appTestConfig = include getcwd() . '/test/test.config.php';
        Bootstrap::init($appTestConfig);

        $this->prepServiceManager();

        $this->mockUserService = $this->setMockServiceClass(UserService::class, ['findPerson']);
        $this->validator = new MailerValidator($this->mockUserService);

        $mockLogger = XMock::of(Logger::class, []);
        $this->serviceManager->setService('Application\Logger', $mockLogger);

        $this->mockMailerService = $this->setMockServiceClass(
            MailerService::class,
            ['send', 'validate']
        );

        // Turn off the logging feature
        $this->config = $this->serviceManager->get('Config');
        $this->config['mailer']['logfile'] = '';
        $this->config['mailer']['sendingAllowed'] = false;
    }

    public function testSendingWithUserId()
    {
        // Must pass validating the user-id first
        $mockPerson = XMock::of(Person::class, ['getId', 'getEmail']);
        $this->mockMailerService->expects($this->once())
            ->method('validate')
            ->willReturn(true);

        $this->mockMailerService->expects($this->once())
            ->method('send')
            ->willReturn(true);

        $this->mailDto = new MailerDto();
        $this->mailDto->setData(['userid' => 5, 'user' => $mockPerson]);

        $emailAddress = 'claimaccountremindertest@' . EmailAddressValidator::TEST_DOMAIN;

        $logic = new ClaimAccountReminder(
            $this->config['mailer'],
            $this->config['helpdesk'],
            $this->mockMailerService,
            $this->mailDto,
            $emailAddress
        );

        $this->assertTrue($logic->send());
    }
}
