<?php

namespace TestSupport\Service;

use Doctrine\ORM\EntityManager;
use TestSupport\Helper\DataGeneratorHelper;
use TestSupport\Helper\TestDataResponseHelper;
use TestSupport\Model\AccountPerson;
use Zend\ServiceManager\ServiceManager;
use TestSupport\Service\AccountService;

/**
 * Creates new accounts in system with different roles.
 *
 * Should not be deployed in production.
 */
class AccountDataService
{
    /**
     * @var AccountService
     */
    private $accountService;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Set the service manager object
     * @param ServiceManager $serviceManager
     */
    public function __construct(AccountService $accountService, EntityManager $entityManager)
    {
        $this->accountService = $accountService;
        $this->entityManager = $entityManager;
    }

    /**
     * @param array  $data
     * @param string $role (user role if null)
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function create($data, $role = null)
    {
        $dataGenerator = DataGeneratorHelper::buildForDifferentiator($data);

        $account = $this->accountService->createAccount(
            $role,
            $dataGenerator,
            new AccountPerson($data, $dataGenerator)
        );

        return TestDataResponseHelper::jsonOk([
            "message"  => $this->createMessage($role),
            "username" => $account->getUsername(),
            "password" => $account->getPassword(),
            "personId" => $account->getPersonId(),
        ]);
    }

    /**
     * @param string $role
     *
     * @return string
     */
    private function createMessage($role)
    {
        return is_null($role) ? 'User created' : strtolower(str_replace('-', ' ', $role)) . ' created';
    }

    public function addRole($personId, $role)
    {
        $stmt = $this->entityManager->getConnection()->prepare("
            INSERT INTO person_system_role_map (`person_id`, `person_system_role_id`, `status_id`, `created_by`)
            VALUES (
                ?,
                (SELECT `id` FROM `person_system_role` WHERE `name` = ?),
                (SELECT `id` FROM `business_role_status` WHERE `code` = 'AC'),
                1
            )
        ");

        $stmt->bindValue(1, $personId);
        $stmt->bindValue(2, $role);
        $stmt->execute();
    }
}
