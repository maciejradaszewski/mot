<?php

namespace TestSupport\Controller;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Enum\OrganisationBusinessRoleCode;
use DvsaCommon\Enum\OrganisationBusinessRoleId;
use TestSupport\FieldValidation;
use TestSupport\Helper\DataGeneratorHelper;
use TestSupport\Helper\NominatorTrait;
use TestSupport\Helper\RestClientGetterTrait;
use TestSupport\Helper\TestDataResponseHelper;
use TestSupport\Helper\TestSupportAccessTokenManager;
use TestSupport\Model\Account;
use TestSupport\Model\AccountPerson;
use TestSupport\Service\AccountService;
use TestSupport\Service\AccountDataService;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Creates AEDMs for use by tests.
 *
 * Should not be deployed in production.
 */
class AedmDataController extends BaseTestSupportRestfulController
{
    use RestClientGetterTrait;
    use NominatorTrait;

    protected $accountPerson;

    /**
     *
     * @param mixed $data optional data with differentiator,
     *                    requestor => {username,password} DVSA scheme management user with whom to assign AEDM role
     *                    aeIds => IDs of AEs for which the user is an AEDM
     *
     * @return void|JsonModel username of new AEDM
     */
    public function create($data)
    {
        TestSupportAccessTokenManager::addSchemeManagerAsRequestorIfNecessary($data);

        FieldValidation::checkForRequiredFieldsInData(['aeIds'], $data);

        /** @var $accountService AccountService */
        $accountService = $this->getServiceLocator()->get(AccountService::class);

        if (!isset($data['personId'])) {
            $dataGeneratorHelper = DataGeneratorHelper::buildForDifferentiator($data);

            $this->accountPerson = new AccountPerson($data, $dataGeneratorHelper);
            $account = $accountService->createAccount(
                OrganisationBusinessRoleCode::AUTHORISED_EXAMINER_DESIGNATED_MANAGER,
                $dataGeneratorHelper, $this->accountPerson
            );
        } else {
            $account = new Account($data);
        }

        $this->nominateUserForRoleInAes(
            $this->getRestClientService($data),
            $account->getPersonId(),
            OrganisationBusinessRoleId::AUTHORISED_EXAMINER_DESIGNATED_MANAGER,
            $data['aeIds']
        );

        $this->activateBusinessRoleForPersonInOrganisation($account->getPersonId());

        return TestDataResponseHelper::jsonOk(
            [
                "message"  => "Authorised Examiner (Designated Manager) created",
                "username" => $account->getUsername(),
                "password" => $account->getPassword(),
                "personId" => $account->getPersonId(),
                "firstName" => $account->getFirstName(),
                "middleName" => $this->accountPerson->getMiddleName(),
                "surname"  => $account->getSurname()
            ]
        );
    }
}
