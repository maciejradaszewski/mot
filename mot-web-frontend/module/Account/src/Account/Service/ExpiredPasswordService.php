<?php

namespace Account\Service;

use Core\Service\MotFrontendIdentityProviderInterface;
use Dvsa\OpenAM\Model\OpenAMLoginDetails;
use Dvsa\OpenAM\OpenAMClientInterface;
use DvsaClient\Mapper\ExpiredPasswordMapper;
use DvsaCommon\Configuration\MotConfig;
use DvsaCommon\Date\DateUtils;

class ExpiredPasswordService
{
    private $identityProvider;

    private $config;

    private $mapper;

    private $openAmClient;

    private $realm;

    public function __construct(
        MotFrontendIdentityProviderInterface $identityProvider,
        MotConfig $config,
        ExpiredPasswordMapper $mapper,
        OpenAMClientInterface $openAmClient,
        $realm
    )
    {
        $this->identityProvider = $identityProvider;
        $this->config = $config;
        $this->mapper = $mapper;
        $this->openAmClient = $openAmClient;
        $this->realm = $realm;
    }

    public function getExpiryDateForCurrentUser()
    {
        $userName = $this->identityProvider->getIdentity()->getUsername();

        return $this->getExpiryDateForUser($userName);
    }

    public function getExpiryDateForUser($username)
    {
        $expirationDate = $this->openAmClient->getPasswordExpiryDate(new OpenAMLoginDetails($username, null, $this->realm));

        $expirationDate = DateUtils::roundUp($expirationDate);

        $expirationDate = $expirationDate->modify("- " . $this->getGracePeriod());

        return $expirationDate;
    }

    public function sentExpiredPasswordNotificationIfNeeded($token, $username)
    {
        if ($this->isExpiryPasswordEnabled()) {
            if ($this->willUserPasswordExpireShortly($username)) {
                $expiryDate = $this->getExpiryDateForUser($username);
                $this->mapper->postPasswordExpiredDate($token, $expiryDate);
            }
        }
    }

    private function isExpiryPasswordEnabled()
    {
        return $this->config->get('feature_toggle', 'openam.password.expiry.enabled');
    }

    private function willUserPasswordExpireShortly($username)
    {
        $longestNotificationPeriod = $this->getLongestPeriod();

        if ($longestNotificationPeriod === null) {
            return false;
        }

        $expiryDate = $this->getExpiryDateForUser($username);
        $now = new \DateTime();
        $whenToSendNotifications = clone $expiryDate;
        $whenToSendNotifications = $whenToSendNotifications->modify('- ' . $longestNotificationPeriod . 'days');

        if ($now > $expiryDate) {
            return false;
        }

        return $now >= $whenToSendNotifications;
    }

    private function getLongestPeriod()
    {
        $periods = $this->config->get('password_expiry_notification_days');
        if (!$periods) {
            return null;
        }

        return max($periods);
    }

    private function getGracePeriod()
    {
        return $this->config->get('password_expiry_grace_period');
    }
}
