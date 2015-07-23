<?php
namespace MailerApiTest\Logic;

use DvsaCommon\Dto\Mailer\MailerDto;
use MailerApi\Service\MailerService;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\Person;
use DvsaMotApi\Service\UserService;
use MailerApi\Logic\UsernameReminder;
use MailerApi\Validator\MailerValidator;
use MailerApiTest\Mixin\ServiceManager;
use PHPUnit_Framework_TestCase;
use PersonApi\Service\PersonalDetailsService;
use Zend\Log\Logger;

class UsernameReminderTest extends PHPUnit_Framework_TestCase
{
    use ServiceManager;

    protected $validator;
    protected $mockUserService;
    protected $mockDetailsService;
    protected $mockMailerService;
    protected $mailDto;
    protected $logic;

    public function setUp()
    {
        $appTestConfig = include getcwd() . '/test/test.config.php';
        Bootstrap::init($appTestConfig);

        $this->prepServiceManager();

        $this->mockUserService = $this->setMockServiceClass(UserService::class, ['findPerson']);
        $this->validator = new MailerValidator($this->mockUserService);

        $this->mockDetailsService = $this->setMockServiceClass(PersonalDetailsService::class, ['get']);

        $mockLogger = XMock::of(Logger::class, []);
        $this->serviceManager->setService('Application\Logger', $mockLogger);

        $this->mockMailerService = $this->setMockServiceClass(
            MailerService::class,
            ['send', 'validate']
        );
    }

    public function testSendingWithUserId()
    {
        // Turn off the logging feature
        $config = $this->serviceManager->get('Config');
        $config['mailer']['logfile'] = '';
        $config['mailer']['sendingAllowed'] = false;

        // Must pass validating the user-id first
        $mockPerson = XMock::of(Person::class, ['getId', 'getEmail']);
        $mockPerson->expects($this->once())->method('getId')->willReturn(5);
        // Note: overloading mock with getEmail to avoid yet another mock object
        $mockPerson->expects($this->once())->method('getEmail')->willReturn('blah');

        $this->mockDetailsService->expects($this->once())
            ->method('get')
            ->willReturn($mockPerson);

        $this->mockMailerService->expects($this->once())
            ->method('validate')
            ->willReturn(true);

        $this->mockMailerService->expects($this->once())
            ->method('send')
            ->willReturn(false);

        $this->mailDto = new MailerDto();
        $this->mailDto->setData(['userid' => 5, 'user' => $mockPerson]);

        $this->logic = new UsernameReminder(
            $config['mailer'],
            $config['helpdesk'],
            $this->mockMailerService,
            $this->mockDetailsService,
            $this->mailDto
        );

        // Config inhibits sending we should get false here not true
        $this->assertFalse($this->logic->send());
    }
}
