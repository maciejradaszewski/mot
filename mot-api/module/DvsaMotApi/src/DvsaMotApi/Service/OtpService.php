<?php

namespace DvsaMotApi\Service;

use DvsaCommonApi\Service\Exception\OtpException;
use DvsaEntities\Entity\Person;
use DvsaEntities\Repository\ConfigurationRepositoryInterface;
use DvsaEntities\Repository\PersonRepository;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Validator\Authentication;

/**
 * Class OtpService provides One Time Password verification
 *
 * @package DvsaMotApi\Service
 */
class OtpService
{

    const CONFIG_PARAM_OTP_MAX_NUMBER_OF_ATTEMPTS = 'otpMaxNumberOfAttempts';

    /**
     * @var AuthenticationService
     */
    private $motIdentityProvider;

    /**
     * @var PersonRepository
     */
    private $personRepository;

    /**
     * @var ConfigurationRepositoryInterface
     */
    private $configurationRepository;

    public function __construct(
        AuthenticationService $motIdentityProvider,
        PersonRepository $personRepository,
        ConfigurationRepositoryInterface $configurationRepository
    ) {
        $this->motIdentityProvider = $motIdentityProvider;
        $this->personRepository = $personRepository;
        $this->configurationRepository = $configurationRepository;
    }

    /**
     * Check token and throw exception if invalid
     *
     * @param $token string OTP token
     *
     * @throws OtpException if authentication fails
     */
    public function authenticate($token)
    {
        $person = $this->getPerson();

        $failedAttempts = $person->getOtpFailedAttempts() ? : 0;
        $maxNumberOfAttempts = (int)$this->configurationRepository->getValue(
            self::CONFIG_PARAM_OTP_MAX_NUMBER_OF_ATTEMPTS
        );

        if (empty($token)) {
            throw new OtpException($maxNumberOfAttempts, $maxNumberOfAttempts);
        }

        if ($this->isTokenValid($token)) {
            if ($failedAttempts != 0) {
                $this->updateFailedAttempts($person, 0);
            }
        } else {
            $failedAttempts++;
            $this->updateFailedAttempts($person, $failedAttempts);

            $attemptsLeft = $maxNumberOfAttempts - $failedAttempts;

            if ($failedAttempts >= $maxNumberOfAttempts) {
                throw new OtpException($maxNumberOfAttempts, 0);
            }

            throw new OtpException($maxNumberOfAttempts, $attemptsLeft);
        }
    }

    private function getPerson()
    {
        $userId = $this->motIdentityProvider->getIdentity()->getUserId();
        return $this->personRepository->get($userId);
    }

    private function isTokenValid($token)
    {
        $person = $this->getPerson();

        return password_verify(
            $token,
            $person->getPin()
        );
    }

    private function updateFailedAttempts(Person $person, $otpFailedAttempts)
    {
        $person->setOtpFailedAttempts($otpFailedAttempts);
        $this->personRepository->persist($person);
        $this->personRepository->flush($person);
    }
}
