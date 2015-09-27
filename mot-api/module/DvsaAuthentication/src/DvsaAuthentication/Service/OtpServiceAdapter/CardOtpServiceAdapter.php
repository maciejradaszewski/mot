<?php

namespace DvsaAuthentication\Service\OtpServiceAdapter;

use Dvsa\OpenAM\Exception\OpenAMClientException;
use Dvsa\OpenAM\Model\OpenAMLoginDetails;
use Dvsa\OpenAM\OpenAMClientInterface;
use DvsaAuthentication\Service\OtpServiceAdapter;
use DvsaEntities\Entity\Person;

class CardOtpServiceAdapter implements OtpServiceAdapter
{
    const REALM = '/';

    const AUTHENTICATION_MODULE = 'OATH';

    /**
     * @var OpenAMClientInterface
     */
    private $openAMClient;

    /**
     * @param OpenAMClientInterface $openAMClient
     */
    public function __construct(OpenAMClientInterface $openAMClient)
    {
        $this->openAMClient = $openAMClient;
    }

    /**
     * @param Person $person
     * @param string $token
     *
     * @return bool
     */
    public function authenticate(Person $person, $token)
    {
        $loginDetails = new OpenAMLoginDetails($person->getUsername(), $token, self::REALM, self::AUTHENTICATION_MODULE);

        try {
            return $this->openAMClient->validateCredentials($loginDetails);
        } catch (OpenAMClientException $e) {
            return false;
        }
    }
}