<?php

namespace TestSupport\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommon\UrlBuilder\NotificationUrlBuilder;
use DvsaCommon\UrlBuilder\SiteUrlBuilder;
use TestSupport\FieldValidation;
use TestSupport\Helper\DataGeneratorHelper;
use TestSupport\Helper\RestClientGetterTrait;
use TestSupport\Helper\TestDataResponseHelper;
use TestSupport\Model\Account;
use TestSupport\Model\AccountPerson;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\JsonModel;

/**
 * Creates Site Managers OR Site users for use by tests.
 *
 * Should not be deployed in production.
 */
class SiteUserDataService implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;
    use RestClientGetterTrait;

    const STATUS_ID_ACCEPTED = 2;
    const SITE_NOMINATION_ACCEPTED = 'SITE-NOMINATION-ACCEPTED';
    const SITE_POSITION_NOTIFICATION_ID = 5;

    /**
     * @param array  $data optional data with differentiator,
     *                     requestor=> username and password of AEDM/AED with whom to assign site manager role
     *                     siteIds -> list of VTSs for site manager
     * @param string $role
     *
     * @return JsonModel username of new Site User (Manager Or Admin)
     */
    public function create($data, $role)
    {
        FieldValidation::checkForRequiredFieldsInData(['siteIds'], $data);

        if (!isset($data['personId'])) {
            /** @var $accountService AccountService */
            $accountService = $this->getServiceLocator()->get(AccountService::class);
            $dataGeneratorHelper = DataGeneratorHelper::buildForDifferentiator($data);
            $account = $accountService->createAccount(
                $role,
                $dataGeneratorHelper,
                new AccountPerson($data, $dataGeneratorHelper)
            );
        } else {
            $account = new Account($data);
        }

        $this->nominateAndAcceptRoleAtSites($account, $role, $data);

        return TestDataResponseHelper::jsonOk(
            [
                "message"  => $role . ' created',
                "username" => $account->getUsername(),
                "password" => $account->getPassword(),
                "personId" => $account->getPersonId()
            ]
        );
    }

    /**
     * @param Account $account
     * @param string  $role
     * @param array   $data
     */
    private function nominateAndAcceptRoleAtSites(Account $account, $role, array $data)
    {
        $restClient = $this->getRestClientService($data);

        foreach ($data['siteIds'] as $siteId) {
            $restClient->post(
                SiteUrlBuilder::of()->site($siteId)->position()->toString(),
                [
                    'nomineeId' => $account->getPersonId(),
                    'roleCode'  => $role
                ]
            );
        }

        $result = $restClient->get(
            NotificationUrlBuilder::of()->notificationForPerson()
                ->routeParam('personId', $account->getPersonId())->toString()
        );
        $notifications = $result['data'];

        $restClient = $this->getRestClientService(
            [
                'requestor' => [
                    'username' => $account->getUsername(),
                    'password' => $account->getPassword(),
                ]
            ]
        );
        foreach ($notifications as $notification) {
            $restClient->putJson(
                NotificationUrlBuilder::of()->notification($notification['id'])->read()->toString(),
                []
            );
            if ($notification['templateId'] == self::SITE_POSITION_NOTIFICATION_ID
            && empty($notification['readOn'])) {
                $restClient->putJson(
                    NotificationUrlBuilder::of()->notification($notification['id'])->action()->toString(),
                    [
                        'action' => self::SITE_NOMINATION_ACCEPTED
                    ]
                );
            }
        }
    }
}
