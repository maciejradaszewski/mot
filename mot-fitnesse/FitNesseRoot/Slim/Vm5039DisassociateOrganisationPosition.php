<?php

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\CredentialsProvider;

class Vm5039DisassociateOrganisationPosition
{
    /** @var \TestSupportHelper */
    private $testSupportHelper;

    /**
     * @description Generate AO1User
     * @var array
     */
    private $areaOffice1User;
    /**
     * @description Generate AO2User
     * @var array
     */
    private $areaOffice2User;
    /**
     * @description Generated AE from test support
     * @var array
     */
    private $ae;
    /**
     * @description Generated AED from test support
     * @var array
     */
    private $aed;
    /**
     * @description Generated AEDM from test support
     * @var array
     */
    private $aedm;
    /**
     * @description Generated VTS from test support
     * @var array
     */
    private $vts;
    /**
     * @description what type of user is removing a site position? ie. AEDM, AED, SITE-MANAGER
     * @var string
     */
    private $nominator;
    /**
     * @description who are we trying to remove? ie. AEDM, AED, SITE-MANAGER, TESTER
     * @var string
     */
    private $nominee;
    /**
     * @description Positions at the generated site after generating data
     * @var array
     */
    private $positions;
    /**
     * @description if any errors are thrown from the API this variable will store it
     * @var string
     */
    private $error;

    public function __construct()
    {
        $this->testSupportHelper = new TestSupportHelper();
    }

    public function success()
    {
        // Generate all users
        $this->generateData();

        /** @var int $nominatorUserId */
        $nominatorUserId = $this->getNominatorUserId();
        /** @var int $nomineeUserId */
        $nomineeUserId = $this->getNomineeUserId();

        // Get positionId to remove and nominators username
        $positionId = false;
        $nominatorUsername = false;
        foreach ($this->positions as $position) {
            if ($position['person']['id'] == $nomineeUserId) {
                $positionId = $position['id'];
            }
        }

        // Get nominator details from API
        $urlBuilder = new UrlBuilder;
        $enforcementClient = FitMotApiClient::create(TestShared::USERNAME_ENFORCEMENT, TestShared::PASSWORD);
        $nominatorDetails = $enforcementClient->get($urlBuilder->personalDetails()->routeParam('id', $nominatorUserId));

        // All we need is the username
        $nominatorUsername = $nominatorDetails['username'];

        // Sanity checks
        if (!$positionId) {
            throw new \Exception('Nominee position ID could not be found');
        }

        if (!$nominatorUsername) {
            throw new \Exception('Nominator Username could not be found');
        }

        // nominator based API client
        $nominatorClient = FitMotApiClient::create($nominatorUsername, TestShared::PASSWORD);

        try {
            // removing position
            $nominatorClient->delete(UrlBuilder::organisationPositionNomination($this->ae['id'], $positionId));
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }

        // verifying position is no longer there
        return $this->isPositionRemoved($positionId);
    }

    /**
     * Who will be disassociating the specified nominee?
     * @param $value
     */
    public function setNominator($value)
    {
        $this->nominator = $value;
    }

    public function getNominatorUserId()
    {
        return $this->getSpecificUserId($this->nominator);
    }

    /**
     * Who will be removed from position
     * @param $value
     */
    public function setNominee($value)
    {
        $this->nominee = $value;
    }

    public function getNomineeUserId()
    {
        return $this->getSpecificUserId($this->nominee);
    }

    public function error()
    {
        return $this->error;
    }

    /**
     * @param $position
     * @return bool
     */
    private function getSpecificUserId($position)
    {
        $userId = false;

        switch ($position) {

            case 'AED':
                $userId = $this->aed['personId'];
                break;

            case 'AEDM':
                $userId = $this->aedm['personId'];
                break;
        }

        if (!$userId) {
            throw new \Exception('Specified position is invalid or generated data does not exist.');
        }

        return $userId;
    }

    /**
     * @param array $positions
     * @param $posId
     *
     * @return bool
     */
    private function isPositionRemoved($posId)
    {
        $aeUrl = (new UrlBuilder())->organisationPositionNomination($this->ae['id']);
        $result = TestShared::get(
            $aeUrl->toString(), $this->areaOffice1User['username'], TestShared::PASSWORD
        );

        $positions = $result;

        foreach ($positions as $pos) {
            if ($pos['id'] === $posId) {
                return 'FALSE';
            }
        }
        return 'TRUE';
    }

    /**
     * Generate data
     */
    private function generateData()
    {
        $this->areaOffice1User = $this->testSupportHelper->createareaOffice1User();
        $this->areaOffice2User = $this->testSupportHelper->createAreaOffice2User();

        $this->testSupportHelper = new TestSupportHelper(new CredentialsProvider($this->areaOffice1User['username'], TestShared::PASSWORD));
        $this->ae = $this->testSupportHelper->createAuthorisedExaminer($this->areaOffice1User['username']);

        $this->testSupportHelper = new TestSupportHelper(new CredentialsProvider($this->areaOffice2User['username'], TestShared::PASSWORD));
        $this->aed = $this->testSupportHelper->createAuthorisedExaminerDelegate($this->areaOffice2User['username'], null, [ $this->ae['id'] ]);
        $this->aedm = $this->testSupportHelper->createAuthorisedExaminerDesignatedManagement($this->areaOffice2User['username'], null, [ $this->ae['id'] ]);

        // Get AE positions
        $aeUrl = (new UrlBuilder())->organisationPositionNomination($this->ae['id']);
        $result = TestShared::get(
            $aeUrl->toString(), $this->areaOffice1User['username'], TestShared::PASSWORD
        );

        $this->positions = $result;
    }

}
