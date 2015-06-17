<?php

/**
 * Helper fitnesse test to be used before other fitnesse tests to create user accounts with given system role.
 * Creates:
 * - TODO: user
 * - vehicle examiner
 * - TODO: DVSA schema management
 * - TODO: DVSA schema user
 * - TODO: finance
 * - TODO: customer service manager
 * - TODO: customer service centre operative
 * - TODO: DVLA operative
 * - TODO: DVSA area office 1
 * - TODO: DVSA area office 2
 *
 * Sample table with all available columns:
 *
 *
!| GenerateSystemRoleForOtherFitnesseTests                          |
|veName?|veId?|dvlaOperativeName?|dvlaOperativeId?|cscoName?|cscoId?|
|       |     |                  |                |         |       |
 */
class GenerateSystemRoleForOtherFitnesseTests
{
    /** @var TestSupportHelper */
    private $testSupportHelper;

    /**
     * @var array
     */
    private $ve;

    private $csco;

    private $dvlaOperative;

    public function __construct()
    {
        $this->testSupportHelper = new TestSupportHelper();
    }

    public function veName()
    {
        return $this->getVe()['username'];
    }

    public function veId()
    {
        return $this->getVe()['id'];
    }

    public function cscoName()
    {
        return $this->getCsco()['username'];
    }

    public function cscoId()
    {
        return $this->getCsco()['id'];
    }

    public function dvlaOperativeName()
    {
        return $this->getDvlaOperative()['username'];
    }

    public function dvlaOperativeId()
    {
        return $this->getDvlaOperative()['id'];
    }

    private function getVe()
    {
        if (null === $this->ve) {
            $this->ve = $this->testSupportHelper->createVehicleExaminer();
        }

        return $this->ve;
    }

    private function getCsco()
    {
        if (null === $this->csco) {
            $this->csco = $this->testSupportHelper->createCustomerServiceCentreOperative();
        }

        return $this->csco;
    }

    private function getDvlaOperative()
    {
        if (null === $this->dvlaOperative) {
            $this->dvlaOperative = $this->testSupportHelper->createDvlaOperative();
        }

        return $this->dvlaOperative;
    }
}
