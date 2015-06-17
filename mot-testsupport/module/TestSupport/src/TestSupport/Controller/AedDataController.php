<?php

namespace TestSupport\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use DvsaCommon\Enum\OrganisationBusinessRoleCode;
use DvsaCommon\Enum\OrganisationBusinessRoleId;
use TestSupport\FieldValidation;
use TestSupport\Helper\DataGeneratorHelper;
use TestSupport\Helper\NominatorTrait;
use TestSupport\Helper\RestClientGetterTrait;
use TestSupport\Helper\TestDataResponseHelper;
use TestSupport\Model\Account;
use TestSupport\Model\AccountPerson;
use TestSupport\Service\AccountService;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Creates AEDs for use by tests.
 *
 * Should not be deployed in production.
 */
class AedDataController extends BaseTestSupportRestfulController
{
    use RestClientGetterTrait;
    use NominatorTrait;

    /**
     *
     * @param mixed $data optional data with differentiator,
     *                    schmUsername => username of DVSA scheme management user with whom to assign AED role
     *                    aeIds => IDs of AEs for which the user is an AED
     *
     * @return void|JsonModel username of new AED
     */
    public function create($data)
    {
        $roleId = OrganisationBusinessRoleId::AUTHORISED_EXAMINER_DELEGATE;
        FieldValidation::checkForRequiredFieldsInData(['aeIds'], $data);

        /** @var $accountService AccountService */
        $accountService = $this->getServiceLocator()->get(AccountService::class);
        $restClient = $this->getRestClientService($data);

        if (!isset($data['personId'])) {
            $dataGeneratorHelper = DataGeneratorHelper::buildForDifferentiator($data);
            $account = $accountService->createAccount(
                OrganisationBusinessRoleCode::AUTHORISED_EXAMINER_DELEGATE,
                $dataGeneratorHelper,
                new AccountPerson($data, $dataGeneratorHelper)
            );
        } else {
            $account = new Account($data);
        }

        $this->nominateUserForRoleInAes(
            $this->getRestClientService($data),
            $account->getPersonId(),
            OrganisationBusinessRoleId::AUTHORISED_EXAMINER_DELEGATE,
            $data['aeIds']
        );

        $this->activateBusinessRoleForPersonInOrganisation($account->getPersonId());

        return TestDataResponseHelper::jsonOk(
            [
                "message" => "Authorised Examiner (Delegate) created",
                "username" => $account->getUsername(),
                "password" => $account->getPassword(),
                "personId" => $account->getPersonId(),
                "firstName" => $account->getFirstName(),
                "surname"  => $account->getSurname()
            ]
        );
    }
}
