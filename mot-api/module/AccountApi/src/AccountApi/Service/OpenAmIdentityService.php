<?php

namespace AccountApi\Service;

use AccountApi\Service\Exception\OpenAmChangePasswordException;
use Dvsa\OpenAM\Model\OpenAMExistingIdentity;
use Dvsa\OpenAM\Model\OpenAMLoginDetails;
use Dvsa\OpenAM\OpenAMClient;
use DvsaCommonApi\Service\Exception\ServiceException;

/**
 * Wrapper for the OpenAMClient service with administrator credentials to perform password changes and account
 * unlocking operations.
 */
class OpenAmIdentityService
{
    /**
     * @var OpenAMClient
     */
    private $openAMClient;

    /**
     * @var string
     */
    private $realm;

    /**
     * @param OpenAMClient $openAMClient
     * @param string       $realm
     */
    public function __construct(OpenAMClient $openAMClient, $realm)
    {
        $this->openAMClient = $openAMClient;
        $this->realm        = $realm;
    }

    /**
     * @param string $username
     * @param string $newPassword
     *
     * @throws OpenAmChangePasswordException
     */
    public function changePassword($username, $newPassword)
    {
        $identity = new OpenAMExistingIdentity($username, $this->realm, ['userpassword' => $newPassword]);
        try {
            $this->openAMClient->updateIdentity($identity);
        } catch (\Exception $e) {
            throw new OpenAmChangePasswordException($e->getMessage(), ServiceException::DEFAULT_STATUS_CODE, $e);
        }
    }

    /**
     * @param string $username
     *
     * @return bool
     */
    public function unlockAccount($username)
    {
        $userDetails = new OpenAMLoginDetails($username, null, $this->realm);
        $status      = $this->openAMClient->unlockAccount($userDetails);

        return $status;
    }
}
