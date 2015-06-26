<?php

namespace TestSupport\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Enum\CountryOfRegistrationCode;
use DvsaCommon\HttpRestJson\Client;
use DvsaCommon\UrlBuilder\UrlBuilder;
use TestSupport\Helper\DataGeneratorHelper;
use TestSupport\Helper\TestSupportAccessTokenManager;
use TestSupport\Model\Account;
use TestSupport\Model\AccountPerson;

/**
 * Service to deal with accounts in system
 */
class AccountService
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \DvsaCommon\HttpRestJson\Client
     */
    private $restClient;

    /**
     * @var TestSupportAccessTokenManager
     */
    private $tokenManager;

    public function __construct(
        EntityManager $entityManager,
        Client $restClient,
        TestSupportAccessTokenManager $tokenManager
    ) {
        $this->entityManager = $entityManager;
        $this->restClient = $restClient;
        $this->tokenManager = $tokenManager;
    }

    /**
     * @param string              $role
     * @param DataGeneratorHelper $dataGeneratorHelper
     * @param AccountPerson       $accountPerson
     *
     * @return Account
     */
    public function createAccount($role, DataGeneratorHelper $dataGeneratorHelper, AccountPerson $accountPerson)
    {
        $password = "Password1";
        $username = $accountPerson->getUsername();

        $emailAddress = $accountPerson->getEmailAddress();

        /** @var Client $restClient */
        $accessToken = $this->tokenManager->getToken('schememgt', 'Password1');
        $this->restClient->setAccessToken($accessToken);

        $result = $this->restClient->post(UrlBuilder::of()->account()->toString(), [
            'username' => $username,
            'title' => 'Mr',
            'firstName' => $accountPerson->getFirstName(),
            'middleName'=> $accountPerson->getMiddleName(),
            'surname' => $accountPerson->getSurname(),
            'gender' => 'Male',
            'drivingLicenceNumber' => $dataGeneratorHelper->drivingLicenceNumber(),
            'drivingLicenceRegion' => CountryOfRegistrationCode::GB_UK_ENG_CYM_SCO_UK_GREAT_BRITAIN,
            'addressLine1' => $accountPerson->getAddressLine1(),
            'addressLine2' => $accountPerson->getAddressLine2(),
            'town' => 'Ipswich',
            'postcode' => $accountPerson->getPostcode(),
            'phoneNumber' => $accountPerson->getPhoneNumber(),
            'email' => $emailAddress,
            'emailConfirmation' => $emailAddress,
            'password' => $password,
            'passwordConfirmation' => $password,
            'dateOfBirth' => $accountPerson->getDateOfBirth(),
            'accountClaimRequired' => $accountPerson->isAccountClaimRequired(),
            'passwordChangeRequired' => $accountPerson->isPasswordChangeRequired(),
            'pin' => '123456',
        ]);

        if ($accountPerson->isSecurityQuestionsRequired()) {
            foreach ([1, 2] as $questionId) {
                $this->restClient->get('security-question', [
                    'person' => $result['data'],
                    'question' => $questionId,
                ]);
            }
        }

        return new Account([
            'personId' => $result['data'],
            'username' => $username,
            'password' => $password,
            'firstName' => $accountPerson->getFirstName(),
            'surname' => $accountPerson->getSurname(),
        ]);
    }
}
