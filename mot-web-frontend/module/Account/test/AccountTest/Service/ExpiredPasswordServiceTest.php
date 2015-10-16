<?php

namespace AccountTest\Service;

use DvsaCommon\Configuration\MotConfig;
use DvsaClient\Mapper\ExpiredPasswordMapper;
use DvsaCommonTest\TestUtils\XMock;
use Account\Service\ExpiredPasswordService;
use Core\Service\MotFrontendIdentityProviderInterface;
use Dvsa\OpenAM\OpenAMClientInterface;

class ExpiredPasswordServiceTest extends \PHPUnit_Framework_TestCase
{
    const TOKEN = "token";
    const USERNAME = "tester1";

    private $openAMClient;

    private $mapper;

    public function setUp()
    {
        $this->openAMClient = XMock::of(OpenAMClientInterface::class);

        $mapper = XMock::of(ExpiredPasswordMapper::class);
        $mapper
            ->expects($this->exactly(0))
            ->method("postPasswordExpiredDate");

        $this->mapper = $mapper;
    }

    public function test_doNotSendNotification_whenExpiryPasswordIsDisabled()
    {
        $config = $this->createMotConfig(['feature_toggle' => false]);

        $this->sentExpiredPasswordNotificationIfNeeded($config,$this->mapper, $this->openAMClient);
    }

    public function test_doNotSendNotification_whenPasswordExpiryNotificationDaysIsEmpty()
    {
        $config = $this->createMotConfig(['password_expiry_notification_days' => []]);

        $this->sentExpiredPasswordNotificationIfNeeded($config, $this->mapper, $this->openAMClient);
    }

    public function test_doNotSendNotification_whenUsersPasswordNotExpireShortlys()
    {
        $config = $this->createMotConfig();

        $date = new \DateTime();
        $date = $date->modify('+ 38 day');

        $openAMClient = XMock::of(OpenAMClientInterface::class);
        $openAMClient
            ->expects($this->any())
            ->method("getPasswordExpiryDate")
            ->willReturn($date);

        $this->sentExpiredPasswordNotificationIfNeeded($config, $this->mapper, $openAMClient);
    }

    public function test_doNotSendNotification_whenPasswordHasAlreadyExpired()
    {
        $config = $this->createMotConfig();

        $date = new \DateTime();
        $date = $date->modify('+ 28 day');

        $openAMClient = XMock::of(OpenAMClientInterface::class);
        $openAMClient
            ->expects($this->any())
            ->method("getPasswordExpiryDate")
            ->willReturn($date);

        $this->sentExpiredPasswordNotificationIfNeeded($config, $this->mapper, $openAMClient);
    }

    public function test_sendNotification_whenUsersPasswordExpireShortly()
    {
        $config = $this->createMotConfig();

        $date = new \DateTime();
        $date = $date->modify('+ 36 day');

        $openAMClient = XMock::of(OpenAMClientInterface::class);
        $openAMClient
            ->expects($this->any())
            ->method("getPasswordExpiryDate")
            ->willReturn($date);

        $mapper = XMock::of(ExpiredPasswordMapper::class);
        $mapper
            ->expects($this->exactly(1))
            ->method("postPasswordExpiredDate");

        $this->sentExpiredPasswordNotificationIfNeeded($config, $mapper, $openAMClient);
    }

    private function createExpiredPasswordService($config, $mapper, $openAMClient)
    {
        return new ExpiredPasswordService(
            XMock::of(MotFrontendIdentityProviderInterface::class),
            $config,
            $mapper,
            $openAMClient,
            "realm"
        );
    }

    private function sentExpiredPasswordNotificationIfNeeded($config, $mapper, $openAMClient)
    {
        $this
            ->createExpiredPasswordService($config, $mapper, $openAMClient)
            ->sentExpiredPasswordNotificationIfNeeded(self::TOKEN, self::USERNAME);
    }

    /**
     * @param array $data
     * @return MotConfig
     */
    private function createMotConfig(array $data = [])
    {
        $defaults = [
            'feature_toggle' => true,
            'password_expiry_notification_days' => [7, 3, 2, 1],
            'password_expiry_grace_period' => '30 days'
        ];

        $data = array_replace($defaults, $data);

        $config = XMock::of(MotConfig::class);
        $config
            ->expects($this->any())
            ->method("get")
            ->willReturnCallback(function ($args) use ($data) {
                $arg = (is_array($args))? array_shift($args) : $args;
                foreach ($data as $key => $value) {
                    if ($arg === $key) {
                        return $value;
                    }
                }

                return null;
            });

        return $config;
    }
}
