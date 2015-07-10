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
use UserApi\Person\Service\BasePersonService;
use UserApi\Person\Service\PersonalAuthorisationForMotTestingService as PersonalAuthorisationService;
use UserApi\Person\Service\PersonalDetailsService;
use UserApi\Person\Service\PersonService;
use UserApi\Person\Service\Validator\BasePersonValidator;
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
                BasePersonService::class            => Factory\BasePersonServiceFactory::class,
                BasePersonValidator::class          => Factory\BasePersonValidatorFactory::class,
                DashboardService::class             => Factory\DashboardServiceFactory::class,
                HelpDeskPersonService::class        => Factory\HelpDeskPersonServiceFactory::class,
                MessageService::class               => Factory\MessageServiceFactory::class,
                PersonalAuthorisationService::class => Factory\PersonalAuthorisationForMotTestingServiceFactory::class,
                PersonalDetailsService::class       => Factory\PersonalDetailsServiceFactory::class,
                PersonService::class                => Factory\PersonServiceFactory::class,
                SpecialNoticeService::class         => Factory\SpecialNoticeServiceFactory::class,
                UserStatsService::class             => Factory\UserStatsServiceFactory::class,
                MailerService::class                => MailerServiceFactory::class,
                ResetClaimAccountService::class     => ResetClaimAccountServiceFactory::class,
            ],
        ];
    }
}
