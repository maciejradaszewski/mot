<?php

namespace UserApi;

use UserApi\Application\Service\AccountService;
use UserApi\Application\Service\ApplicationService;
use UserApi\Application\Service\Validator\AccountValidator;
use UserApi\Dashboard\Service\DashboardService;
use UserApi\Dashboard\Service\UserStatsService;
use UserApi\Factory as Factory;
use UserApi\HelpDesk\Factory\Service\ResetClaimAccountServiceFactory;
use UserApi\HelpDesk\Service\ResetClaimAccountService;
use UserApi\Message\Service\MessageService;
use UserApi\HelpDesk\Service\HelpDeskPersonService;
use PersonApi\Service\BasePersonService;
use PersonApi\Service\PersonalAuthorisationForMotTestingService as PersonalAuthorisationService;
use PersonApi\Service\PersonalDetailsService;
use PersonApi\Service\PersonService;
use PersonApi\Service\Validator\BasePersonValidator;
use UserApi\SpecialNotice\Service\SpecialNoticeService;
use Zend\ServiceManager\ServiceLocatorInterface;
use MailerApi\Factory\MailerServiceFactory;
use MailerApi\Service\MailerService;

/**
 * Class Module
 *
 * @package UserApi
 */
class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                AccountService::class               => Factory\AccountServiceFactory::class,
                AccountValidator::class             => Factory\AccountValidatorFactory::class,
                ApplicationService::class           => Factory\ApplicationServiceFactory::class,
                HelpDeskPersonService::class        => Factory\HelpDeskPersonServiceFactory::class,
                MessageService::class               => Factory\MessageServiceFactory::class,
                SpecialNoticeService::class         => Factory\SpecialNoticeServiceFactory::class,
                MailerService::class                => MailerServiceFactory::class,
                ResetClaimAccountService::class     => ResetClaimAccountServiceFactory::class,
            ],
        ];
    }
}
